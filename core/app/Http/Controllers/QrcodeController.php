<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use Storage;

class QrcodeController extends Controller
{
    public function generateQrCode(Request $request){
    	/* Generate Qr-Code */
        $url = route('admin.shopkeeper.show','3');
        
        $image = \QrCode::format('png')->merge(asset('assets/img/ss.png'), 0.3, true)
                            ->size(500)->errorCorrection('H')
                            ->generate('1');
        // $images = response($image)->header('Content-type','image/png');
        $output_file = '/assets/qrcode/' . uniqid() . '.png';
        // Storage::disk('local')->put($output_file, $image);

        $filename = uniqid() . '.jpg';
        $location = 'assets/qrcode/' . $filename;

        $background = Image::canvas(570, 570);
        // insert resized image centered into background
        $background->insert($image, 'center');
        // save or do whatever you like
        $background->save($location);

        /* Generate Qr-Code */
    }
}
