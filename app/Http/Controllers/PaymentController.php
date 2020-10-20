<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use Str;
use GuzzleHttp\Client;

class PaymentController extends Controller
{
    //
    public function store(Request $request){
         $order = Order::create([
            'amount' => $request->amount,
            'method' => $request->method
         ]);

         $order->order_id = $order->id . '-' . Str::random(5);

         $order->save();

         $response_midtrans = $this->midtrans_store($order);

         return response()->json([
            'response_code' => '00',
            'response_msg' => 'success',
            'data' => $response_midtrans
         ]);
    }

    protected function midtrans_store(Order $order){
        $server_key = base64_encode(config('app.midtrans.server_key'));
        $base_uri = config('app.midtrans.base_uri');
        $client = new Client([
            'base_uri' => $base_uri
        ]);

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $server_key,
            'Content-Type' => 'application/json'
        ];

        switch($order->method){
            case 'bca' :
                $body = [
                    "payment_type" => "bank_transfer",
                    "transaction_details" => [
                        "order_id" => $order->order_id,
                        "gross_amount" => $order->amount
                    ],
                    "bank_transfer" => [
                        "bank" => "bca"
                    ]
                ];
                break;
            case 'permata' :
                $body = [
                    "payment_type" => "permata",
                    "transaction_details" => [
                        "order_id" => $order->order_id,
                        "gross_amount" => $order->amount
                    ]
                ];
            break;

            default: $body = []; break;
        }

        $res = $client->post('/v2/charge',[
            'headers' => $headers,
            'body' => json_encode($body)
        ]);

        return json_decode($res->getBody());
    }
}
