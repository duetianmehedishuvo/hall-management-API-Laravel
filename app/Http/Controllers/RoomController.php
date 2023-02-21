<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomModels;
use App\Models\RegistrationModel;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    function addRoom(Request $request)
    {
        $roomNo = $request->input('roomNo');
        $studentID = $request->input('studentID');
        $year = $request->input('year');
        $current_date = date('Y-m-d');

        $userCount = RegistrationModel::where('studentID', $studentID)->count();
        if ($userCount != 0) {

            $result = RoomModels::insert([
                'roomNo' => $roomNo,
                'floor' => $roomNo[0],
                'studentID' => $studentID,
                'year' => $year,
                'isAvaible' => 1,
                'updated_at' => $current_date
            ]);

            if ($result == true) {

                return response()->json(['message' => 'Room  Succesfull Created'])->setStatusCode(200);

            } else {
                return response()->json(['message' => 'Registration Fail Please Try Again', 'statusCode' => 404])->setStatusCode(404);

            }

        } else {
            return response()->json(['message' => 'Student Not Found!!!', 'statusCode' => 404])->setStatusCode(404);
        }

    }

    function getAllRooms(Request $request)
    {
        
        return DB::table('roomtable')
            ->leftJoin('studenttable', 'studenttable.studentID', '=', 'roomtable.studentID')
            ->select(
                'roomtable.id',
                'roomtable.isAvaible',
                'roomtable.studentID',
                'roomtable.year',
                'roomtable.updated_at',
                'studenttable.name',
                'studenttable.department'
            )->orderBy('roomtable.id', 'desc')->get();
    }

    function getAllRoomsByYearRoomNo(Request $request)
    {
        $roomNo = $request->input('roomNo');

        $activeStudents= DB::table('roomtable')
            ->leftJoin('studenttable', 'studenttable.studentID', '=', 'roomtable.studentID')
            ->select(
                'roomtable.id',
                'roomtable.isAvaible',
                'roomtable.studentID',
                'roomtable.year',
                'roomtable.updated_at',
                'studenttable.name',
                'studenttable.department'
            )->where(['roomtable.roomNo' => $roomNo, 'roomtable.isAvaible' => 1])->orderBy('roomtable.id', 'desc')->get();

            
        $inactiveStudents= DB::table('roomtable')
        ->leftJoin('studenttable', 'studenttable.studentID', '=', 'roomtable.studentID')
        ->select(
            'roomtable.id',
            'roomtable.isAvaible',
            'roomtable.studentID',
            'roomtable.year',
            'roomtable.updated_at',
            'studenttable.name',
            'studenttable.department'
        )->where(['roomtable.roomNo' => $roomNo, 'roomtable.isAvaible' => 0])->orderBy('roomtable.year', 'desc')->get();

        return response()->json(['activeStudents' => $activeStudents,'inactiveStudents' => $inactiveStudents])->setStatusCode(200);

    }

    
    public function updateRoomStatusByStudentID(Request $request)
    {
        
        $id = $request->input('id');
        $isAvaible = $request->input('isAvaible');

        $result = RoomModels::where('id', $id)->update(['isAvaible' => $isAvaible]);
        if ($result == true) {
            return response()->json(['message' => 'Room Update successfully.', 'statusCode' => 200])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Failed to Update Rooms', 'statusCode' => 404])->setStatusCode(404);
        }
    }


    public function deleteStudentsRoom(Request $request)
    {
        
        $id = $request->input('id');
        $result = RoomModels::where('id', $id)->delete();
        if ($result == true) {
            return response()->json(['message' => 'Delete successfully.', 'statusCode' => 200])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Failed to Delete Rooms', 'statusCode' => 404])->setStatusCode(404);
        }
    }

}
