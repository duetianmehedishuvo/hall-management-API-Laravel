<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HallFeeDetailsModel;
use App\Models\HallFeeModel;
use App\Models\TransactionModel;
use App\Models\OtherModel;
use App\Models\RoomModels;
use \Carbon\Carbon;

class HallFeeController extends Controller
{

    public function set_number()
    {
        $number = mt_rand(1000000000, 9999999999);
        return TransactionModel::where('transactionID', $number)->exists() ? $this->set_number() : $number;
    }

    function addHallFee(Request $request)
    {
        $amount = $request->input('amount');
        $purpose = $request->input('purpose');
        $type = $request->input('type');
        $transactionID = $this->set_number();

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');

        $users = RoomModels::where('isAvaible', 1)->get();

        foreach ($users as $user) {

            $result = HallFeeModel::insert([
                'studentID' => $user['studentID'],
                'amount' => $amount,
                'transactionID' => $transactionID,
                'purpose' => $purpose,
                'due' => $amount,
                'type' => $type,
                'fine' => 0,
                'created_at' => $current_date,
                'lastFineTime' => $current_date,
                'updated_at' => $current_date
            ]);

        }

        if ($result == true) {

            return response()->json(['message' => 'Hall Fee Assign Successfully'], 200);

        } else {
            return response()->json(['message' => 'Assign Faild Please Try Again Later', 'statusCode' => 404], 404);

        }
    }

    function fineHallFee(Request $request)
    {

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');

        $users = HallFeeModel::where([['due', '>', 0], ['type', '=', 0]])->get();
        $mealResults = OtherModel::first();

        foreach ($users as $user) {

            $date2 = Carbon::createFromFormat('Y-m-d H:i:s', $user['lastFineTime']);
            $compareTime = $date2->diffInMonths($current_date);

            if ($compareTime > 0) {
                for ($i = 0; $i < $compareTime; $i++) {

                    $nextMonth = Carbon::createFromFormat('Y-m-d H:i:s', $user['lastFineTime'])->addMonths($i + 1);

                    $result = HallFeeModel::where('id', $user['id'])->update([
                        'due' => $user['due'] + $mealResults['fine_rate'],
                        'fine' => $user['fine'] + $mealResults['fine_rate'],
                        'lastFineTime' => $nextMonth,
                        'updated_at' => $current_date
                    ]);

                    $result = HallFeeDetailsModel::insert([
                        'hallfeeID' => $user['id'],
                        'money' => $mealResults['fine_rate'],
                        'purpose' => 'Hall Fee Fine',
                        'created_date' => $nextMonth
                    ]);

                }
            }

        }

        if ($result == true) {

            return response()->json(['message' => 'Hall Fee Fine Assign Successfully'], 200);

        } else {
            return response()->json(['message' => 'Assign Faild Please Try Again Later', 'statusCode' => 404], 404);

        }
    }




    function payNow(Request $request)
    {
        $amount = $request->input('amount');
        $id = $request->input('id');
        $transactionID = $this->set_number();

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');

        $users = HallFeeModel::where('id', $id)->first();
        if ($users['due'] == $amount) {
           HallFeeModel::where('id', $id)->update([
                'due' => 0,
                'fine' => 0,
                'updated_at' => $current_date
            ]);

            $result = HallFeeDetailsModel::insert([
                'hallfeeID' => $id,
                'money' => $amount,
                'purpose' => 'Hall Fee Submitted',
                'created_date' => $current_date
            ]);

            TransactionModel::insert(['studentID' => $users['studentID'], 'created_at' => $current_date, "amount" => $amount, 'isIn' => 0, "transactionID" => $transactionID,'purpose'=>"Hall Fee Added"]);

            return response()->json(['message' => 'Pay Successfully', 'statusCode' => 200], 200);

        } else {
            return response()->json(['message' => 'Please Provide full ammount', 'statusCode' => 404], 404);
        }
    }


    public function deleteHallFee(Request $request)
    {
        $id = $request->input('id');
        $result = HallFeeModel::where('id', $id)->delete();
        if ($result == true) {
            HallFeeDetailsModel::where('hallfeeID', $id)->delete();
            return response()->json(['message' => 'Delete successfully.', 'statusCode' => 200])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Failed to Delete Rooms', 'statusCode' => 404])->setStatusCode(404);
        }
    }


    function getUserAllHallFeeByID(Request $request)
    {
        $studentID = $request->input('studentID');

        $result = HallFeeModel::where('studentID', $studentID)->orderby('created_at','desc')->paginate(10);

        if ($result == true) {

            return response()->json($result,200);

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }

    
    function getUserAllSubHallFeeByID(Request $request)
    {
        $id = $request->input('id');

        $result = HallFeeDetailsModel::where('hallfeeID', $id)->orderby('created_date','desc')->paginate(10);

        if ($result == true) {

            return response()->json($result,200);

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }


    function hallFeeSummery(Request $request)
    {
        $studentID = $request->input('studentID');

        $result = HallFeeModel::where('studentID', $studentID)->get();
        

        if ($result == true) {
            $ammount=0;
            foreach ( $result as $resultStudent){
                $ammount=$ammount+$resultStudent['due'];
            }

            return response()->json(["balance"=>$ammount],200);

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }
}