<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Models\RegistrationModel;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionModel;
use \Carbon\Carbon;
class RegistrationController extends Controller
{

    public function set_number()
    {
        $number = mt_rand(1000000000, 9999999999);
        return TransactionModel::where('transactionID', $number)->exists() ? $this->set_number() : $number;
    }


    function onRegister(Request $request)
    {
        $studentID = $request->input('studentID');
        $name = $request->input('name');
        $department = $request->input('department');
        $phoneNumber = $request->input('phoneNumber');
        $whatssApp = $request->input('whatssApp');
        $email = $request->input('email');
        $bloodGroup = $request->input('bloodGroup');
        $password = $request->input('password');
        $details = $request->input('details');
        $homeTown = $request->input('homeTown');
        $researchArea = $request->input('researchArea');
        $jobPosition = $request->input('jobPosition');
        $futureGoal = $request->input('futureGoal');
        $motive = $request->input('motive');
        $current_date = date('Y-m-d');

        $userCount = RegistrationModel::where('studentID', $studentID)->count();
        if ($userCount == 0) {
            $result = RegistrationModel::insert([
                'studentID' => $studentID,
                'name' => $name,
                'department' => $department,
                'phoneNumber' => $phoneNumber,
                'whatssApp' => $whatssApp,
                'email' => $email,
                'bloodGroup' => $bloodGroup,
                'fingerID' => "",
                'rfID' => "",
                'password' => $password,
                'details' => $details,
                'homeTown' => $homeTown,
                'researchArea' => $researchArea,
                'jobPosition' => $jobPosition,
                'futureGoal' => $futureGoal,
                'motive' => $motive,
                'balance' => 0,
                'role' => 0,
                'create_at' => $current_date,
                'updated_at' => $current_date

            ]);

            if ($result == true) {

                return response()->json(['message' => 'Student Registration Succesfull Complete'])->setStatusCode(200);

            } else {
                return response()->json(['message' => 'Registration Fail Please Try Again', 'statusCode' => 404])->setStatusCode(404);

            }

        } else {
            return response()->json(['message' => 'Student Already Exists!!!', 'statusCode' => 404])->setStatusCode(404);
        }

    }

    function getUserByStudentID(Request $request)
    {
        $studentID = $request->input('studentID');
        $userCount = RegistrationModel::where('studentID', $studentID)->count();
        if ($userCount >= 1) {
            return RegistrationModel::where('studentID', $studentID)->first();
        } else {
            return response()->json(['message' => 'Student not found', 'statusCode' => 404])->setStatusCode(404);
        }
    }

    function updateUser(Request $request)
    {

        $studentID = $request->input('studentID');
        $name = $request->input('name');
        $department = $request->input('department');
        $phoneNumber = $request->input('phoneNumber');
        $whatssApp = $request->input('whatssApp');
        $email = $request->input('email');
        $bloodGroup = $request->input('bloodGroup');
        $details = $request->input('details');
        $homeTown = $request->input('homeTown');
        $researchArea = $request->input('researchArea');
        $jobPosition = $request->input('jobPosition');
        $futureGoal = $request->input('futureGoal');
        $motive = $request->input('motive');
        $current_date = date('Y-m-d');


        $userCount = RegistrationModel::where('studentID', $studentID)->count();
        if ($userCount >= 1) {
            $result = RegistrationModel::where('studentID', $studentID)->update([
                'name' => $name,
                'department' => $department,
                'phoneNumber' => $phoneNumber,
                'whatssApp' => $whatssApp,
                'email' => $email,
                'bloodGroup' => $bloodGroup,
                'details' => $details,
                'homeTown' => $homeTown,
                'researchArea' => $researchArea,
                'jobPosition' => $jobPosition,
                'futureGoal' => $futureGoal,
                'motive' => $motive,
                'updated_at' => $current_date
            ]);
            if ($result == true) {
                return response()->json(['message' => 'User Update successfull.', 'statusCode' => 200])->setStatusCode(200);
            } else {
                return response()->json(['message' => 'User not Updated', 'statusCode' => 404])->setStatusCode(404);
            }
        } else {
            return response()->json(['message' => 'User not found', 'statusCode' => 404])->setStatusCode(404);
        }
    }

    function updateFingerRFID(Request $request)
    {

        $access_token = str_replace('Bearer ', '', $request->header('Authorization'));
        $key = env('TOKEN_KEY');
        $decoded = JWT::decode($access_token, new Key($key, 'HS256'));
        $decoded_array = (array) $decoded;
        $studentID = $decoded_array['studentID'];

        $fingerRfID = $request->input('fingerRfID');
        $isFinger = $request->input('isFinger');
        if ($isFinger == 0) {
            $keyOff = "fingerID";
        } else {
            $keyOff = "rfID";
        }

        $userCount = RegistrationModel::where('studentID', $studentID)->count();
        if ($userCount >= 1) {
            $result = RegistrationModel::where('studentID', $studentID)->update([$keyOff => $fingerRfID]);
            if ($result == true) {
                return response()->json(['message' => 'Update successfull.', 'statusCode' => 200])->setStatusCode(200);
            } else {
                return response()->json(['message' => 'Finger or RfID not Updated', 'statusCode' => 404])->setStatusCode(404);
            }
        } else {
            return response()->json(['message' => 'User not found', 'statusCode' => 404])->setStatusCode(404);
        }
    }



    function resetPassword(Request $request)
    {

        $access_token = str_replace('Bearer ', '', $request->header('Authorization'));
        $key = env('TOKEN_KEY');
        $decoded = JWT::decode($access_token, new Key($key, 'HS256'));
        $decoded_array = (array) $decoded;
        $studentID = $decoded_array['studentID'];

        $password = $request->input('password');
        $newPassword = $request->input('newPassword');


        $userCount = RegistrationModel::where(['studentID' => $studentID, 'password' => $password])->count();
        if ($userCount >= 1) {
            $result = RegistrationModel::where('studentID', $studentID)->update(['password' => $newPassword]);
            if ($result == true) {
                return response()->json(['message' => 'Update successfull.', 'statusCode' => 200])->setStatusCode(200);
            } else {
                return response()->json(['message' => 'Failed', 'statusCode' => 404])->setStatusCode(404);
            }
        } else {
            return response()->json(['message' => 'Password Not Updated', 'statusCode' => 404])->setStatusCode(404);
        }
    }




    public function updateStudentBalance(Request $request)
    {
        $balance = $request->input('balance');
        $studentID = $request->input('studentID');
        $isAddition = $request->input('isAddition');
        $transactionID = $this->set_number();

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');

        $value = RegistrationModel::where('studentID', $studentID)->get();

        if ($isAddition == 0) {
            $newBalance = $value[0]['balance'] + $balance;
            TransactionModel::insert(['studentID' => $studentID, 'created_at' => $current_date, "amount" => $balance, 'isIn' => 1, "transactionID" => $transactionID,'purpose'=>"Balance Created"]);
        } else {
            $newBalance = $value[0]['balance'] - $balance;
            TransactionModel::insert(['studentID' => $studentID, 'created_at' => $current_date, "amount" => $balance, 'isIn' => 0, "transactionID" => $transactionID,'purpose'=>"Balance Removed"]);
        }

        $result = RegistrationModel::where('studentID', $studentID)->update(['balance' => $newBalance]);
        if ($result == true) {
            return response()->json(['message' => 'Balance Update successfull.', 'statusCode' => 200])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Failed to Update Balance', 'statusCode' => 404])->setStatusCode(404);
        }
    }


    public function shareBalance(Request $request)
    {
        $balance = $request->input('balance');
        $fromStudentID = $request->input('fromStudentID');
        $toStudentID = $request->input('toStudentID');
        
        $transactionID = $this->set_number();

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');

        $value = RegistrationModel::where('studentID', $fromStudentID)->first();
        $value1 = RegistrationModel::where('studentID', $toStudentID)->first();

        $newBalance = $value['balance'] - $balance;
        $newBalance1 = $value1['balance'] + $balance;
            TransactionModel::insert(['studentID' => $fromStudentID, 'created_at' => $current_date, "amount" => $balance, 'isIn' => 0, "transactionID" => $transactionID,'purpose'=>"Balance Share to ".$toStudentID]);
            $transactionID = $this->set_number();
            TransactionModel::insert(['studentID' => $toStudentID, 'created_at' => $current_date, "amount" => $balance, 'isIn' => 1, "transactionID" => $transactionID,'purpose'=>"Balance Added from ".$fromStudentID]);

        $result = RegistrationModel::where('studentID', $toStudentID)->update(['balance' => $newBalance]);
        $result = RegistrationModel::where('studentID', $fromStudentID)->update(['balance' => $newBalance1]);
        if ($result == true) {
            return response()->json(['message' => 'Balance Share successfully Created.', 'statusCode' => 200])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Failed to Share Your Balance', 'statusCode' => 404])->setStatusCode(404);
        }
    }



    function getUserBalance(Request $request)
    {
        $studentID = $request->input('studentID');

        $value = RegistrationModel::where('studentID', $studentID)->get();
        if ($value == true) {
            return response()->json(['balance' => $value[0]['balance'], 'statusCode' => 200])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Failed to Update Balance', 'statusCode' => 404])->setStatusCode(404);
        }

    }


    function getUserProfile(Request $request)
    {
        $access_token = str_replace('Bearer ', '', $request->header('Authorization'));
        $key = env('TOKEN_KEY');
        $decoded = JWT::decode($access_token, new Key($key, 'HS256'));
        $decoded_array = (array) $decoded;
        $studentID = $decoded_array['studentID'];
        $userCount = RegistrationModel::where('studentID', $studentID)->count();
        if ($userCount >= 1) {
            return RegistrationModel::where('studentID', $studentID)->first();
        } else {
            return response()->json(['message' => 'User not found', 'statusCode' => 404])->setStatusCode(404);
        }
    }


    function countTotalCustomer(Request $request)
    {
        $userCount = RegistrationModel::count();
        return response()->json(['total_students' => $userCount])->setStatusCode(200);
    }

    function getAllCustomer(Request $request)
    {
        return DB::table('studenttable')
            ->select(
                'studenttable.studentID',
                'studenttable.name',
                'studenttable.department'
            )->orderBy('studenttable.id', 'desc')->get();

    }


    function searchStudent(Request $request)
    {
        $query = $request->input('query');

        return DB::table('studenttable')->select(
            'studentID',
            'name',
            'department')
            ->where('studentID', 'like', '%' . $query . '%')
            ->orWhere('name', 'like', '%' . $query . '%')
            ->orWhere('department', 'like', '%' . $query . '%')
            ->orWhere('homeTown', 'like', '%' . $query . '%')
            ->get();
    }

}