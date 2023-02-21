<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankModel;

class BankController extends Controller
{
    function allBankList(Request $request)
    {
        return BankModel::all();
    }

    function addBankData(Request $request)
    {

        $bankName = $request->input('bankName');

        $result = BankModel::insert(['bankName' => $bankName]);
        if ($result == true) {
            return response()->json(['message' => 'Bank Add Success ', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Bank add Failed', 'statusCode' => 404])->setStatusCode(404);

        }
    }

    function updateBank(Request $request)
    {
        $id = $request->input('id');
        $bankName = $request->input('bankName');
        $result = BankModel::where('id', $id)->update([
            'bankName' => $bankName
        ]);
        if ($result == true) {
            return response()->json(['message' => 'Update Success', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Fail ! Try Again', 'statusCode' => 404])->setStatusCode(404);

        }
    }

    function deleteBankName(Request $request)
    {
        $id = $request->input('id');
        $result = BankModel::where('id', $id)->delete();
        if ($result == true) {
            return response()->json(['message' => 'Delete Success', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Fail ! Try Again', 'statusCode' => 404])->setStatusCode(404);

        }
    }

}