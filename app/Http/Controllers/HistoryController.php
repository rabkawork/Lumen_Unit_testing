<?php

use App\Item;
use App\Checklist;
namespace App\Http\Controllers;

class HistoryController extends Controller
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
