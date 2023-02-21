<?php

namespace App\Http\Controllers;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Models\MeterModel;
use App\Models\TransactionModel;
use Illuminate\Support\Facades\DB;

class MeterController extends Controller
{
    
    
    function onSelect(Request $request)
    {
        return MeterModel::all();
    }

    function onInsert(Request $request)
    {

        $year = $request->input('year');
        $meterID = $request->input('meter-id');
        $count=MeterModel::where(["meterID" => $meterID])->count();
        if($count==0){
            $result = MeterModel::insert([
                'year' => $year,
                'meterID' => $meterID,
            ]);
            if ($result == true) {
                return response()->json(['message' => 'Meter Save Success ', 'statusCode' => 200])->setStatusCode(200);
                
            } else {
                return response()->json(['message' => 'Save Failed', 'statusCode' => 404])->setStatusCode(404);
               
            }
        }else{
            return response()->json(['message' => 'Meter ID already exists', 'statusCode' => 404])->setStatusCode(404);
        }
    }

    function getMeterByID(Request $request)
    {
        $meterID = $request->input('meterID');
        $meterCount = MeterModel::where('meterID', $meterID)->count();
        if ($meterCount >= 1) {
            return MeterModel::where('meterID', $meterID)->first();
        } else {
            return response()->json(['message' => 'Meter not found', 'statusCode' => 404])->setStatusCode(404);
        }
    }

    function updateMeter(Request $request)
    {
        $meterID = $request->input('meterID');
        $year = $request->input('year');
        $meterCount = MeterModel::where('meterID', $meterID)->count();
        if ($meterCount >= 1) {
            $result = MeterModel::where('meterID', $meterID)->update([
                'year' => $year
            ]);
            if ($result == true) {
                return response()->json(['message' => 'Update Success', 'statusCode' => 200])->setStatusCode(200);
                
            } else {
                return response()->json(['message' => 'Fail ! Try Again', 'statusCode' => 404])->setStatusCode(404);
                
            }
        } else {
            return response()->json(['message' => 'Meter not found', 'statusCode' => 404])->setStatusCode(404);
        }
    }

    function deleteMeter(Request $request)
    {
        $meterID = $request->input('meterID');
        $meterCount = MeterModel::where('meterID', $meterID)->count();
        if ($meterCount >= 1) {
            $result = MeterModel::where('meterID', $meterID)->delete();
            if ($result == true) {
                return response()->json(['message' => 'Delete Success', 'statusCode' => 200])->setStatusCode(200);
                
            } else {
                return response()->json(['message' => 'Fail ! Try Again', 'statusCode' => 404])->setStatusCode(404);
                
            }
        } else {
            return response()->json(['message' => 'Meter not found', 'statusCode' => 404])->setStatusCode(404);
        }
    }

}
