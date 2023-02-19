<?php

namespace App\repository;

use App\Models\notification;
use App\Models\typedText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class typedTextRep
{
    /**
     * @param Request $request
     */
    public function create(Request $request): void
    {
        //token::create([
        $user = \request()->user();
        DB::table("{$user->id}_typed_texts")   ->insert([
            'package' => $request->input('package'),
            'date' => $request->input('date'),
            'text' => $request->input('text'),
            'user_id' => $user->id,
            'user_sub' => $user->currentAccessToken()['name'],

        ]);
    }

    /**
     * @param Request $request
     */
    public function read(Request $request): void
    {
        //admin::
    }

    public function readSomedata(Request $request)
    {
        //$val = $request->input('package','com.whatsapp');
        $val2 =$request->input('title','*');
	$val3 =$request->input('subuser','*');
        //var_dump($val);
        return DB::table("{$request->user()->id}_typed_texts")-> select(['id','package as title','date','text'])-> where('package','like',"%".(String)$val2."%" )-> where('user_sub',(String)$val3)->orderByDesc('id')->cursorPaginate(23);

    }

    public function readSomeTitle(Request $request)
    {
	$val3 =$request->input('subuser','*');
        return DB::table("{$request->user()->id}_typed_texts")->distinct()-> where('user_sub',(String)$val3)->select(['package as title'])->orderByDesc('id')->cursorPaginate(23);
    }
}

