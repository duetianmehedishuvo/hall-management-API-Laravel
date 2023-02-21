<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserMobileRequestModel;
use App\Models\RegistrationModel;
use Illuminate\Support\Facades\DB;

class UserMobileRequestController extends Controller
{
    public function getUniqueRefNo()
    {
        $number = mt_rand(1000000, 9999999);
        return UserMobileRequestModel::where('orderID', $number)->exists() ? $this->getUniqueRefNo() : $number;
    }

    function allMobileRequestData(Request $request)
    {
        $isMobileRecharge = $request->input('isMobileRecharge');
        return DB::table('usermobilerequestreport')
            ->leftJoin('provider', 'provider.id', '=', 'usermobilerequestreport.provider_id')
            ->select(
                'usermobilerequestreport.id',
                'usermobilerequestreport.provider_id',
                'usermobilerequestreport.recharge_number',
                'usermobilerequestreport.amount',
                'usermobilerequestreport.refNo',
                'usermobilerequestreport.transitionID',
                'usermobilerequestreport.orderID',
                'usermobilerequestreport.transStatus',
                'usermobilerequestreport.createDate',
                'usermobilerequestreport.user_id',
                'usermobilerequestreport.communication',
                'provider.name',
                'provider.api_code',
                'provider.image'
            )->where(['isMobileRecharge' => $isMobileRecharge])
            ->orderBy('usermobilerequestreport.id', 'desc')
            ->get();
    }

    function getUserMobileRechargeRequest(Request $request)
    {
        $user_id = $request->input('user_id');
        $isMobileRecharge = $request->input('isMobileRecharge');
        return DB::table('usermobilerequestreport')
            ->leftJoin('provider', 'provider.id', '=', 'usermobilerequestreport.provider_id')
            ->select(
                'usermobilerequestreport.id',
                'usermobilerequestreport.provider_id',
                'usermobilerequestreport.recharge_number',
                'usermobilerequestreport.amount',
                'usermobilerequestreport.refNo',
                'usermobilerequestreport.transitionID',
                'usermobilerequestreport.orderID',
                'usermobilerequestreport.transStatus',
                'usermobilerequestreport.user_id',
                'usermobilerequestreport.createDate',
                'usermobilerequestreport.communication',
                'provider.name',
                'provider.api_code',
                'provider.image'
            )->where(['user_id' => $user_id, 'isMobileRecharge' => $isMobileRecharge])
            ->get();
    }


    public function addUserMobileRequest(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $current_date = date('Y-m-d H:i:s');
        $provider_id = $request->input('provider_id');
        $recharge_number = $request->input('recharge_number');
        $amount = $request->input('amount');
        $refNo = $request->input('refNo');
        $user_id = $request->input('user_id');
        $transitionID = $request->input('transitionID');
        $orderID = $request->input('orderID');
        $transStatus = $request->input('transStatus');
        $communication = $request->input('communication');
        $isMobileRecharge = $request->input('isMobileRecharge');

        $result = UserMobileRequestModel::insert([
            'createDate' => $current_date,
            'provider_id' => $provider_id,
            'amount' => $amount,
            'recharge_number' => $recharge_number,
            'refNo' => $refNo,
            'user_id' => $user_id,
            'transitionID' => $transitionID,
            'orderID' => $orderID,
            'communication' => $communication,
            'isMobileRecharge' => $isMobileRecharge,
            'transStatus' => $transStatus
        ]);

        if ($transStatus == 0 || $transStatus == 1 || $transStatus == 4 || $transStatus == 6) {
            $this->updateBalance($amount, $user_id);
        }


        if ($result == true) {
            return response()->json(['message' => 'Report Added Successfully', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Report Added Failed', 'statusCode' => 404])->setStatusCode(404);

        }
    }

    public function updateBalance($walletBalance, $userID)
    {

        $value = RegistrationModel::where('id', $userID)->get();

        $newBalance = $value[0]['walletBalance'] - $walletBalance;

        RegistrationModel::where('id', $userID)->update(['walletBalance' => $newBalance]);

    }


    public function updateMobileRechargeStatus(Request $request)
    {

        $id = $request->input('id');
        $transStatus = $request->input('transStatus');
        $orderID = $request->input('orderID');

        $result = UserMobileRequestModel::where('id', $id)->update(['transStatus' => $transStatus, 'orderID' => $orderID]);

        if ($result == true) {
            return response()->json(['message' => 'Update Successfully', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Report Added Failed', 'statusCode' => 404])->setStatusCode(404);

        }
    }

}