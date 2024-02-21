<?php

namespace App\Http\Controllers;

use App\Models\CommunityModel;
use App\Models\CommendModel;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;

class CommunityController extends Controller
{
    function post(Request $request)
    {
        $details = $request->input('details');
        $isUpdate = $request->input('isUpdate');

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');


        if ($isUpdate == 1) {
            $id = $request->input('id');
            $result = CommunityModel::where('id', $id)->update([
                'details' => $details,
                'updated_at' => $current_date
            ]);
        } else {

            $access_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $key = env('TOKEN_KEY');
            $decoded = JWT::decode($access_token, new Key($key, 'HS256'));
            $decoded_array = (array)$decoded;
            $studentID = $decoded_array['studentID'];


            $result = CommunityModel::insert([
                'student_id' => $studentID,
                'details' => $details,
                'created_at' => $current_date,
                'updated_at' => $current_date
            ]);
        }

        if ($result == true) {

            if ($isUpdate == 1) {
                return response()->json(['message' => 'Updated Successfully'])->setStatusCode(200);

            } else {
                return response()->json(['message' => 'Added Successfully'])->setStatusCode(200);

            }

        } else {
            return response()->json(['message' => 'Fail Please Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }

    public function deletePost(Request $request)
    {
        $id = $request->input('id');

        $result = CommunityModel::where('id', $id)->delete();

        if ($result == true) {
            return response()->json(['message' => 'Delete successfully.', 'statusCode' => 200])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Failed to Delete Post', 'statusCode' => 404])->setStatusCode(404);
        }
    }

    function getAllPost(Request $request)
    {

        $result = DB::table('community_table')
            ->leftJoin('studenttable', 'studenttable.studentID', '=', 'community_table.student_id')
            ->leftJoin('commend_table', 'commend_table.community_id', '=', 'community_table.id')
            ->select(
                'community_table.id',
                'community_table.student_id',
                'community_table.details',
                'community_table.updated_at',
                'studenttable.name',
                'studenttable.department',
                DB::raw('COUNT(commend_table.community_id) as comment_count'),
            )
            ->groupBy('community_table.id',
                'community_table.student_id',
                'community_table.details',
                'community_table.updated_at',
                'studenttable.name',
                'studenttable.department')
            ->orderBy('community_table.updated_at', 'desc')->paginate(10);

        if ($result == true) {

            return response()->json($result)->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }

    }

    function getAllPostByStudentID(Request $request)
    {
        $access_token = str_replace('Bearer ', '', $request->header('Authorization'));
        $key = env('TOKEN_KEY');
        $decoded = JWT::decode($access_token, new Key($key, 'HS256'));
        $decoded_array = (array)$decoded;
        $studentID = $decoded_array['studentID'];


        $result = DB::table('community_table')
            ->leftJoin('studenttable', 'studenttable.studentID', '=', 'community_table.student_id')
            ->leftJoin('commend_table', 'commend_table.community_id', '=', 'community_table.id')
            ->select(
                'community_table.id',
                'community_table.student_id',
                'community_table.details',
                'community_table.updated_at',
                'studenttable.name',
                'studenttable.department',
                DB::raw('COUNT(commend_table.community_id) as comment_count'),
            )->where(['community_table.student_id' => $studentID])
            ->groupBy('community_table.id',
                'community_table.student_id',
                'community_table.details',
                'community_table.updated_at',
                'studenttable.name',
                'studenttable.department')
            ->orderBy('community_table.updated_at', 'desc')->paginate(10);

        if ($result == true) {

            return response()->json($result)->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }

    }


    function comment(Request $request)
    {
        $communityId = $request->input('community_id');
        $comment = $request->input('comment');
        $isUpdate = $request->input('isUpdate');

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');


        if ($isUpdate == 1) {
            $id = $request->input('id');
            $result = CommendModel::where('id', $id)->update([
                'comment' => $comment,
                'created_at' => $current_date
            ]);
        } else {

            $access_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $key = env('TOKEN_KEY');
            $decoded = JWT::decode($access_token, new Key($key, 'HS256'));
            $decoded_array = (array)$decoded;
            $studentID = $decoded_array['studentID'];

            $result = CommendModel::insert([
                'community_id' => $communityId,
                'student_id' => $studentID,
                'comment' => $comment,
                'created_at' => $current_date,
            ]);
        }

        if ($result == true) {

            if ($isUpdate == 1) {
                return response()->json(['message' => 'Commend Updated Successfully'])->setStatusCode(200);

            } else {
                return response()->json(['message' => 'Commend Added Successfully'])->setStatusCode(200);

            }

        } else {
            return response()->json(['message' => 'Fail Please Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }

    public function deleteCommend(Request $request)
    {
        $id = $request->input('id');

        $result = CommendModel::where('id', $id)->delete();

        if ($result == true) {
            return response()->json(['message' => 'Commend Delete successfully.', 'statusCode' => 200])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Commend Failed to Delete', 'statusCode' => 404])->setStatusCode(404);
        }
    }

    function getAllPostCommend(Request $request)
    {

        $community_id = $request->input('community_id');


        $access_token = str_replace('Bearer ', '', $request->header('Authorization'));
        $key = env('TOKEN_KEY');
        $decoded = JWT::decode($access_token, new Key($key, 'HS256'));
        $decoded_array = (array)$decoded;
        $studentID = $decoded_array['studentID'];

//        community_id	student_id	comment	created_at

        $result = DB::table('commend_table')
            ->leftJoin('studenttable', 'studenttable.studentID', '=', 'commend_table.student_id')
            ->select(
                'commend_table.id',
                'commend_table.community_id',
                'commend_table.student_id',
                'commend_table.comment',
                'commend_table.created_at',
                'studenttable.name',
                'studenttable.department'
            )->where(['commend_table.student_id' => $studentID, 'commend_table.community_id' => $community_id])->orderBy('commend_table.created_at', 'desc')->paginate(10);

        if ($result == true) {

            return response()->json($result)->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }

    }


}
