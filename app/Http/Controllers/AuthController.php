<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Tymon\JWTAuth\Exceptions\JWTException;
// use JWTAuth;

class AuthController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		//
	}

	/**
	 * POST the login account.
	 *
	 * @return Token
	 */
	public function login(Request $request) {
		try {
            // $this->validate($request, [
            $validator = \Validator::make($request->all(), [
    			'email'    => 'required|string',
    			'password' => 'required|string',
    		]);

            if ($validator->fails()) 
            {
                //return required validation
                return response()->json([
                        'data'    => $validator->errors(), 
                        'message' => 'Login Failed!',
                        'status'  => 411
                       ],
                       411);
            }
            else
            {

        		$credentials = $request->only(['email', 'password']);
                $token = Auth::attempt($credentials);

        		if (!$token) 
                {
                    return response()->json([
                            'data'    => null, 
                            'message' => 'Email Or Password Not Found',
                            'status'  => 404, 
                        ], 404);
        		}
                else
                {
                    $user = Auth::user();
                    $user['authorization'] = $this->authResponse($token);
                    return response()->json([
                        'data'    => $user, 
                        'message' => 'Login Success',
                        'status'  => 200, 
                    ],200);
                }
            }

        } catch (\Exception $e) {
            //return error message
            return response()->json([
                    'data'    => $e, 
                    'message' => 'Login Failed!',
                    'status'  => 500, 
                ], 500);
        }
	}

}
