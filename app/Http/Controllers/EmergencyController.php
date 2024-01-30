<?php

namespace App\Http\Controllers;

use App\Models\EmergencyModel;
use App\Models\RegistrationModel;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class EmergencyController extends Controller
{
    function addEmergency(Request $request)
    {
        
        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');

        $access_token = str_replace('Bearer ', '', $request->header('Authorization'));
        $key = env('TOKEN_KEY');
        $decoded = JWT::decode($access_token, new Key($key, 'HS256'));
        $decoded_array = (array) $decoded;
        $studentID = $decoded_array['studentID'];

        $student=RegistrationModel::where('studentID',$studentID)->first();


        $result = EmergencyModel::insert([
            'dept' => $student['department'],
            'studentID' => $studentID,
            'name' => $student['name'],
            'create_at' => $current_date
        ]);

        if ($result == true) {

            return response()->json(['message' => 'Emergency Created'])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Emergency added Fail Please Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }


    function getAllEmergency(Request $request)
    {
       
        $result = EmergencyModel::orderby('create_at','desc')->get();

        if ($result == true) {

            return response()->json($result,200);

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }

    }
}
