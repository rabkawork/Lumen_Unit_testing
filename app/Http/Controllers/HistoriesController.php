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
        return $request->all();
    }

    public function getone(Request $request,$historyId)
    {
        // return $request->all();
        try {
            $reqBody['historyId'] = $id;
            $validator = \Validator::make($reqBody, [
                'historyId'     => 'exists:histories,id',
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

                $histories = DB::table('histories')
                        ->where('id',$id)
                        ->first();

                $type = 'history';
                $data = [];
                unset($checklists->id);
                $response  = ['data' => [
                                'id' => (int) $id,
                                'type' => $type,
                                'attributes' => $histories,
                                'links' => URL::to('/').'/checklists/'.$id
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

}
