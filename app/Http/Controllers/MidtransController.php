<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\CoreApi;

class MidtransController extends Controller
{
    //
    public function snapToken(Request $request)
    {
        // Set Midtrans configuration
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;



        // Generate Snap Token
        //$snapToken = Snap::getSnapToken($request->all());
        $charge = CoreApi::charge($request->all());

        // Return Snap Token
        return response()->json([
            $charge,
        ]);
    }
    
}
