<?php
///no attention to baned users!!

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\repository\userRep;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class authcontroller extends Controller
{
    //register, login, read, editEmail, editPass, editAddress, modem, token, test, userCount, userList, userBan,
    //
    /**
     * @param Request $request
     * @return JsonResponse
     * @method post
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate(
            [
                'MCID' => ['required'],
                'email' => ['required','email','unique:users'],
                'password' => 'required',
            ]);

        $results=resolve(userRep::class)->create($request);
	    if ($results  )
        {
	        $Maintoken= $results->createToken('owner', ['signed'])->plainTextToken;
            return response()->json(["error"=> false, "user"=> $results,
                "token"=> $Maintoken,"message"=> "registered successfully"],201);
	    }else{
	        return response()->json(["error"=> true, "user"=> $results, "message"=> "Cant do this"],203);
	    }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @method get
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate(
            [
                'email' => ['required','email'],
                'password' => ['required'],
            ]);

        $user = User::where('email', \request('email'))->first();
        if (! $user || ! Hash::check(\request('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $user->tokens()->where('tokenable_id',$user->id)->where('name','owner')->delete();
        $Maintoken= $user->createToken('owner', ['signed'])->plainTextToken;
        return response()->json(["error"=>false , "message"=>"logged in successfully","token"=> $Maintoken]);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @method get
     * @throws ValidationException
     */
    public function read(Request $request): JsonResponse
    {
        $thisUser = $request->user() ;
        if ($thisUser->isBan) return response()->json([
            "error" => true,
            "message" => "banned user. "
        ],403);
        if ($thisUser -> tokenCan('admin')) {
            $request->validate(
                [
                    'email' => ['required','email'],
                ]);
            $results=resolve(userRep::class)->read($request->input('email'));
            if ($results  )
            {
                //$Maintoken= $results->createToken('owner', ['data:receive'])->plainTextToken;
                return response()->json(["error"=> false, "user"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "user"=> $results, "message"=> "unregistered user"],203);
            }
        }else if ($request->user() -> tokenCan('signed')) {
            return response()->json(["error"=> false, "user"=> $thisUser,
                "message"=> "done"]);
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
    public function editEmail(Request $request): JsonResponse
    {
        $thisUser = $request->user() ;
        if ($thisUser->isBan) return response()->json([
            "error" => true,
            "message" => "banned user. "
        ],403);
        if ($request->user() -> tokenCan('admin')) {
            $request->validate(
                [
                    'email' => ['required','email'],
                    'newEmail' => ['required','email'],
                ]);
            $results=resolve(userRep::class)->updateEmail($request->input('email'),$request->input('newEmail'));
            if ($results  )
            {
                return response()->json(["error"=> false, "user"=> $results,
                    "message"=> "done"],202);
            }else{
                return response()->json(["error"=> true, "user"=> $results, "message"=> "unregistered user"],203);
            }
        }elseif ($request->user() -> tokenCan('signed')) {
            $request->validate(
                [
                    'newEmail' => ['required','email'],
                ]);
            $results=resolve(userRep::class)->updateEmail($request->user()->email,$request->input('newEmail'));
            if ($results  )
            {
                return response()->json(["error"=> false, "user"=> $results,
                    "message"=> "done"],202);
            }else{
                return response()->json(["error"=> true, "user"=> $results, "message"=> "unregistered user"],203);
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
    public function editPass(Request $request): JsonResponse
    {
        $thisUser = $request->user() ;
        if ($thisUser->isBan) return response()->json([
            "error" => true,
            "message" => "banned user. "
        ],403);
        if ($thisUser-> tokenCan('admin')) {
            $request->validate(
                [
                    'email' => ['required','email'],
                    'password' => ['required'],
                    'newPassword' => ['required'],
                ]);
            $results=resolve(userRep::class)->updatePass($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "user"=> $results,
                    "message"=> "done"],202);
            }else{
                return response()->json(["error"=> true, "user"=> $results, "message"=> "unregistered user"],203);
            }
        }elseif ($thisUser -> tokenCan('signed')) {
            $request->validate(
                [
                    'password' => ['required'],
                    'newPassword' => ['required'],
                ]);
            $results=resolve(userRep::class)->updatePass($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "user"=> $results,
                    "message"=> "done"],202);
            }else{
                return response()->json(["error"=> true, "user"=> $results, "message"=> "unregistered user"],203);
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
    public function editAddress(Request $request): JsonResponse
    {
        $thisUser = $request->user() ;
        if ($thisUser->isBan) return response()->json([
            "error" => true,
            "message" => "banned user. "
        ],403);
        if ($request->user() -> tokenCan('admin')) {
            $request->validate(
                [
                    'email' => ['required','email'],
                    'address' => ['required'],
                ]);
            $results=resolve(userRep::class)->updateAddress($request->input('email'),$request->input('address'));
            if ($results  )
            {
                return response()->json(["error"=> false, "user"=> $results,
                    "message"=> "done"],202);
            }else{
                return response()->json(["error"=> true, "user"=> $results, "message"=> "unregistered user"],203);
            }
        }elseif ($request->user() -> tokenCan('signed')) {
            $request->validate(
                [
                    'address' => ['required'],
                ]);
            $results=resolve(userRep::class)->updateAddress($request->user()->email,$request->input('address'));
            if ($results  )
            {
                return response()->json(["error"=> false, "user"=> $results,
                    "message"=> "done"],202);
            }else{
                return response()->json(["error"=> true, "user"=> $results, "message"=> "unregistered user"],203);
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
    public function modem(Request $request): JsonResponse
    {
        $thisUser = $request->user() ;
        if ($thisUser->isBan) return response()->json([
            "error" => true,
            "message" => "banned user. "
        ],403);
        if ($request->user() -> tokenCan('admin')) {
            $request->validate(
                [
                    'email' => ['required','email'],
                ]);
            $results=resolve(userRep::class)->modemCount($request->input('email'));
            if ($results  )
            {
                return response()->json(["error"=> false, "count"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "unregistered user"],203);
            }
        }elseif ($request->user() -> tokenCan('signed')) {

            $results=resolve(userRep::class)->modemCount($request->user()->email);
            if ($results  )
            {
                return response()->json(["error"=> false, "count"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "unregistered user"],203);
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
    public function token(Request $request): JsonResponse
    {
        $thisUser = $request->user() ;
        if ($thisUser->isBan) return response()->json([
            "error" => true,
            "message" => "banned user. "
        ],403);
        if ($request->user() -> tokenCan('admin')) {
            $request->validate(
                [
                    'email' => ['required','email'],
                ]);
            $results=resolve(userRep::class)->token($request->input('email'));
            if ($results  )
            {
                return response()->json(["error"=> false, "token"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "unregistered user"],203);
            }
        }elseif ($request->user() -> tokenCan('signed')) {

            $results=resolve(userRep::class)->token($request->user()->email);
            if ($results  )
            {
                return response()->json(["error"=> false, "token"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "unregistered user"],203);
            }
        }else{
            return response()->json([
                "error" => true,
                "message" => "unauthenticated. "
            ],400);
        }
    }

    /**
     * @return JsonResponse
     * @method get
     */
    public function test(): JsonResponse
    {
        return response()->json([
            "error" => false,
            "message" => "hello world"
        ]);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @method get
     * @throws ValidationException
     */
    public function userList(Request $request): JsonResponse
    {
        if ($request->user() -> tokenCan('admin')) {

            $results=resolve(userRep::class)->userList($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "user"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "no user!"],203);
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
    public function userCount(Request $request): JsonResponse
    {
        $results=resolve(userRep::class)->userCount();
        if ($results  )
        {
            return response()->json(["error"=> false, "count"=> $results,
                "message"=> "done"]);
        }else{
            return response()->json(["error"=> true,  "message"=> "no user"],203);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @method get
     * @throws ValidationException
     */
    public function userBan(Request $request): JsonResponse
    {
        $request->validate(
            [
                'email' => ['required','email'],
            ]);
        if ($request->user() -> tokenCan('admin')) {
            $results=resolve(userRep::class)->userBan($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "user"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "unregistered user!"],203);
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
    public function userUnBan(Request $request): JsonResponse
    {
        $request->validate(
            [
                'email' => ['required','email'],
            ]);
        if ($request->user() -> tokenCan('admin')) {
            $results=resolve(userRep::class)->userUnBan($request);
            if ($results  )
            {
                return response()->json(["error"=> false, "user"=> $results,
                    "message"=> "done"]);
            }else{
                return response()->json(["error"=> true, "message"=> "unregistered user!"],203);
            }
        }else{
            return response()->json([
                "error" => true,
                "message" => "unauthenticated. "
            ],400);
        }
    }
}
