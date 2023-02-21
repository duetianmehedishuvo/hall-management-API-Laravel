<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Models\RegistrationModel;
// @CrossOrigin(maxAge = 3600)

class LoginController extends Controller
{
    function onLogin(Request $request)
    {
        
        $studentID = $request->input('studentID');
        $password = $request->input('password');
        $userCount = RegistrationModel::where(["studentID" => $studentID, "password" => $password,])->count();
        if ($userCount == 1) {
            $user = RegistrationModel::where(["studentID" => $studentID])->get()->first();
            $key = env('TOKEN_KEY');
            $payload = array(
                "site" => "http://demo.com",
                "studentID" => $studentID,
                "iat" => time(),
                "exp" => time() + 3600*24*15
            );
            $jwt = JWT::encode($payload, $key, 'HS256');
            return response()->json(['message' => ' Login Success','token' => $jwt,  'user' => $user]);
           
                
        } else {
            return response()->json(['message' => 'User not found', 'statusCode' => 404])->setStatusCode(404);

        }
    }


    function logout(Request $request)
    {
        $phone = $request->input('phone');
        $userCount = RegistrationModel::where(["phone" => $phone])->count();
        if ($userCount == 1) {
            return response()->json(['message' => 'Logout Success', 'statusCode' => 200])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'User not found', 'statusCode' => 404])->setStatusCode(404);
        }
    }
   
}
