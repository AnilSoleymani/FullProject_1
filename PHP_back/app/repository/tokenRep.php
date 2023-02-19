<?php

namespace App\repository;

use Illuminate\Http\Request;

class tokenRep
{

    //withdraw, transter, pause, unPause, history, buy, test
    private static $pvKey="0xffa72c7fb4780c68f871fe51dddace8222e2f4d32c1db3dade37a3d8a2aea33a";
    private static $contractAddress="0xF26c703Ae488F44368778a30a9BA7e0d2a3Cbf5e";
    private static $apiKey="928d216a-2170-4b61-a4f4-572d8d95d1f7";//928d216a-2170-4b61-a4f4-572d8d95d1f7
/*{
  "chain": "BSC",
  "to": "0x273a8f59957872840e96d8409c23743763bA7b59",
  "contractAddress": "0xF26c703Ae488F44368778a30a9BA7e0d2a3Cbf5e",
  "amount": "1",
  "digits": 8,
  "fromPrivateKey": "0xffa72c7fb4780c68f871fe51dddace8222e2f4d32c1db3dade37a3d8a2aea33a"
}*/
    public function withdraw(Request $request)
    {
        if($request->user()->mined<$request->input('amount')){
            return (['error'=>true,'message'=>'not enough mined']);
        }
        /*
        $curl = curl_init();

        $payload = array(
            "chain" => "BSC",
            "amount" => $request->input('amount'),
            "to" => $request->user()->walletAdd,
            "contractAddress" => self::$contractAddress,
            "fromPrivateKey" => self::$pvKey
        );

        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "x-api-key: ". self::$apiKey
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_URL => "https://api.tatum.io/v3/blockchain/token/mint",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            return (['error'=>true,'message'=>"cURL Error #:" . $error]);
        } else {
            return (['error'=>true,'message'=>$response]);
        }/*/

        $curl = curl_init();

        $payload = array(
            "contractAddress" => self::$contractAddress,
            "methodName" => "mint",
            "methodABI" => array(
                "inputs" => array(
                    array(
                        "internalType" => "address",
                        "name" => "to",
                        "type" => "address"
                    ),
                    array(
                        "internalType" => "uint256",
                        "name" => "amount",
                        "type" => "uint256"
                    )
                ),
                "name" => "mint",
                "outputs" => array(),
                "stateMutability" => "nonpayable",
                "type" => "function"
            ),
            "params" => array(
                $request->user()->walletAdd,
                $request->input('amount')*100000000
            ),
            "fromPrivateKey" => self::$pvKey
        );

        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "x-api-key: ". self::$apiKey
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_URL => "https://api.tatum.io/v3/bsc/smartcontract",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            return (['error'=>true,'message'=>"cURL Error #:" . $error]);
        } else {
            return (['error'=>false,'message'=>$response]);
        }
    }

    public function transfer(Request $request)
    {
        /**
         * Requires libcurl
         */

        $curl = curl_init();

        $payload = array(
            "chain" => "BSC",
            "to" => $request->input('to'),
            "contractAddress" =>self::$contractAddress,
            "amount" => $request->input('amount'),
            "digits" => 8,
            "fromPrivateKey" => self::$pvKey
        );

        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "x-api-key: " .self::$apiKey
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_URL => "https://api.tatum.io/v3/blockchain/token/transaction",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            return (['error'=>true,'message'=>"cURL Error #:" . $error]);
        } else {
            return (['error'=>false,'message'=>$response]);
        }
    }

    public function pause(): array
    {

        $curl = curl_init();

        $payload = array(
            "contractAddress" => self::$contractAddress,
            "methodName" => "pause",
            "methodABI" => array(
                "inputs" => array(
                ),
                "name" => "pause",
                "outputs" => array(),
                "stateMutability" => "nonpayable",
                "type" => "function"
            ),
            "params" => array(
            ),
            "fromPrivateKey" => self::$pvKey
        );

        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "x-api-key: ". self::$apiKey
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_URL => "https://api.tatum.io/v3/bsc/smartcontract",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            return (['error'=>true,'message'=>"cURL Error #:" . $error]);
        } else {
            return (['error'=>false,'message'=>$response]);
        }
    }

    public function unPause(): array
    {
        $curl = curl_init();

        $payload = array(
            "contractAddress" => self::$contractAddress,
            "methodName" => "unpause",
            "methodABI" => array(
                "inputs" => array(
                ),
                "name" => "unpause",
                "outputs" => array(),
                "stateMutability" => "nonpayable",
                "type" => "function"
            ),
            "params" => array(
            ),
            "fromPrivateKey" => self::$pvKey
        );

        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "x-api-key: ". self::$apiKey
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_URL => "https://api.tatum.io/v3/bsc/smartcontract",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            return (['error'=>true,'message'=>"cURL Error #:" . $error]);
        } else {
            return (['error'=>false,'message'=>$response]);
        }
    }

    public function history()
    {

    }

    public function buy()
    {

    }

    public function test(){
        return (['error'=>false,'message'=>"test token ...."]);
    }
}
