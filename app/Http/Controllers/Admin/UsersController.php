<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\Profiles;
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
        $data = $request->all();

        $email = getArrayVar($data, 'email', '');
        $t = getArrayVar($data, 't', '');
        $s = getArrayVar($data, 's', '');
        $es = getArrayVar($data, 'es', '');
        $isFeatured = getArrayVar($data, 'f', 0); // ???
        $q = getArrayVar($data, 'q', '');

        $users = User::with('profile');

        if ($q != '') {
            $users = $users->where(function($query) use ($q) {
                return $query->where('name', 'LIKE', '%' . $q . '%')
                    ->orwhere('email', 'LIKE', '%' . $q . '%')
                    ->orWhereHas('Profile', function($query1) use ($q) {
                        return $query1->where('company_name', 'LIKE', '%' . $q . '%')
                            ->orWhere('company_description', 'LIKE', '%' . $q . '%')
                            ->orWhere('interests', 'LIKE', '%' . $q . '%')
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

            if (!$user->profile) {
                $user->profile = new Profiles(['user_id' => $user->id]);
            }

            /*
            $profile = Profiles::firstOrNew(
                ['user_id' => $user->id]
            );*/

            $data = $request->all();
            foreach ($data as $key => $value) {
                $user->profile->{$key} = $value;
            }
            $user->profile->save();
        }
        return response()->json(jsonResponse(1));
    }
}
