<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\TransactionModel;

class TransactionController extends Controller
{
    function getUserAllTransAction(Request $request)
    {
        $studentID = $request->input('studentID');

        $result = TransactionModel::where('studentID', $studentID)->orderby('created_at','desc')->paginate(10);

        if ($result == true) {

            return response()->json($result,200);

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }

    }

    function getAllTransctionList(Request $request)
    {
        
        return TransactionModel::orderby('created_at','desc')->paginate(10);
    }

}
