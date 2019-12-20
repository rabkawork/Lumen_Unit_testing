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
        $reqBody = $request->all();
        $reqData = $reqBody['data'];
        
        try{
            $getIds = [];
            foreach ($reqData as $key => $value) {
                $getIds[] = $value->item_id;
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
                $getIds[] = $value->item_id;
            }
            $items = DB::table('items')
                    ->select('id','item_id','is_complete','checklist_id')
                    ->whereIn('item_id', $getIds)
                    ->where('is_complete',false)
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

    }


    public function createChecklistitem(Request $request,$checklistId)
    {

    }

    public function getchecklistitem(Request $request,$checklistId,$itemId)
    {

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
