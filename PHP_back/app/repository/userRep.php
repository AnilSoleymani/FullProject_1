<?php

namespace App\repository;

use App\Models\modem;
use App\Models\User;
use App\Models\volume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class userRep
{
    //register, login, read, editEmail, editPass, editAddress, modem, token, test, userCount, userList, userBan,

    /**
     * @param Request $request
     * @return mixed
     * @throws ValidationException
     */
    public function create(Request $request)
    {
        if(!modem::where('MCID',$request->input('MCID'))->where('user_id','1')->exists()){
            throw ValidationException::withMessages([
                'MCID' => ['The provided credentials are incorrect.'],
            ]);
        }
        $user =User::create([
            'MCID' => $request->input('MCID'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        modem::where('MCID',$request->input('MCID'))->update(['user_id'=>$user->id]);

        user::whereId(1)->update(['modemCount'=>
            user::whereId(1)->value('modemCount')-1]);
        return $user;

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function read(String $email)
    {
        //admin::
        return User::where('email',$email)->get()->first();
    }

    /**
     * @param String $email
     * @param String $newEmail
     * @return mixed
     */
    public function updateEmail(String $email,String $newEmail)
    {
        return User::where('email',$email)
            ->update(['email'=>$newEmail]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function updatePass(Request $request)
    {
        return User::where('email',$request->user()->email)
                ->where('password',$request->input('password'))
                ->update(['password'=>$request->input('newPassword')]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function updateAddress(String $email,String $address)
    {
        return User::where('email',$email)
            ->update(['walletAdd'=>$address]);
    }

    /**
     * @param String $email
     * @return mixed
     */
    public function modemCount(String $email)
    {
        return User::where('email',$email)->value('modemCount');
    }

    /**
     * @param String $email
     * @return void
     */
    public function token(String $email)
    {
        $user = User::where('email',$email)->get();
        $modems = modem::where('user_id',$user->id)->get();
        foreach ($modems as $modem){
            return volume::where('modem_id',$modem->MCID)->cursorPaginate(23);;//addto array!
        }

    }

    /**
     * @return mixed
     */
    public function userCount()
    {
        return User::count();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function userList(Request $request)
    {
        return User::get();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function userBan(Request $request)
    {
        return User::where('email',$request->input('email'))->update(['isBan'=>true]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function userUnBan(Request $request)
    {
        return User::where('email',$request->input('email'))->update(['isBan'=>false]);
    }

    public function isBan(User $thisUser)
    {
        return $thisUser->isBan;
    }

}

