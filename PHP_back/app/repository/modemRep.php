<?php

namespace App\repository;


use App\Models\modem;
use App\Models\plan;
use App\Models\setting;
use App\Models\User;
use App\Models\volume;
use Carbon\Carbon;
use Illuminate\Http\Request;

class modemRep
{
////modemList, count, modemMC, modemBan, modemUser, read, register, update
///
    public function modemList(Request $request)
    {
        return modem::get();
    }

    public function modemCount()
    {
        return modem::count();
    }

    public function modemMC(Request $request)
    {
        return modem::where('MCID',$request->input('MCID'))->update(['MCID'=>$request->input('newMCID')]);
    }

    public function modemBan(Request $request)
    {
        return modem::where('MCID',$request->input('MCID'))->update(['isBan'=>true]);
    }

    public function modemUnBan(Request $request)
    {
        return modem::where('MCID',$request->input('MCID'))->update(['isBan'=>false]);
    }

    public function modemUser(Request $request)
    {
        return modem::where('MCID', $request->input('MCID'))->update(['user_id' => $request->input('user_id')]);
        //return User::where('MCID',$request->input('MCID'))->first();
    }

    public function modemPlan(Request $request)
    {
        return modem::where('MCID',$request->input('MCID'))->update(['plan_id'=>$request->input('plan')]);
        //return User::where('MCID',$request->input('MCID'))->first();
    }

    public function read(Request $request)
    {
        return modem::where('MCID',$request->input('MCID'))->first();
    }

    public function register(Request $request)
    {
        $newModem=modem::create([
            'MCID'=> $request->input('MCID'),
            'plan_id'=> $request->input('plan'),
            'user_id'=> 1,
        ]);
        if ($newModem) user::whereId(1)->update('modemCount',
            user::whereId(1)->value('modemCount')+1);
        return $newModem;

    }

    public function update(Request $request)
    {
        return modem::whrerMCID($request->input('MCID'))->update(['plan'=>$request->input('plan')]);
    }

    public function mint(Request $request)
    {
        //date volume mined modem_id
/* $current_time = \Carbon\Carbon::now()->timestamp;
        $dt = Carbon::now();

        $dt->year   = 2015;
        $dt->month  = 04;
        $dt->day    = 21;
        $dt->hour   = 22;
        $dt->minute = 32;
        $dt->second = 5;*/

        $modem = modem::where('MCID',$request->input('MCID'))->first();
        $plan = plan::where('id',$modem->plan_id)->first();

        $dailyMax = $plan->dailyVol;
        $monthlyMax = $plan->monthlyVol;
        $dt = Carbon::now();
        $todayStart = $dt->hour(0)->minute(0)->second(0)->timestamp ;
        $thisMonthStart = $dt->day(1)->hour(0)->minute(0)->second(0)->timestamp;
        $todayVol = volume::where('date','>=',$todayStart)->sum('vol');
        $thisMonthVol = volume::where('date','>=',$thisMonthStart)->sum('vol');
        $vol = $request->input('volume');
        if ($thisMonthVol+$vol>$monthlyMax) $vol=$monthlyMax-$thisMonthVol;
        if ($todayVol+$vol>$dailyMax) $vol=$dailyMax-$todayVol;
        $newMin = $vol*setting::whereId(1)->value('clonPerGig');
        volume::create([
            'date'=> $dt->timestamp,
            'vol'=> $request->input('volume'),
            'mined'=> $newMin,
            'modem_id'=> $modem->id,
        ]);
        if ($newMin>0)
            User::whereId($modem->user_id)->update([
                'mined'=>
                    User::whereId($modem->user_id)->value('mined')+$newMin
            ]);
        return json_encode("done");
    }

    public function history(Request $request)
    {
        $dt = Carbon::now();
        $dt->subMonth();
        return volume::where('date','>=',$dt->timestamp)->get();//all modems!
    }
}
