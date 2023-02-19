<?php

namespace App\repository;

use App\Models\notification;
use App\Models\plan;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class planRep
{
    ////'name', 'dailyVol', 'monthlyVol'
    /// list, update, register, count
    public function list()
    {
        return plan::get();
    }

    public function update(Request $request)
    {
        return Plan::whereId($request->input('id'))->update(['name'=> $request->input('name'),
                                                                 'dailyVol'=> $request->input('dailyVol'),
                                                                  'monthlyVol'=> $request->input('monthlyVol'),]);
    }

    public function register(Request $request)
    {
        return plan::create(['name'=> $request->input('name'),
            'dailyVol'=> $request->input('dailyVol'),
            'monthlyVol'=> $request->input('monthlyVol'),]);
    }

    public function count()
    {
        return plan::count();
    }
}

