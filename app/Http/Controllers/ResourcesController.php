<?php

namespace App\Http\Controllers;

use Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourcesController extends Controller
{
    //
    public function uploadToTemp(Request $request) {

        $data = array(
            'status' => 1,
            'name' => '',
            'path' => '',
            'url' => '',
            'msg' => '',
        );

        $type = $request->type;
        $file = $request->file('tmpfile');

        // image file upload
        if ($file) {
            $isValid = false;
            if ($type == '0') {
                // image file
                $validator = Validator::make($request->all(), [
                    'tmpfile' => 'required|mimes:jpeg,png,bmp,gif,svg,webb,tiff',
                ]);
                if (!$validator->fails()) $isValid = true;
            } elseif ($type == '1') {
                // pdf file
                $validator = Validator::make($request->all(), [
                    'tmpfile' => 'required|mimes:pdf',
                ]);
                if (!$validator->fails()) $isValid = true;
            }

            if ($isValid) {
                $path = $file->store('tmp',  ['disk' => 'public']);

                $data['name'] = $file->getClientOriginalName();
                $data['path'] = $path;
                $data['url'] = Storage::disk('public')->url($path);    
            } else {
                $data['status'] = 0;
                $data['msg'] = 'The file is not in accepted format.';
            }
        } else {
            $data['status'] = 0;
            $data['msg'] = 'The file is not given.';
        }

        return response()->json($data);
    }

    public function getSellerSpecific(Request $request, $id, $sub_path) {
        // any other url, subfolders also
        $path = 'sellings/' . getSubPath($id) . '/' . $sub_path;
        if (!Storage::exists($path)) {
            // show 404
        } else {
            return response()->file(Storage::path($path));
            // return Storage::download($path);
        }
    }
}
