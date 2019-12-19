<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use URL;

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


    protected function showPaging($total,$limit,$offset,$url,$count)
    {

        $first = $count > 0 ? URL::to('/').'/'.$url.'?page[limit]='.(int) $limit.'page[offset]=0' : "null";
        $last  = $count > 0 ? URL::to('/').'/'.$url.'?page[limit]='.(int) $limit.'page[offset]='.((int) $count - 1) * $limit : "null";

        $next  = $count > 0 && $offset < $count ? 
                    URL::to('/').'/'.$url.'?page[limit]='.(int) $limit.'page[offset]='.((int) $limit + (int) $offset) : "null";
        $prev  = $count > 0 && $offset > 0 ? 
                    URL::to('/').'/'.$url.'?page[limit]='.(int) $limit.'page[offset]='.((int) $limit - (int) $offset)  : "null";

        return ['first' => $first,'last' => $last,'next' => $next,'prev' => $prev];
    }

}
