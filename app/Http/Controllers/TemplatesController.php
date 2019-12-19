<?php

namespace App\Http\Controllers;

use App\Item;
use App\Checklist;
use Illuminate\Http\Request;


class TemplatesController extends Controller
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

    public function index(Request $request)
    {

    }

    public function create(Request $request)
    {
        // return $request->all();
    }

    public function getone(Request $request,$id)
    {

    }

    public function update(Request $request,$id)
    {

    }

    public function remove(Request $request,$id)
    {

    }

    public function assigns(Request $request,$id)
    {

    }


    //
}
