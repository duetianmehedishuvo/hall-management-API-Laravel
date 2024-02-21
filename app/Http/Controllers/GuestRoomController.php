<?php

namespace App\Http\Controllers;

use App\Models\GuestRoomModel;
use App\Models\RoomModels;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class GuestRoomController extends Controller
{
    function addGuestRoomBook(Request $request)
    {
        $roomNO = $request->input('roomNO');
        $date = $request->input('date');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');
        $purpose = $request->input('purpose');
        $phoneNo = $request->input('phoneNo');

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');

        $access_token = str_replace('Bearer ', '', $request->header('Authorization'));
        $key = env('TOKEN_KEY');
        $decoded = JWT::decode($access_token, new Key($key, 'HS256'));
        $decoded_array = (array) $decoded;
        $studentID = $decoded_array['studentID'];


        $result = GuestRoomModel::insert([
            'roomNO' => $roomNO,
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'studentID' => $studentID,
            'purpose' => $purpose,
            'phoneNo' => $phoneNo,
            'status' => 0,
            'create_at' => $current_date,
            'updated_at' => $current_date
        ]);

        if ($result == true) {

            return response()->json(['message' => 'Guest Room Book Succesfull Created'])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Guest Room Book added Fail Please Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }


    function acceptRoom(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        $result = GuestRoomModel::where('id',$id)->update(['status' => $status]);

        if ($result == true) {

            return response()->json(['message' => 'Guest Room Succesfull Created'])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Guest Room added Fail Please Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }



    function getMyRoomAssignByID(Request $request)
    {
        $studentID = $request->input('studentID');

        $result = GuestRoomModel::where('studentID', $studentID)->orderby('create_at','desc')->paginate(10);

        if ($result == true) {

            return response()->json($result,200);

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }

    }

    function getAllRoomAssignList(Request $request)
    {
        $searchType = $request->input('searchType'); //0 mean all //1 mean only accepted // 2 mean queue list only
        if($searchType==0){
            $result = GuestRoomModel::orderby('create_at','desc')->paginate(10);
        }else if($searchType==1){
            $result = GuestRoomModel::where('status',2)->orderby('create_at','desc')->paginate(10);
        }else if($searchType==2){
            $result = GuestRoomModel::where('status',0)->orderby('create_at','desc')->paginate(10);
        }

        if ($result == true) {
            return response()->json($result,200);

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }

    }


    public function deleteGuestRoomBook(Request $request)
    {
        $id = $request->input('id');

        $result = GuestRoomModel::where('id',$id)->delete();

        if ($result == true) {
            return response()->json(['message' => 'Delete successfully.', 'statusCode' => 200])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Failed to Delete Rooms', 'statusCode' => 404])->setStatusCode(404);
        }
    }


}
