<?php

/** @noinspection PhpUndefinedClassInspection */

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{



    public function signup(UserRequest $request)
    {
        $user = new User();
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        // $user->verification_code = rand(100000, 999999);
        $user->email_verified_at =  date('Y-m-d H:m:s');
        $user->save();
        //create token
        $user->createToken('tokens')->plainTextToken;
        return $this->_loginSuccess($user);
    }



    public function login(Request $request)
    {
        $messages = array(
            'email.required' => ('Email is required'),
            'email.email' => ('Email must be a valid email address'),
            'password.required' => ('Password is required'),
        );
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'email' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        $req_email = $request->email;

            $user = User::where('email', $req_email)->first();

            if ($user &&  Hash::check($request->password, $user->password)) {

                return $this->_loginSuccess($user);
            }else{
                return $this->_loginFailed();
            }
    }

    public function logout(Request $request)
    {

        $user = auth()->user();

        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return $this->success('Successfully logged out');
    }

  
    public function _loginSuccess($user, $token = null)
    {

        if (!$token) {
            $token = $user->createToken('API Token')->plainTextToken;
        }
        return response()->json([
            'result' => true,
            'message' => ('Successfully logged in'),
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => null,
            'user' => [
                'id' => $user->id,
                'type' => $user->user_type,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'address' => $user->address,
                'avatar_original' => ($user->avatar_original),
                'phone' => $user->phone,
                'email_verified' => $user->email_verified_at != null
            ]
        ]);
    }


    protected function _loginFailed()
    {

        return response()->json([
            'result' => false,
            'message' => ('Login Failed'),
            'access_token' => '',
            'token_type' => '',
            'expires_at' => null,
            'user' => [
                'id' => 0,
                'type' => '',
                'name' => '',
                'email' => '',
                'avatar' => '',
                'avatar_original' => '',
                'phone' => ''
            ]
        ]);
    }



    public function infoByToken()
    {
        $token = PersonalAccessToken::findToken(request()->bearerToken());
        if (!$token) {
            return $this->_loginFailed();
        }
        $user = $token->tokenable;

        if ($user == null) {
            return $this->_loginFailed();
        }

        return $this->_loginSuccess($user, request()->bearerToken());
    }

}
