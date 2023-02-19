<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;


use App\repository\planRep;

use App\repository\typedTextRep;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class admin extends Controller
{
    /**
     * create new notification
     * @Method post
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request  $request): JsonResponse
    {
        if ($request->user() -> tokenCan('data:send')) {
            $request->validate([
                'title' => ['required'] ,
                'package' => ['required'] ,
                'date' => ['required'] ,
                'sbn' => ['required'] ,
                'text' =>['required']
            ]);
            resolve(planRep::class)->create($request);

            return response()->json(['message'=>'done','error' => false],201);
        }
        return response()->json([
        "error" => true,
        "message" => "unauthenticated"
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function read(Request $request): JsonResponse
    {
        if ($request->user() -> tokenCan('data:receive')) {
            $result= null;
            $request->validate([
                'package' => 'required',
            ]);
            if ($request->input('package') == 'TypedText'){
                $results=resolve(typedTextRep::class)->readSomedata($request);
            }else{
                $results=resolve(planRep::class)->read($request);
            }
            $result = response()->json(['package'=>$request->input('package').', title:'. $request->input('title','*'),'message'=>$results,'error'=>false],200);
            return $result;
        }
        return response()->json([
            "error" => true,
            "message" => "unauthenticated"
        ]);

    }

    /**
     * create new notification
     * @Method get
     */
    public function test(): string
    {

       /* return response().json(['message'=>'done','error' => false],200);*/
        return response()->json(["error"=>false , "message"=> "its done"]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function readPerson(Request $request)
    {
        if ($request->user() -> tokenCan('data:receive')) {
            $request->validate([
                'package' => 'required',
            ]);
            if ($request->input('package') == 'TypedText'){
                $results=resolve(typedTextRep::class)->readSomeTitle($request);
            }else{
                $results=resolve(planRep::class)->readPerson($request);
            }
            return response()->json(['package: '=>$request->input('package') ,'message'=>$results,'error'=>false],200);
        }
        return response()->json([
        "error" => true,
        "message" => "unauthenticated"
        ]);
    }
}
