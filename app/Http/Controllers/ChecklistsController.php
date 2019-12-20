<?php

namespace App\Http\Controllers;

use App\Item;
use App\Checklist;
use App\History;
use Illuminate\Http\Request;
use DB;
use URL;

class ChecklistsController extends Controller
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
        // $checklistsCount = DB::table('checklists')
                //              ->select(DB::raw('count(*) as total'))->first();

                // $total = (int) $checklistsCount->total;
                // $limit  = $request->page_limit  ? (int) $request->page_limit  : 0;
                // $offset = $request->page_offset ? (int) $request->page_offset : 0;
                // $count = (int) $total < $limit ? 0 : ceil((int) $total / (int) $limit);

                $checklists = DB::table('checklists')
                        ->where('id',$id)
                        // ->offset((int) $offset)
                        // ->limit((int) $limit)
                        ->first();

                // $showPaging  = $this->showPaging((int) $total,$limit,$offset,$this->url,$count);

                $params            = $request->all();
                $response          = [];
                // $response['meta']  = ['total' => (int) $total,'count' => $count];
                // $response['links'] = $showPaging;
            
                $data = [];
                foreach ($templates as $key => $value) {
                    $templates = DB::table('templates')->select('name')
                        ->where('id', '=', $value->id)
                        ->first();

                    $checklists = DB::table('checklists')->select('description','due_unit','due_interval')
                        ->where('template_id', '=', $value->id)
                        ->first();

                    $items = DB::table('items')->select('description','urgency','due_unit','due_interval')
                        ->where('template_id', '=', $value->id)
                        ->get();

                    $data[] = ['id' => $value->id,'name' => $templates->name,'checklists' => $checklists,'items' => $items];
                
                }

    }

    public function create(Request $request)
    {
        
    }

    public function getone(Request $request,$id)
    {


        try {
            $reqBody['checklistId'] = $id;
            $validator = \Validator::make($reqBody, [
                'checklistId'     => 'exists:checklists,id',
            ]);

            if ($validator->fails()) 
            {
                //return required validation
                return response()->json([
                        'error'      => 'Not Found', 
                        'status'     => 404
                        ],
                       404);
            }
            else
            {

                $checklists = DB::table('checklists')
                        ->where('id',$id)
                        ->first();

                $type = $checklists->type;
                $data = [];
                unset($checklists->id);
                unset($checklists->template_id);
                unset($checklists->type);
                unset($checklists->pos);
                $response  = ['data' => [
                                'id' => (int) $id,
                                'type' => $type,
                                'attributes' => $checklists,
                                'links' => URL::to('/').'/checklists/'.$id
                                ]
                             ];
                $include = !empty($request->include) ? $request->include : '';
                $items   = [];
                if($include == "items"){
                    $items = DB::table('items')->select('description','urgency','due_unit','due_interval')
                    ->where('checklist_id', '=', $id)
                    ->get();
                    $response['data']['items'] = $items;
                }
                return response()->json($response,200);
            }


        } catch (\Exception $e) {
            //return error message
            return response()->json([
                    'error'    => 'Server Error', 
                    'status'  => 500, 
                ], 500);
        }
    }

    public function update(Request $request,$id)
    {

    }

    public function remove(Request $request,$id)
    {
        try {
            $reqBody['checklistId'] = $id;
            $validator = \Validator::make($reqBody, [
                 'checklistId'     => 'required|exists:checklists,id',
            ]);

            if ($validator->fails()) 
            {
                //return required validation
                return response()->json(
                        [
                            'error'    => $validator->errors(), 
                            'status'     => 400
                        ],
                       400);
            }
            else
            {
                Checklist::where('id', $id)->delete();
                return response()->json('',204);
            }

        } catch (\Exception $e) {
            //return error message
            return response()->json([
                    'error'    => 'Server Error', 
                    'status'  => 500, 
                ], 500);
        }
    }

    public function listofchecklists(Request $request)
    {
        
    }

    //
}
