<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComplainModel;
use \Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class ComplainController extends Controller
{
    function addComplain(Request $request)
    {
        $subject = $request->input('subject');
        $complain = $request->input('complain');      

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');

        $access_token = str_replace('Bearer ', '', $request->header('Authorization'));
        $key = env('TOKEN_KEY');
        $decoded = JWT::decode($access_token, new Key($key, 'HS256'));
        $decoded_array = (array) $decoded;
        $studentID = $decoded_array['studentID'];


        $result = ComplainModel::insert([
            'subject' => $subject,
            'complain' => $complain,
            'studentID' => $studentID,
            'reply' => '',
            'is_solved' => 0,
            'create_at' => $current_date,
            'updated_at' => $current_date
        ]);

        if ($result == true) {

            return response()->json(['message' => 'Complain  Succesfull Created'])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Complain added Fail Please Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }


    function replyComplain(Request $request)
    {
        $id = $request->input('id');
        $reply = $request->input('reply');      

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');

        $result = ComplainModel::where('id',$id)->update([
            'reply' => $reply,
            'is_solved' => 1,
            'updated_at' => $current_date
        ]);

        if ($result == true) {

            return response()->json(['message' => 'Complain  Succesfull Created'])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Complain added Fail Please Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }

    public function deleteComplain(Request $request)
    {
        $id = $request->input('id');
        $result = ComplainModel::where('id', $id)->delete();
        if ($result == true) {
            return response()->json(['message' => 'Delete successfully.', 'statusCode' => 200])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Failed to Delete Complain', 'statusCode' => 404])->setStatusCode(404);
        }
    }


    
    function editComplain(Request $request)
    {
        $id = $request->input('id');
        $subject = $request->input('subject');
        $complain = $request->input('complain');       

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');

        $checkValidity=ComplainModel::where('id',$id)->first();
        if($checkValidity['is_solved']==0){

            $result = ComplainModel::where('id',$id)->update([
                'subject' => $subject,
                'complain' => $complain,
                'updated_at' => $current_date
            ]);
    
            if ($result == true) {
    
                return response()->json(['message' => 'Complain  Update Successfully'])->setStatusCode(200);
    
            } else {
                return response()->json(['message' => 'Complain added Fail Please Try Again Later', 'statusCode' => 404])->setStatusCode(404);
    
            }


        }else{
            return response()->json(['message' => 'You can\'t update your complain because this issue already solved by admin.', 'statusCode' => 404])->setStatusCode(404);
        }

       
    }


    function getUserAllComplainByID(Request $request)
    {
        $studentID = $request->input('studentID');

        $result = ComplainModel::where('studentID', $studentID)->orderby('create_at','desc')->paginate(10);

        if ($result == true) {

            return response()->json($result,200);

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }

    }

}
