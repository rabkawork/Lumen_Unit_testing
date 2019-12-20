<?php

namespace App\Http\Controllers;

use App\Item;
use App\Checklist;
use Illuminate\Http\Request;
use DB;
use URL;
// use Rule;
use Validator;
use Illuminate\Support\Facades\Auth;

class ItemsController extends Controller
{
    public $url = 'api/checklists';
    
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
        $reqBody = $request->all();
        $reqData = $reqBody['data'];
        
        try{
            $getIds = [];
            foreach ($reqData as $key => $value) {
                $getIds[] = $value['item_id'];
            }
            $items = DB::table('items')
                    ->select('id','item_id','is_complete','checklist_id')
                    ->whereIn('item_id', $getIds)
                    ->where('is_complete',true)
                    ->get();
            $response['data'] = $items;
            return response()->json($response,200);

        } catch (\Exception $e) {
            //return error message
            return response()->json([
                    'error'    => 'Server Error', 
                    'status'  => 500, 
                ], 500);
        }
    }

    public function incomplete(Request $request)
    {
        $reqBody = $request->all();
        $reqData = $reqBody['data'];
        
        try{
            $getIds = [];
            foreach ($reqData as $key => $value) {
                $getIds[] = $value['item_id'];
            }
            $items = DB::table('items')
                    ->select('id','item_id','is_complete','checklist_id')
                    ->whereIn('item_id', $getIds)
                    ->where(function($q) {
                        $q->where('is_complete', false)
                          ->orWhere('is_complete', null);
                    })
                    ->where('is_complete',null)
                    ->get();
            $response['data'] = $items;
            return response()->json($response,200);
        } catch (\Exception $e) {
            //return error message
            return response()->json([
                    'error'    => 'Server Error', 
                    'status'  => 500, 
                ], 500);
        }
    }

    public function listAllitems(Request $request,$checklistId)
    {
        try {
            $reqBody['checklistId'] = $checklistId;
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
                $itemsCount = DB::table('items')
                         ->select(DB::raw('count(*) as total'))
                         ->where('checklist_id',$checklistId)
                         ->first();

                $total = (int) $itemsCount->total;
                $limit  = $request->page_limit  ? (int) $request->page_limit  : 0;
                $offset = $request->page_offset ? (int) $request->page_offset : 0;
                $count = (int) $total < $limit ? 0 : ceil((int) $total / (int) $limit);

                $items = DB::table('items')
                        ->where('checklist_id',$checklistId)
                        ->offset((int) $offset)
                        ->limit((int) $limit)
                        ->get();

                $showPaging  = $this->showPaging((int) $total,$limit,$offset,$this->url,$count);

                $params            = $request->all();
                $response          = [];
                $response['meta']  = ['total' => (int) $total,'count' => $count];
                $response['links'] = $showPaging;
            


                $checklists = DB::table('checklists')
                        ->where('id',$checklistId)
                        ->first();

                $checklists->items = $items;

                $type = $checklists->type;

                $data = [];
                unset($checklists->id);
                unset($checklists->template_id);
                unset($checklists->type);
                unset($checklists->pos);
                $response['data']  = [
                                'id' => (int) $checklistId,
                                'type' => $type,
                                'attributes' => $checklists,
                                ];

                $response['links'] = ['self' => URL::to('/').'/checklists/'.$checklistId];
                
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


    public function createchecklistitem(Request $request,$checklistId)
    {
        $reqBody = $request->all(); 
        $reqAttributes = $reqBody['data']['attribute'];

        try {
            $reqBody['checklistId'] = $checklistId;
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
                $user = Auth::user();
                $Item = new Item();
                // $checklist->object_id     = $reqAttributes['object_id'];
                // $checklist->object_domain = $reqAttributes['object_domain'];
                $Item->description   = $reqAttributes['description'];
                $Item->assignee_id   = $reqAttributes['assignee_id'];
                $Item->due           = $reqAttributes['due'];
                $Item->urgency       = $reqAttributes['urgency'];
                $Item->task_id       = isset($reqAttributes['task_id']) ? $reqAttributes['task_id'] : 0;
                $Item->updated_by    = $user->id;
                $Item->template_id   = 0;

                $Item->is_complete   = isset($reqAttributes['is_complete']) ? (bool) $reqAttributes['is_complete'] : false;
                if(isset($reqAttributes['completed_at']))
                    $Item->completed_at  = $reqAttributes['completed_at'];
                // $Item->links         = json_encode($reqBody['links']);
                $Item->save();
                $itemId = $Item->id;


                $checklists = DB::table('checklists')
                ->where('id',$checklistId)
                ->first();

                $type = $checklists->type;
                $data = [];
                unset($checklists->id);
                unset($checklists->template_id);
                unset($checklists->type);
                unset($checklists->pos);
                $response  = ['data' => [
                                'id' => (int) $itemId,
                                'type' => $type,
                                'attributes' => $checklists,
                                'links' => ['self' => URL::to('/').'/checklists/'.$checklistId]
                                ]
                            ];
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

    public function getchecklistitem(Request $request,$checklistId,$itemId)
    {

        try {
            $reqBody['checklistId'] = $checklistId;
            $reqBody['itemId'] = $itemId;
                                    
            $validator = \Validator::make($reqBody, [
                'checklistId'     => 'exists:checklists,id',
                'itemId'          => 'exists:items,id',
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
                $user = Auth::user();

                $items = DB::table('items')
                ->where('id', $itemId)
                ->where('checklist_id', $checklistId)
                ->first();

                if(empty($items))
                {
                    return response()->json([
                        'error'      => 'Not Found', 
                        'status'     => 404
                        ],
                        404);
                }
                {
                    $type = $items->type;
                    $data = [];
                    unset($items->id);
                    unset($items->template_id);
                    unset($items->type);
                    unset($items->pos);
                    $response  = ['data' => [
                                    'id' => (int) $itemId,
                                    'type' => $type,
                                    'attributes' => $items,
                                    'links' => ['self' => URL::to('/').'/api/checklists/'.$checklistId]
                                    ]
                                ];
                    return response()->json($response,200);
                }
            }
        } catch (\Exception $e) {
            //return error message
            return response()->json([
                    'error'    => 'Server Error', 
                    'status'  => 500, 
                ], 500);
        }
    }

    public function updatechecklistitem(Request $request,$checklistId,$itemId)
    {

    }

    public function deletechecklistitems(Request $request,$checklistId,$itemId)
    {

    }

    public function updatechecklistitemsbulk(Request $request,$checklistId,$itemId)
    {

    }


    public function sumaries(Request $request)
    {

    }


    public function getall(Request $request)
    {

    }

}
