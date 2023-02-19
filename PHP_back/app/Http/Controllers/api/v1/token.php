<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\repository\tokenRep;
use App\repository\typedTextRep;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class token extends Controller
{
    //withdraw, transter, pause, unPause, history, buy, test
    /**
     * create new token
     * @Method post
     * @param Request $request
     * @return JsonResponse
     */
    public function withdraw(Request  $request): JsonResponse
    {
        if ($request->user() -> tokenCan('signed')) {
            $request->validate([
                'amount' => ['required'] ,
            ]);
            $result =resolve(tokenRep::class)->withdraw($request);
            return response()->json($result);
            //return response()->json(['message'=>'done','error' => false]);
        }
        return response()->json([
            "error" => true,
            "message" => "unauthenticated"
        ],400);
    }

    //
    /**
     * test new token
     * @Method get
     * @return JsonResponse
     */
    public function test(): JsonResponse
    {

        return response()->json(['message'=>'done','error' => false],200);
    }

    /**
     * test new token
     * @Method post
     * @param Request $request
     * @return JsonResponse
     */
    public function transfer(Request $request): JsonResponse
    {
        if ($request->user() -> tokenCan('admin')) {
            $request->validate([
                'amount' => ['required'] ,
                'to' => ['required'] ,
            ]);
            $result= resolve(tokenRep::class)->transfer($request);
            return response()->json($result);
            //return response()->json(['message'=>'done','error' => false]);
        }
        return response()->json([
            "error" => true,
            "message" => "unauthenticated"
        ],400);
    }


    /**
     * @param Request $request
     * @Method post
     * @return JsonResponse
     */
    public function pause(Request $request): JsonResponse
    {
        if ($request->user() -> tokenCan('admin')) {
            resolve(tokenRep::class)->pause();
            return response()->json(['message'=>'done','error' => false]);
        }
        return response()->json([
            "error" => true,
            "message" => "unauthenticated"
        ],400);
    }


    /**
     * test new token
     * @Method post
     * @param Request $request
     * @return JsonResponse
     */
    public function unPause(Request  $request): JsonResponse
    {
        if ($request->user() -> tokenCan('admin')) {
            resolve(tokenRep::class)->unPause();
            return response()->json(['message'=>'done','error' => false]);
        }
        return response()->json([
            "error" => true,
            "message" => "unauthenticated"
        ],400);
    }

    /**
     * test new token
     * @Method post
     * @param Request $request
     * @return JsonResponse
     */
    public function history(Request  $request): JsonResponse
    {
        if ($request->user() -> tokenCan('signed')) {
            resolve(tokenRep::class)->history($request);
            return response()->json(['message'=>'done','error' => false]);
        }
        return response()->json([
            "error" => true,
            "message" => "unauthenticated"
        ],400);
    }

    /**
     * test new token
     * @Method post
     * @param Request $request
     * @return JsonResponse
     */
    public function buy(Request  $request): JsonResponse
    {
        if ($request->user() -> tokenCan('signed')) {
            $request->validate([
                'amount' => ['required'] ,
                'address' => ['required'] ,
            ]);
            resolve(tokenRep::class)->buy($request);
            return response()->json(['message'=>'done','error' => false]);
        }
        return response()->json([
            "error" => true,
            "message" => "unauthenticated"
        ],400);
    }
}
