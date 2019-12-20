<?php

namespace App\Http\Controllers;
use App\History;
use Illuminate\Http\Request;
use DB;
use URL;

class HistoriesController extends Controller
{
    public $url = 'api/checklist/histories';

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

    public function getone(Request $request,$id)
    {

    }

}
