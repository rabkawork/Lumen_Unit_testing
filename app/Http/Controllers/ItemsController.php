<?php

use App\Item;
use App\Checklist;
namespace App\Http\Controllers;

class ItemsController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function complete(Request $request)
    {

    }

    public function incomplete(Request $request)
    {

    }

    public function checklistitems(Request $request)
    {

    }

    public function create(Request $request)
    {

    }

    public function getone(Request $request,$id)
    {

    }

    public function update(Request $request,$id)
    {

    }

    //
}
