<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OtherModel;

class OtherController extends Controller
{

    function updateMealRate(Request $request)
    {

        $amount = $request->input('amount');

        $result = OtherModel::where('id', 1)->update(['fine_rate' => $amount]);

        if ($result == true) {
            return response()->json(['message' => 'Meal Rate Update Successfully', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Meal Rate Update Failed', 'statusCode' => 404])->setStatusCode(404);
        }
    }

    
    function updateFineAmmount(Request $request)
    {

        $amount = $request->input('amount');

        $result = OtherModel::where('id', 1)->update(['meal_rate' => $amount]);

        if ($result == true) {
            return response()->json(['message' => 'Fine Rate Update Successfully', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Fine Rate Update Failed', 'statusCode' => 404])->setStatusCode(404);
        }
    }


    function updateGuestMealRate(Request $request)
    {

        $amount = $request->input('amount');

        $result = OtherModel::where('id', 1)->update(['guest_meal_rate' => $amount]);

        if ($result == true) {
            return response()->json(['message' => 'Guest Meal Rate Update Successfully', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Guest Meal Rate Update Failed', 'statusCode' => 404])->setStatusCode(404);
        }
    }

    

    function chanegGuestMealAddedStatus(Request $request)
    {

        $statusCode = $request->input('statusCode');

        $result = OtherModel::where('id', 1)->update(['isAvaibleGuestMeal' => $statusCode]);

        if ($result == true) {
            return response()->json(['message' => 'Guest Meal Rate Update Successfully', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Guest Meal Rate Update Failed', 'statusCode' => 404])->setStatusCode(404);
        }
    }




    function getConfig(Request $request)
    {
        return response()->json(OtherModel::first())->setStatusCode(200);
    }



    function updateOfflineTakaCollectTime(Request $request)
    {

        $text = $request->input('text');

        $result = OtherModel::where('id', 1)->update(['offline_taka_load_time' => $text]);

        if ($result == true) {
            return response()->json(['message' => 'Offline TAKA load time has been update Successfully', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Offline TAKA load time has been update Failed', 'statusCode' => 404])->setStatusCode(404);
        }
    }


}