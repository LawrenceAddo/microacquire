<?php

namespace App\Http\Controllers;

use App\SellingProps;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController  extends Controller
{
    //
    private function getSellingPropByUser($user) {

        if ($user->sellings->isEmpty()) {
            $selling = new SellingProps(['user_id' => $user->id]);
        } else {
            $selling = $user->sellings->first();
        }


        $imgs_sending = array();
        $imgs_original = json_decode($selling->images, true);
        if ($imgs_original && is_array($imgs_original)) {

            foreach ($imgs_original as $img) {
                $imgs_sending[] = [
                    'path' => $img,
                    'url' => getResourceUrl($selling->id, 'selling', ($img ? 'demos/' . $img : null)),
                ];
            }
        }
        $selling->images = $imgs_sending;

        $pdf_sending = array();
        $pdf_original = json_decode($selling->files, true);
        if ($pdf_original && is_array($pdf_original)) {

            foreach ($pdf_original as $pdf) {
                $pdf_sending[] = [
                    'name' => $pdf['name'],
                    'path' => $pdf['path'],
                    'url' => getResourceUrl($selling->id, 'selling', 'pdfs/' . ($pdf['path'] ? $pdf['path'] : null)),
                ];
            }
        }
        $selling->files = $pdf_sending;

        return $selling;
    }

    public function sellerView()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        $selling = $this->getSellingPropByUser($user);

        return view('biz.seller.profile', [
            'page_class' => 'seller-profile-view',
            'selling' => $selling,
        ]);
    }

    public function sellerEdit()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect(route('login'));
        }

        $selling = $this->getSellingPropByUser($user);

        return view('biz.seller.edit', [
            'page_class' => 'seller-profile-edit',
            'selling' => $selling,
        ]);
    }

    public function sellerSave(Request $request)
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

        $selling->save();

        return response()->json($resp);
    }
}
