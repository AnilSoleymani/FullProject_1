<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\repository\planRep;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class plan extends Controller
{
    ////'name', 'dailyVol', 'monthlyVol'
    /// list, update, register, count

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $results=resolve(planRep::class)->list();
        if ($results  )
        {
            return response()->json(["error"=> false, "plans"=> $results,
                "message"=> "done"],202);
        }else{
            return response()->json(["error"=> true, "plans"=> $results, "message"=> "error 1602"],203);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate(
            [
                'name' => ['required','unique:plans'],
                'dailyVol' => ['required'],
                'monthlyVol' => 'required',
                'id' => 'required',
            ]);
        $thisUser = $request->user() ;
        if ($thisUser->isBan) return response()->json([
            "error" => true,
            "message" => "banned user. "
        ],403);
        if ($thisUser -> tokenCan('admin')) {
            $results=resolve(planRep::class)->update($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "plan"=> $results,
                    "message"=> "done"],202);
            }else{
                return response()->json(["error"=> true, "plan"=> $results, "message"=> "unregistered plan"],203);
            }
        }else{
            return response()->json([
                "error" => true,
                "message" => "unauthenticated. "
            ],400);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate(
            [
                'name' => ['required','unique:plans'],
                'dailyVol' => ['required'],
                'monthlyVol' => 'required',
            ]);
        $thisUser = $request->user() ;
        if ($thisUser->isBan) return response()->json([
            "error" => true,
            "message" => "banned user. "
        ],403);
        if ($thisUser -> tokenCan('admin')) {
            $results=resolve(planRep::class)->register($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "plan"=> $results,
                    "message"=> "done"],202);
            }else{
                return response()->json(["error"=> true, "plan"=> $results, "message"=> "Can't create this plan"],203);
            }
        }else{
            return response()->json([
                "error" => true,
                "message" => "unauthenticated. "
            ],400);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function count(Request $request): JsonResponse
    {
        $results=resolve(planRep::class)->count();
        if ($results  )
        {
            return response()->json(["error"=> false, "count"=> $results,
                "message"=> "done"],202);
        }else{
            return response()->json(["error"=> true, "count"=> $results, "message"=> "error 1601"],203);
        }
    }
}
