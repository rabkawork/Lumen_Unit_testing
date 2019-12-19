<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    //
    protected function authResponse($token)
    {
        return 
        [
	            'token' 	 => $token,
	            'token_type' => 'bearer',
	            'expires_in' => Auth::factory()->getTTL() * 60
        ];
    }

}
