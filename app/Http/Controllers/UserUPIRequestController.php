<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserUPIRequestModel;

class UserUPIRequestController extends Controller
{

    public function set_number()
    {
        $number = mt_rand(1000000000, 9999999999);
        return UserUPIRequestModel::where('order_id', $number)->exists() ? $this->set_number() : $number;
    }

    function allManualRequest(Request $request)
    {
        return UserUPIRequestModel::all();
    }

    function getUserManualRequest(Request $request)
    {
        $user_id = $request->input('user_id');
        return UserUPIRequestModel::where('user_id', $user_id)->get();
    }

    public function addManualRequest(Request $request)
    {

        $current_date = date('Y-m-d H:i:s');
        $order_id = $this->set_number();
        $payment_mode = $request->input('payment_mode');
        $user_id = $request->input('user_id');
        $bank_name = $request->input('bank_name');
        $reference = $request->input('reference');
        $amount = $request->input('amount');
        $fileData = $request->file('paymentSlip');
        

        $reportImage = '';

        if ($fileData != null) {
            $imageUrl = $order_id . '.' . $fileData->extension();
            $fileData->move('images', $imageUrl);
            $reportImage = $imageUrl;
        } else {
            $reportImage = 'no-image-found.jpg';
        }

        $result = UserUPIRequestModel::insert([
            'create_at' => $current_date,
            'updated_at' => $current_date,
            'order_id' => $order_id,
            'payment_mode' => $payment_mode,
            'user_id' => $user_id,
            'bank_name' => $bank_name,
            'reference' => $reference,
            'amount' => $amount,
            'status' => 0,
            'paymentSlip' => $reportImage

        ]);

        if ($result == true) {
            return response()->json(['message' => 'Requested Added Successfully', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Transaction Added Failed', 'statusCode' => 404])->setStatusCode(404);

        }
    }

    function changeManualStatus(Request $request)
    {
        $current_date = date('Y-m-d H:i:s');
        $status = $request->input('status');
        $id = $request->input('id');

        $result = UserUPIRequestModel::where('id', $id)->update([
            'status' => $status,
            'updated_at' => $current_date
        ]);
        if ($result == true) {
            return response()->json(['message' => 'status Update Success', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Fail ! Try Again', 'statusCode' => 404])->setStatusCode(404);

        }
    }

    function deleteUpiManualRequest(Request $request)
    {
        $id = $request->input('id');
        $result = UserUPIRequestModel::where('id', $id)->delete();
        if ($result == true) {
            return response()->json(['message' => 'Delete Success', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Fail ! Try Again', 'statusCode' => 404])->setStatusCode(404);

        }
    }

}