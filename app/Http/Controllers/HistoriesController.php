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
        $historiesCount = DB::table('histories')
                     ->select(DB::raw('count(*) as total'))->first();

        $total = (int) $historiesCount->total;
        $limit  = $request->page_limit  ? (int) $request->page_limit  : 0;
        $offset = $request->page_offset ? (int) $request->page_offset : 0;
        $count = (int) $total < $limit ? 0 : ceil((int) $total / (int) $limit);

        $histories = DB::table('histories')
                ->offset((int) $offset)
                ->limit((int) $limit)
                ->get();

        $showPaging  = $this->showPaging((int) $total,$limit,$offset,$this->url,$count);

        $params            = $request->all();
        $response          = [];
        $response['meta']  = ['total' => (int) $total,'count' => $count];
        $response['links'] = $showPaging;
    
        $data = [];
        foreach ($histories as $key => $value) {
            
        
        }

        $response['data']  = $data;

        return response()->json($response,200);
    }

    public function getone(Request $request,$id)
    {

    }

}
