<?php

namespace App\Http\Controllers;

use App\Profiles;
use App\Socials;
use App\SellingProps;

use View;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController  extends Controller
{


    //
    public function __construct() {
       parent::__construct();

       View::share ( '_currentPage', 'profile');
    }

    public function view() {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }
        
        if ($user->type == 0) {
            return $this->sellerView();
        } else if ($user->type == 1) {
            return $this->buyerView();
        } else if ($user->type == 2) {
            // admin
        } else {
            // oops. what the hell?
        }
    }

    public function edit() {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }
        
        if ($user->type == 0) {
            return $this->sellerEdit();
        } else if ($user->type == 1) {
            return $this->buyerEdit();
        } else if ($user->type == 2) {
            // admin
        } else {
            // oops. what the hell?
        }
    }

    public function save(Request $request) {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }
        
        if ($user->type == 0) {
            return $this->sellerSave($request);
        } else if ($user->type == 1) {
            return $this->buyerSave($request);
        } else if ($user->type == 2) {
            // admin
        } else {
            // oops. what the hell?
        }
    }

    // 
    /*
     *
     *** Seller Related ***
     *
     */
    private function refineSellingProp($selling) {

        if (!$selling) return $selling;

        //

        return $selling;
    }

    private function getSellingPropByUser($user) {

        if ($user->sellings->isEmpty()) {
            $selling = new SellingProps(['user_id' => $user->id]);
        } else {
            $selling = $user->sellings->first();
        }

        $selling = $this->refineSellingProp($selling);
        return $selling;
    }

    private function getSellingPropById($id) {
        $selling = SellingProps::find($id);
        $selling = $this->refineSellingProp($selling);
        return $selling;
    }

    private function sellerView()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        $selling = $this->getSellingPropByUser($user);

        return view('biz.seller.profile', [
            'selling' => $selling,
            'show_contact' => ($user->type == 0) ? false : true,
        ]);
    }

    private function sellerEdit()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        $selling = $this->getSellingPropByUser($user);

        return view('biz.seller.edit', [
            'selling' => $selling,
        ]);
    }

    private function sellerSave(Request $request)
    {
        $resp = array(
            'status' => 1,
            'name' => '',
            'path' => '',
            'url' => '',
            'msg' => '',
        );

        $user = Auth::user();
        $userId = $user->id;

        if (!$user) {
            $resp['status'] = 0;
            $resp['redirect'] = route('login');
            return response()->json($resp);   
        }

        $data = $request->all();

        $selling = SellingProps::firstOrCreate(
            ['user_id' => $userId]
        );
        
        $this->sellerFilling($selling, $data);

        $selling->save();

        return response()->json($resp);
    }

    private function sellerFilling($selling, $data)
    {
        
        $sellingId = $selling->id;

        $fields = explode(',', 'name,description,metrics,revenue,date_founded,customers_cnt,price,reason,growth,highlights,fi_info,team,support');
        foreach ($fields as $f) {
            $selling->{$f} = $data[$f];
        }

        
        $sellingId = $selling->id;

        /*
         * Copy public image files into selling product specific path
         *
         */
        $ind = 0;
        $demos = array();
        foreach ($data['profilePic'] as $pic) {

            $pathBit = 'sellings/' . getSubPath($sellingId) . '/demos/';
            if (strpos($pic, 'tmp/') === 0) {
                if (!Storage::disk('public')->exists($pic)) continue;

                $ext = pathinfo($pic, PATHINFO_EXTENSION);
                $newFileName = str_pad($ind, 3, "0", STR_PAD_LEFT) . '.' . $ext;
                $copyDone = Storage::put($pathBit . $newFileName, Storage::disk('public')->get($pic));
                Storage::disk('public')->delete($pic);

                if ($copyDone) {
                    $demos[] = $newFileName;
                    $ind ++;
                }
            } else {
                if (!Storage::exists($pathBit . $pic)) continue;
                $demos[] = $pic;
                $ind ++;
            }
        }
        $selling->images = json_encode($demos);
        $selling->images_cnt = $ind;

        /*
         * Copy public pdf files into selling product specific path
         *
         */
        $ind = 0;
        $pdfs = array();
        if (isset($data['pdfs']) && is_array($data['pdfs'])) {
            for ($i=0; $i<count($data['pdfs']); $i++) {
                
                $pdf = $data['pdfs'][$i];
                $pathBit = 'sellings/' . getSubPath($sellingId) . '/pdfs/';

                if (strpos($pdf, 'tmp/') === 0) {
                    if (!Storage::disk('public')->exists($pdf)) continue;

                    $ext = pathinfo($pdf, PATHINFO_EXTENSION);
                    $newFileName = str_pad($ind, 3, "0", STR_PAD_LEFT) . '.' . $ext;
                    $copyDone = Storage::put($pathBit . $newFileName, Storage::disk('public')->get($pdf));
                    Storage::disk('public')->delete($pdf);
                    if ($copyDone) {
                        $pdfs[] = [
                            'name' => $data['names'][$i],
                            'path' => $newFileName,
                        ];
                        $ind ++;
                    }    
                } else {
                    // already moved to specific path
                    if (!Storage::exists($pathBit . $pdf)) continue;
                    $pdfs[] = [
                        'name' => $data['names'][$i],
                        'path' => $pdf,
                    ];
                    $ind ++;
                }
                
            }
        }
        $selling->files = json_encode($pdfs);
        $selling->files_cnt = $ind;

        $selling->status = (($data['submit_type'] == '0') ? 0 : 2);
        $selling->others = '';

        return $selling;
    }

    public function sellers() {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }
        
        //
        return view('biz.seller.list', [
            '_currentPage' => 'sellers',
        ]);
    }

    public function sellersSearch(Request $request) {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'redirect' => route('login'),
            ]);
        }
        
        //
        $data = $request->all();

        $rev0 = getArrayVar($data, 'r0', '');
        $rev1 = getArrayVar($data, 'r1', '');
        $cost0 = getArrayVar($data, 'c0', '');
        $cost1 = getArrayVar($data, 'c1', '');
        $isFeatured = getArrayVar($data, 'f', 0); // ???
        $q = getArrayVar($data, 'q', '');

        $sellers = SellingProps::where('name', 'LIKE', '%' . $q . '%');
        if ($rev0 != '') {
            $sellers = $sellers->where('revenue', '>=', $rev0);
        }
        if ($rev1 != '') {
            $sellers = $sellers->where('revenue', '<=', $rev1);
        }
        if ($cost0 != '') {
            $sellers = $sellers->where('price', '>=', $cost0);
        }
        if ($cost1 != '') {
            $sellers = $sellers->where('price', '<=', $cost1);
        }
        $sellers = $sellers->paginate(9);


        return response()->json($sellers);
    }

    public function sellerViewById(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        $selling = $this->getSellingPropById($id);

        return view('biz.seller.profile', [
            'selling' => $selling,
            'show_contact' => true,
            '_currentPage' => '',
        ]);
    }

    //
    /*
     *
     *** Buyer Related ***
     *
     */
    private function getBuyerPropByUser($user) {

        if (is_null($user->profile)) {
            $profile = new Profiles(['user_id' => $user->id]);
        } else {
            $profile = $user->profile;
        }

        return $profile;
    }

    private function buyerView()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        $buyer = $this->getBuyerPropByUser($user);

        return view('biz.buyer.profile', [
            'buyer' => $buyer
        ]);
    }

    private function buyerEdit()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        $buyer = $this->getBuyerPropByUser($user);

        return view('biz.buyer.edit', [
            'buyer' => $buyer,
        ]);
    }

    private function buyerSave(Request $request)
    {
        $resp = array(
            'status' => 1,
            'name' => '',
            'path' => '',
            'url' => '',
            'msg' => '',
        );

        $user = Auth::user();
        $userId = $user->id;

        if (!$user) {
            $resp['status'] = 0;
            $resp['redirect'] = route('login');
            return response()->json($resp);   
        }

        $data = $request->all();

        $profile = Profiles::firstOrCreate(
            ['user_id' => $userId]
        );
        
        $profile->company_name = $data['name'];
        $profile->company_description = $data['description'];
        $profile->interests = $data['interests'];
        $profile->status = (($data['submit_type'] == '0') ? 0 : 2);

        $profile->save();

        $profile->allSocials()->update(['status' => 0]); // disable all socials first
        $seq = count($data['social_names']);
        for ($i=0; $i<count($data['social_names']); $i++) {
            $stype = $data['social_names'][$i];
            $url = $data['social_urls'][$i];
            if ($stype && $url) {
                // 
                $item = Socials::firstOrNew([
                    'ref_id' => $profile->user_id, 
                    'ref_type' => 0, 
                    'social_type' => $stype,
                ]);
                $item->social_url = $url;
                $item->status = 1;
                $item->seq = $seq;
                $item->save();
                $seq --;
            }
        }

        return response()->json($resp);
    }

    public function buyers() {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }
        
        //
    }

    public function buyersSearch() {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'redirect' => route('login'),
            ]);
        }
        
        //
        return response()->json($resp);
    }

}
