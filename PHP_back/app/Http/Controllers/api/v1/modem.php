<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\repository\modemRep;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class modem extends Controller
{
    //modemList, userList, modemMC, modemBan, modemUser, read, register, update
    /**
     * @param Request $request
     * @return JsonResponse
     * @method get
     * @throws ValidationException
     */
    public function modemList(Request $request): JsonResponse
    {
        if ($request->user() -> tokenCan('admin')) {
            $results=resolve(modemRep::class)->modemList($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "modem"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "no modems"],203);
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
     * @method get
     * @throws ValidationException
     */
    public function modemCount(Request $request): JsonResponse
    {
        $results=resolve(modemRep::class)->modemCount($request);
        if ($results  )
        {
            return response()->json(["error"=> false, "count"=> $results,
                "message"=> "done"]);
        }else{
            return response()->json(["error"=> true, "message"=> "no modems"],203);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @method get
     * @throws ValidationException
     */
    public function modemMC(Request $request): JsonResponse
    {
        if ($request->user() -> tokenCan('admin')) {
            $request->validate(
                [
                    'MCID' => ['required'],
                    'newMCID' => ['required'],
                ]);
            $results=resolve(modemRep::class)->modemMC($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "modem"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "not registered"],203);
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
     * @method get
     * @throws ValidationException
     */
    public function modemBan(Request $request): JsonResponse
    {
        if ($request->user() -> tokenCan('admin')) {
            $request->validate(
                [
                    'MCID' => ['required'],
                ]);
            $results=resolve(modemRep::class)->ban($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "modem"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "not registered"],203);
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
     * @method get
     * @throws ValidationException
     */
    public function modemUNBan(Request $request): JsonResponse
    {
        if ($request->user() -> tokenCan('admin')) {
            $request->validate(
                [
                    'MCID' => ['required'],
                ]);
            $results=resolve(modemRep::class)->unBan($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "modem"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "not registered"],203);
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
     * @method get
     * @throws ValidationException
     */
    public function modemUser(Request $request): JsonResponse
    {
        if ($request->user() -> tokenCan('signed')) {
            $request->validate(
                [
                    'MCID' => ['required'],
                    'user_id' => ['required'],
                ]);
            if (! User::where('id',$request->input('user_id'))->first()) {
                return response()->json(["error"=> true, "message"=> "no user"],203);
            }
            $results=resolve(modemRep::class)->modemUser($request); //if belongs to admin!
            if ($results  )
            {
                return response()->json(["error"=> false, "modem"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "not registered"],203);
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
     * @method get
     * @throws ValidationException
     */
    public function modemPlan(Request $request): JsonResponse
    {
        if ($request->user() -> tokenCan('signed')) {
            $request->validate(
                [
                    'MCID' => ['required'],
                    'plan' => ['required'],
                ]);
            if (! \App\Models\plan::where('id',$request->input('plan'))->first()) {
                return response()->json(["error"=> true, "message"=> "no plan"],203);
            }
            $results=resolve(modemRep::class)->modemPlan($request); //if belongs to admin!
            if ($results  )
            {
                return response()->json(["error"=> false, "modem"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "not registered"],203);
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
     * @method get
     * @throws ValidationException
     */
    public function read(Request $request): JsonResponse
    {
        if ($request->user() -> tokenCan('signed')) {
            $request->validate(
                [
                    'MCID' => ['required'],
                ]);
            $results=resolve(modemRep::class)->read($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "modem"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "not registered"],203);
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
     * @method get
     * @throws ValidationException
     */
    public function register(Request $request): JsonResponse
    {
        if ($request->user() -> tokenCan('admin')) {
            $request->validate(
                [
                    'MCID' => ['required','unique:modems'],
                    'plan' => ['required'],
                ]);
            if (! \App\Models\plan::where('id',$request->input('plan'))->first()) {
                return response()->json(["error"=> true, "message"=> "no plan"],203);
            }
            $results=resolve(modemRep::class)->register($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "modem"=> $results,
                    "message"=> "registered successfully"]);
            }else{
                return response()->json(["error"=> true, "message"=> "cant register"],203);
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
     * @method get
     * @throws ValidationException
     */
    public function update(Request $request): JsonResponse
    {
        if ($request->user() -> tokenCan('admin')) {
            $request->validate(
                [
                    'MCID' => ['required'],
                    'plan' => ['required'],
                ]);
            $results=resolve(modemRep::class)->update($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "modem"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "cant update"],203);
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
     * @method post
     * @throws ValidationException
     */
    public function mint(Request $request)
    {
        //if ($request->user() -> tokenCan('modem')) {
            $request->validate(
                [
                    'MCID' => ['required'],
                    'volume' => ['required'],
                ]);
            $results=resolve(modemRep::class)->mint($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "modem"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "cant update"],203);
            }
        /*}else{
            return response()->json([
                "error" => true,
                "message" => "unauthenticated. "
            ],400);
        }*/
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @method get
     * @throws ValidationException
     */
    public function history(Request $request): JsonResponse
    {
        if ($request->user() -> tokenCan('admin')) {
            $request->validate(
                [
                    'MCID' => ['required'],
                ]);
            $results=resolve(modemRep::class)->history($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "modem"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "cant update"],203);
            }
        }else{
            return response()->json([
                "error" => true,
                "message" => "unauthenticated. "
            ],400);
        }
    }

}
