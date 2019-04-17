<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Profiles;
use App\SellingProps;

use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('checkAdmin');

        parent::__construct();
    }

    public function users() {
        //
        return view('admin.users', [
            '_currentPage' => 'users',
        ]);
    }

    public function usersSearch(Request $request) {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'redirect' => route('login'),
            ]);
        }
        
        //
        $userId = $user->id;

        $data = $request->all();

        $email = getArrayVar($data, 'email', '');
        $t = getArrayVar($data, 't', '');
        $s = getArrayVar($data, 's', '');
        $es = getArrayVar($data, 'es', '');
        $isFeatured = getArrayVar($data, 'f', 0); // ???
        $q = getArrayVar($data, 'q', '');

        $users = User::with(['profile', 'sellings']);

        if ($q != '') {
            $users = $users->where(function($query) use ($q) {
                return $query->where('name', 'LIKE', '%' . $q . '%')
                    ->orwhere('email', 'LIKE', '%' . $q . '%')
                    ->orWhereHas('profile', function($query1) use ($q) {
                        return $query1->where('company_name', 'LIKE', '%' . $q . '%')
                            ->orWhere('company_description', 'LIKE', '%' . $q . '%')
                            ->orWhere('interests', 'LIKE', '%' . $q . '%')
                        ;
                    })
                    ->orWhereHas('sellings', function($query2) use ($q) {
                        return $query2->where('name', 'LIKE', '%' . $q . '%')
                            ->orWhere('description', 'LIKE', '%' . $q . '%')
                        ;
                    });
            });
        }

        if ($email != '') {
            $users = $users->where('users.email', 'LIKE', '%' . $email . '%');
        }
        if ($t != '') {
            $users = $users->where('users.type', '=', $t);
        }
        if ($s != '') {
            $users = $users->whereHas('Profile', function($query) use ($s) {
                        return $query->where('status', '=', $s);
            });
        }
        if ($es == '0') {
            $users = $users->where('users.email_verified_at', '=', null);
        } else if ($es == '1') {
            $users = $users->where('users.email_verified_at', '<>', null);
        }
        
        $users = $users->paginate(20);

        $users->getCollection()->transform(function ($user) use($userId)  {
            if ($user->id == $userId) {
                $user->is_me = true;
            } else {
                $user->is_me = false;
            }

            if ($user->type == 0) {
                if (!$user->profile) $user->profile = new Profiles(['user_id' => $user->id]);
            }
            if ($user->seller) {
                $user->profile->company_name = $user->seller->name;
                $user->profile->company_description = $user->seller->description;
                $user->profile->state = $user->seller->state;
            }

            return $user;
        });

        return response()->json($users);
    }

    public function delete(Request $request, $userId) {

        $user = User::find($userId);
        if (!$user) {
            return response()->json(jsonResponse(0, ['msg' => 'The user is not existing.']));
        }
        if ($user) {
            $user->delete();
        }
        return response()->json(jsonResponse(1));
    }

    public function login(Request $request, $userId) {

        $user = User::find($userId);
        if (!$user) {
            return response()->json(jsonResponse(0, ['msg' => 'The user is not existing.']));
        }
        if ($user) {
            Auth::login($user);
        }

        return response()->json(jsonResponse(0, ['redirect' => $this->homeByUser($user)]));
    }

    public function modify(Request $request, $userId) {

        $user = User::find($userId);

        if (!$user) {
            return response()->json(jsonResponse(0, ['msg' => 'The user is not existing.']));
        }
        if ($user) {

            $data = $request->all();

            foreach ($data as $key => $value) {
                if ($key == 'pid') continue;
                if ($key == 'email_verified') {
                    $key = 'email_verified_at';
                    $value = (($value == '1') ? date('Y-m-d H:i:s', time()) : null);
                }
                $user->{$key} = $value;
            }
            $user->save();
        }
        return response()->json(jsonResponse(1));
    }

    public function modifyProfile(Request $request, $userId) {

        $user = User::find($userId);

        if (!$user) {
            return response()->json(jsonResponse(0, ['msg' => 'The user is not existing.']));
        }
        if ($user) {

            if ($user->type == 2) return response()->json(jsonResponse(1));

            $data = $request->all();
            if ($user->type == 1) {
                
                if (!$user->profile) {
                    $user->profile = new Profiles(['user_id' => $user->id]);
                }

                /*
                $profile = Profiles::firstOrNew(
                    ['user_id' => $user->id]
                );*/

                foreach ($data as $key => $value) {
                    if ($key == 'pid') continue;
                    $user->profile->{$key} = $value;
                }
                $user->profile->save();

            } else {
                $mapper = [
                    'company_name' => 'name',
                    'company_description' => 'description'
                ];

                $pid = $data['pid'];
                $seller = SellingProps::find($pid);
                if (!$seller) return response()->json(jsonResponse(0, ['msg' => 'Matching profile could not be found.']));
                if (!$seller->isEditable(Auth::user())) return response()->json(jsonResponse(0, ['msg' => 'The action is not allowed.']));

                foreach ($data as $key => $value) {
                    if ($key == 'pid') continue;
                    $skey = isset($mapper[$key]) ? $mapper[$key] : $key;
                    $seller->{$skey} = $value;
                }
                $seller->save();
            }
            
        }
        return response()->json(jsonResponse(1));
    }

    public function sendVerifEmail(Request $request, $userId) {

        $user = User::find($userId);

        if (!$user) {
            return response()->json(jsonResponse(0, ['msg' => 'The user is not existing.']));
        }
        if ($user) {
            $user->sendEmailVerificationNotification();
        }
        return response()->json(jsonResponse(1));
    }
}
