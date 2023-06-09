<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\CoreApi;

class MidtransController extends Controller
{
    //
    const PAYMENT_TYPE = array(
        "midtrans_bca" => "bca", 
        "midtrans_permata" => "permata", 
        "midtrans_mandiri" => "mandiri", 
        "midtrans_bni" => "bni",
        "midtrans_gopay" => "gopay"
    ); 

    public function chargeVa(Request $request)
    {
        // Set Midtrans configuration
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $payload = [
            'bank' => self::PAYMENT_TYPE[$request->payment_type]
        ];
        $request['payment_type'] = 'bank_transfer';
        $request['bank_transfer'] = $payload;
        



        // Generate Snap Token
        //$snapToken = Snap::getSnapToken($request->all());
        $charge = CoreApi::charge($request->all());

        // Return Snap Token
        return response()->json([
            $charge,
        ]);
    }

    public function chargeGopay(Request $request)
    {
        // Set Midtrans configuration
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $request['payment_type'] = self::PAYMENT_TYPE[$request->payment_type];


        // Generate Snap Token
        //$snapToken = Snap::getSnapToken($request->all());
        $charge = CoreApi::charge($request->all());

        // Return Snap Token
        return response()->json([
            $charge,
        ]);
    }
    
}
