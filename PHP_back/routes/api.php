<?php

use App\Http\Controllers\api\v1\authcontroller;
use App\Http\Controllers\api\v1\modem;
use App\Http\Controllers\api\v1\token;
use App\Http\Controllers\api\v1\plan;
use App\Models\subuser;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/


//admin //remove it!
Route::prefix('admin')->group(function () {
    Route::post('/email', [
        admin::class,
        'editEmail'
    ])->middleware('auth:sanctum');

    Route::post('/password', [
        admin::class,
        'editPass'
    ])->middleware('auth:sanctum');

});
//user
Route::prefix('user')->group(function () {
    Route::post('/register', [
        authcontroller::class,
        'register'
    ]);

    Route::post('/login', [
        authcontroller::class,
        'login'
    ]);

    Route::post('/read', [
        authcontroller::class,
        'read'
    ])->middleware('auth:sanctum','verified');

    Route::post('/email', [
        authcontroller::class,
        'editEmail'
    ])->middleware('auth:sanctum');

    Route::post('/pass', [
        authcontroller::class,
        'editPass'
    ])->middleware('auth:sanctum','verified');

    Route::post('/address', [
        authcontroller::class,
        'editAddress'
    ])->middleware('auth:sanctum','verified');

    Route::get('/modem', [
        authcontroller::class,
        'modem'
    ])->middleware('auth:sanctum','verified');//just modem count

    Route::get('/token', [
        authcontroller::class,
        'token'
    ])->middleware('auth:sanctum','verified');//history mined/withdrawed/total

    Route::get('/test', [
        authcontroller::class,
        'test'
    ])->name('notification.test');

    Route::get('/list', [
        authcontroller::class,
        'userList'
    ])->middleware('auth:sanctum');

    Route::get('/count', [
        authcontroller::class,
        'userCount'
    ]);

    Route::post('/ban', [
        authcontroller::class,
        'userBan'
    ])->middleware('auth:sanctum');

    Route::post('/unban', [
        authcontroller::class,
        'userUnBan'
    ])->middleware('auth:sanctum');

    Route::get('/logout', function (){
        \request()->user()->tokens()->delete();
        return response([], 204);
    })->middleware('auth:sanctum');

    Route::post('/getByToken', function (){
        return response()->json(['error'=>"false",'message'=>['user'=>\request()->user()],'name'=>\request()->user()->currentAccessToken()['name']], 200); ;
    })->middleware('auth:sanctum');

    Route::get('/getByToken', function (){
        return response()->json(['error'=>"false",'message'=>['user'=>\request()->user()],'name'=>\request()->user()->currentAccessToken()['name']], 200); ;
    })->middleware('auth:sanctum');

    Route::get('/read', [
        authcontroller::class,
        'read'
    ])->middleware('auth:sanctum','verified');

    Route::get('/2', function (){
        return response()->json(['user'=>\request()->user(),'name'=>\request()->user()->currentAccessToken()['name']], 200); ;
    })->middleware('auth:sanctum');

});

//modem
Route::prefix('modem')->group(function () {
    Route::get('/list', [
        modem::class,
        'modemList'
    ])->middleware('auth:sanctum');


    Route::get('/count', [
        modem::class,
        'modemCount'
    ])->middleware('auth:sanctum');

    Route::post('/mc', [
        modem::class,
        'modemMC'
    ])->middleware('auth:sanctum');

    Route::post('/plan', [
        modem::class,
        'modemPlan'
    ])->middleware('auth:sanctum');

    Route::post('/ban', [
        modem::class,
        'modemBan'
    ])->middleware('auth:sanctum');

    Route::post('/unban', [
        modem::class,
        'modemUNBan'
    ])->middleware('auth:sanctum');


    Route::post('/user', [
        modem::class,
        'modemUser'
    ])->middleware('auth:sanctum');

    Route::get('/read', [
        modem::class,
        'read'
    ])->middleware('auth:sanctum');

    Route::post('/register', [
        modem::class,
        'register'
    ])->middleware('auth:sanctum');

    Route::post('/update', [
        modem::class,
        'update'
    ])->middleware('auth:sanctum');


    Route::post('/mint', [
        modem::class,
        'mint'
    ]);

});

//token
Route::prefix('token')->group(function () {

    Route::post('/withdraw', [
        token::class,
        'withdraw'
    ])->middleware('auth:sanctum','verified');

    Route::post('/transfer', [
        token::class,
        'transfer'
    ])->middleware('auth:sanctum');

    Route::post('/pause', [
        token::class,
        'pause'
    ])->middleware('auth:sanctum');

    Route::post('/unpause', [
        token::class,
        'unPause'
    ])->middleware('auth:sanctum');

    Route::get('/history', [
        token::class,
        'history'
    ])->middleware('auth:sanctum');

    Route::post('/buy', [
        token::class,
        'buy'
    ])->middleware('auth:sanctum');

    Route::get('/test', [
        token::class,
        'test'
    ])->name('notification.test');

});

//plan
Route::prefix('plan')->group(function () {

    Route::get('/list', [
        plan::class,
        'list'
    ]);

    Route::post('/update', [
        plan::class,
        'update'
    ])->middleware('auth:sanctum');

    Route::post('/register', [
        plan::class,
        'register'
    ])->middleware('auth:sanctum');

    Route::get('/count', [
        plan::class,
        'count'
    ])->middleware('auth:sanctum');

});

//verifying
Route::get('email/verify',function (){
    return response()->json(['error'=>true,'message'=>'Email not verified.']);
})->middleware('auth:sanctum')->name('verification.notice');


Route::get('email/verify/{id}/{hash}',function (EmailVerificationRequest $request){
    $request->fulfill();
    return redirect('/home');
})->middleware('auth:sanctum')->name('verification.verify');

Route::get('email/verification-resend',function (Request $request){
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['error'=>false,'message'=>'Verification link sent.']);
})->middleware('auth:sanctum')->name('verification.verify');

//global





