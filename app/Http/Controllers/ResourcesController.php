<?php

namespace App\Http\Controllers;

use Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

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

    public function qrcode(Request $request) {
        $data = $request->all();

        $fore_color = getArrayVar($data, 'c', '#000000');
        $bg_color = getArrayVar($data, 'b', '#ffffff');
        $text = getArrayVar($data, 'd', '');
        $size = getArrayVar($data, 's', 300);
        $padding = getArrayVar($data, 'p', 0);

        $fore_color = hex2rgb($fore_color, 1, 1);
        $bg_color = hex2rgb($bg_color, 1, 1);

        $qrCode = new QrCode();
        $qrCode->setText($text);
        $qrCode->setSize($size);
        $qrCode->setMargin($padding);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
        $qrCode->setForegroundColor($fore_color);
        $qrCode->setBackgroundColor($bg_color); // array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0)
        /*
        $qrCode->setLabel('Scan the code', 16, null, LabelAlignment::CENTER);
        $qrCode->setLogoPath(__DIR__.'/../assets/images/symfony.png');
        $qrCode->setLogoSize(150, 200);
        $qrCode->setRoundBlockSize(true);
        */
        $qrCode->setValidateResult(false);
        $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);

        // Directly output the QR code
        return response($qrCode->writeString())
            ->header('Content-Type', $qrCode->getContentType());
        
        /*
        // now we can directly output the qrcode
        header('Content-Type: '.$qrCode->getContentType());
        $qrCode->render();
        

        // or create a response object
        $response = new Response($qrCode->get(), 200, array('Content-Type' => $qrCode->getContentType()));
        */
    }


    function emptyAvatar(Request $request) {
        $data = $request->all();

        $ind = getArrayVar($data, 'i', 0);
        $full_name = getArrayVar($data, 's', 'M A');

        // $full_name = urldecode($full_name);

        $names = explode(' ', $full_name);
        $n0 = trim(mb_substr($names[0], 0, 1));
        $n1 = trim(isset($names[1]) ? mb_substr($names[1], 0, 1) : '');

        $etag = md5($n0 . $n1);
        $etagHeader=(isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);
        
        $svg = getEmptyAvatarSvg($ind, $full_name);

        return response($svg)
            ->withHeaders([
                'ETag' => $etag,
                'Cache-Control' => 'max-age=86400',
                'Content-Type' => 'image/svg+xml',
            ]);
    }
}
