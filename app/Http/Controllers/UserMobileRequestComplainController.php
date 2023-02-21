<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserMobileRequestComplainModels;

class UserMobileRequestComplainController extends Controller
{
    function addComplain(Request $request)
    {
        $userID = $request->input('userId');
        $transactionID = $request->input('transactionID');
        $title = $request->input('title');
        $isMobileRecharge = $request->input('isMobileRecharge');
        date_default_timezone_set('Asia/Kolkata');
        $current_date = date('Y-m-d H:i:s');

        $result = UserMobileRequestComplainModels::insert([
            'transactionID' => $transactionID,
            'userID' => $userID,
            'title' => $title,
            'isMobileRecharge' => $isMobileRecharge,
            'createDate' => $current_date
        ]);

        if ($result == true) {
            return response()->json(['message' => 'Complain Added Successfully', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'State Added Failed', 'statusCode' => 404])->setStatusCode(404);
        }

    }


    function getMobileRechargeComplainByUserID(Request $request)
    {
        $user_id = $request->input('user_id');
        $isMobileRecharge = $request->input('isMobileRecharge');
        return UserMobileRequestComplainModels::where(['userID' => $user_id, 'isMobileRecharge' => $isMobileRecharge])->get();
    }


    function getMobileRechargeComplainByTransactionID(Request $request)
    {
        $transactionID = $request->input('transactionID');
        return UserMobileRequestComplainModels::where('transactionID', $transactionID)->get();
    }


    function getAllComplain(Request $request)
    {

        return UserMobileRequestComplainModels::orderBy('id', 'desc')->get();
    }
}