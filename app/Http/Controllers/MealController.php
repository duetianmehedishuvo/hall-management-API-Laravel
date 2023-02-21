<?php

namespace App\Http\Controllers;

use App\Models\OtherModel;
use App\Models\RoomModels;
use Illuminate\Http\Request;
use App\Models\MealModel;
use App\Models\TransactionModel;
use App\Models\RegistrationModel;
use \Carbon\Carbon;

class MealController extends Controller
{

    public function set_number()
    {
        $number = mt_rand(1000000000, 9999999999);
        return TransactionModel::where('transactionID', $number)->exists() ? $this->set_number() : $number;
    }


    function addMeal(Request $request)
    {
        $studentID = $request->input('studentID');
        $time = $request->input('time');
        $transactionID = $this->set_number();

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');

        $userCount = RoomModels::where(['studentID' => $studentID, 'isAvaible' => 1])->count();
        if ($userCount != 0) {

            $mealResults = OtherModel::first();
            $customerResults = RegistrationModel::where(['studentID' => $studentID])->first();

            if ($mealResults['meal_rate'] <= $customerResults['balance']) {

                $mealCount = MealModel::where(['studentID' => $studentID, 'created_at' => $time])->count();
                if ($mealCount != 0) {
                    $mealCount1 = MealModel::where(['studentID' => $studentID, 'created_at' => $time, 'total_meal' => 0])->count();
                    if ($mealCount1 != 0) {
                        $mealResults = MealModel::where(['studentID' => $studentID, 'created_at' => $time])->first();
                        $result = MealModel::where('id', $mealResults['id'])->update(['total_meal' => 1, 'updated_at' => $time]);
                    } else {
                        return response()->json(['message' => 'You Already added meal in this days', 'statusCode' => 404])->setStatusCode(404);
                    }

                } else {
                    $result = MealModel::insert(['studentID' => $studentID, 'total_meal' => 1, 'created_at' => $time, 'updated_at' => $time]);
                }

                if ($result == true) {
                    TransactionModel::insert(['studentID' => $studentID, 'created_at' => $current_date, "amount" => $mealResults['meal_rate'], 'isIn' => 1, "transactionID" => $transactionID,'purpose'=>"Meal Added"]);
                    $updateBalance = $customerResults['balance'] - $mealResults['meal_rate'];
                    RegistrationModel::where(['studentID' => $studentID])->update(['balance' => $updateBalance]);

                    return response()->json(['message' => 'Meal Added Successfully '])->setStatusCode(200);

                } else {
                    return response()->json(['message' => 'Meal Added Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

                }

            } else {
                return response()->json(['message' => 'Insufficient balance!! Please Upgrade your Balance', 'statusCode' => 404])->setStatusCode(404);
            }
        } else {
            return response()->json(['message' => 'Sorry you are not elegable for added meal,Thanks', 'statusCode' => 404])->setStatusCode(404);
        }
    }


    function getAllMealByStudentID(Request $request)
    {
        $studentID = $request->input('studentID');

        $result = MealModel::where('studentID', $studentID)->get();

        if ($result == true) {

            return response()->json($result)->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }

    }


    function deleteMealByID(Request $request)
    {
        $created_at = $request->input('created_at');
        $transactionID = $this->set_number();

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');

        $resultCount = MealModel::where('created_at', $created_at)->count();
        if ($resultCount != 0) {

            $resultData = MealModel::where('created_at', $created_at)->first();
            $result = MealModel::where('created_at', $created_at)->delete();

            if ($result == true) {
                $mealResults = OtherModel::first();
                $customerResults = RegistrationModel::where(['studentID' => $resultData['studentID']])->first();
                $sum=$mealResults['meal_rate'];
                
                if($resultData['total_meal']==2){
                    $sum = $sum+ $mealResults['guest_meal_rate'];
                }
                TransactionModel::insert(['studentID' => $resultData['studentID'], 'created_at' => $current_date, "amount" => $sum, 'isIn' => 0, "transactionID" => $transactionID,'purpose'=>"Meal Cancel"]);
                $updateBalance = $customerResults['balance'] + $sum;

                RegistrationModel::where(['studentID' => $resultData['studentID']])->update(['balance' => $updateBalance]);

                return response()->json(['message' => 'Meal Delete Successfully', 'statusCode' => 200])->setStatusCode(200);

            }

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }



    function addGuestMeal(Request $request)
    {
        $studentID = $request->input('studentID');
        $created_at = $request->input('created_at');
        $transactionID = $this->set_number();

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');

        $userCount = RoomModels::where(['studentID' => $studentID, 'isAvaible' => 1])->count();
        if ($userCount != 0) {

            $mealResults = OtherModel::first();
            $customerResults = RegistrationModel::where(['studentID' => $studentID])->first();

            if ($mealResults['guest_meal_rate'] <= $customerResults['balance']) {

                $mealTempDataBeforeUpdate = MealModel::where('created_at', $created_at)->first();

                $result = MealModel::where('created_at', $created_at)->update(['total_meal' => 2, 'updated_at' => $current_date]);

                if ($result == true) {
                    if ($mealTempDataBeforeUpdate['total_meal'] != 2) {
                        TransactionModel::insert(['studentID' => $studentID, 'created_at' => $current_date, "amount" => $mealResults['guest_meal_rate'], 'isIn' => 1, "transactionID" => $transactionID,'purpose'=>"Guest Meal Added"]);
                        $updateBalance = $customerResults['balance'] - $mealResults['guest_meal_rate'];
                        RegistrationModel::where(['studentID' => $studentID])->update(['balance' => $updateBalance]);

                    }

                    return response()->json(['message' => 'Guest Meal Added Successfully '])->setStatusCode(200);

                } else {
                    return response()->json(['message' => 'Guest Meal Added Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

                }

            } else {
                return response()->json(['message' => 'Insufficient balance!! Please Upgrade your Balance', 'statusCode' => 404])->setStatusCode(404);
            }
        } else {
            return response()->json(['message' => 'Sorry you are not elegable for added meal,Thanks', 'statusCode' => 404])->setStatusCode(404);
        }
    }



    function deleteGuestMealByID(Request $request)
    {
        $created_at = $request->input('created_at');
        $transactionID = $this->set_number();

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');

        $resultCount = MealModel::where('created_at', $created_at)->count();
        if ($resultCount != 0) {

            $resultData = MealModel::where('created_at', $created_at)->first();
            $result = MealModel::where('created_at', $created_at)->update(['total_meal' => 1]);

            if ($result == true) {
                $mealResults = OtherModel::first();
                $customerResults = RegistrationModel::where(['studentID' => $resultData['studentID']])->first();

                TransactionModel::insert(['studentID' => $resultData['studentID'], 'created_at' => $current_date, "amount" => $mealResults['guest_meal_rate'], 'isIn' => 0, "transactionID" => $transactionID,'purpose'=>"Gust Meal Removed"]);
                $updateBalance = $customerResults['balance'] + $mealResults['guest_meal_rate'] ;
                RegistrationModel::where(['studentID' => $resultData['studentID']])->update(['balance' => $updateBalance]);

                return response()->json(['message' => 'Guest Meal Remove Successfully', 'statusCode' => 200])->setStatusCode(200);

            }

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }



}