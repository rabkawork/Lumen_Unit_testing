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

    public function index(Request $request)
    {

        // try {

            $checklistsCount = DB::table('checklists')
                         ->select(DB::raw('count(*) as total'))->first();

            $total = (int) $checklistsCount->total;
            $limit  = $request->page_limit  ? (int) $request->page_limit  : 0;
            $offset = $request->page_offset ? (int) $request->page_offset : 0;
            $count = (int) $total < $limit ? 0 : ceil((int) $total / (int) $limit);

            $checklists = DB::table('checklists')
                    ->offset((int) $offset)
                    ->limit((int) $limit)
                    ->get();

            $showPaging  = $this->showPaging((int) $total,$limit,$offset,$this->url,$count);

            $params            = $request->all();
            $response          = [];
            $response['meta']  = ['total' => (int) $total,'count' => $count];
            $response['links'] = $showPaging;
        
            $data = [];
            foreach ($checklists as $key => $value) {

                $type = $value->type;
                $id = $value->id;

                unset($value->id);
                unset($value->template_id);
                unset($value->type);
                unset($value->pos);
                $data[]  = [
                                'id' => (int) $id,
                                'type' => $type,
                                'attributes' => $value,
                                'links' => ['self' => URL::to('/').'/checklists/'.$id]
                        ];
            
            }
            $response['data']  = $data;

            return response()->json($response,200);

        // } catch (\Exception $e) {
        //     //return error message
        //     return response()->json([
        //             'error'    => 'Server Error', 
        //             'status'  => 500, 
        //         ], 500);
        // }
    }

    public function create(Request $request)
    {
        $reqBody = $request->all();
        $reqAttributes = $reqBody['data']['attributes'];
        try{
            $validator = \Validator::make($reqBody, [
                'data'             => 'required',
                'data.attributes'  => 'required',
                'data.attributes.object_domain'  => 'required',
                'data.attributes.object_id'      => 'required',
                'data.attributes.description'    => 'required',
            ]);

            if ($validator->fails()) 
            {
                //return required validation
                return response()->json([
                        'error'      => $validator->errors(), 
                        'status'     => 404
                        ],
                       404);
            }
            else
            {


                $checklist = new Checklist();
                $checklist->type          = 'checklists';
                $checklist->due           = $reqAttributes['due'];
                $checklist->object_id     = $reqAttributes['object_id'];
                $checklist->object_domain = $reqAttributes['object_domain'];
                $checklist->description   = $reqAttributes['description'];
                $checklist->urgency       = $reqAttributes['urgency'];
                $checklist->task_id       = $reqAttributes['task_id'];
                $checklist->template_id   = 0;
                // $checklist->links         = json_encode($links);
                $checklist->save();
                $id = $checklist->id;
                $links = ['links' => ['self' => URL::to('/').'/checklists/'.$id]];
                $updateChecklist = Checklist::find($id);
                $updateChecklist->links = json_encode($links,true);
                $updateChecklist->save();



                foreach ($reqAttributes['items'] as $key => $value) {
                    $item      = new Item();
                    $item->type         = 'checklists';
                    $item->pos          = $key;
                    $item->description  = $value;
                    $item->checklist_id = $id;
                    $item->save();
                }

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
                return response()->json($response,201);
            }
        }catch (\Exception $e) {
            //return error message
            return response()->json([
                    'error'    => 'Server Error', 
                    'status'  => 500, 
                ], 500);
        }
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
        $reqBody = $request->all();
        $reqAttributes = $reqBody['data']['attributes'];
        $reqData       = $reqBody['data'];

        try{

            $validator = \Validator::make($reqBody, [
                'data'                           => 'required',
                'data.type'                      => 'required',
                'data.id'                        => 'required',
                'data.attributes.object_domain'  => 'required',
                'data.attributes.object_id'      => 'required',
                'data.attributes.description'    => 'required',
                'data.links'          => 'required',
                'data.links.self'     => 'required',
            ]);

            if ($validator->fails()) 
            {
                //return required validation
                return response()->json([
                        'error'      => $validator->errors(), 
                        'status'     => 404
                        ],
                       404);
            }
            else
            {

                $checklist = Checklist::find($id);
                $checklist->type          = $reqData['type'];
                $checklist->object_id     = $reqAttributes['object_id'];
                $checklist->object_domain = $reqAttributes['object_domain'];
                $checklist->description   = $reqAttributes['description'];
                $checklist->completed_at  = $reqAttributes['completed_at'];
                $checklist->is_complete   = $reqAttributes['is_completed'];
                $checklist->template_id   = 0;
                $checklist->links         = json_encode($reqData['links']);
                $checklist->save();

    

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
                                'links' => $reqData['links']['self']
                                ]
                             ];
                return $reqBody;
            }

        }catch (\Exception $e) {
            //return error message
            return response()->json([
                    'error'    => 'Server Error', 
                    'status'  => 500, 
                ], 500);
        }
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

    //
}
