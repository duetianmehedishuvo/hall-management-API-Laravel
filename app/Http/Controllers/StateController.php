<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StateModel;

class StateController extends Controller
{
    function addState(Request $request)
    {
        $name = $request->input('name');
        $code = $request->input('code');

        $result = StateModel::insert(['name' => $name, 'code' => $code]);

        if ($result == true) {
            return response()->json(['message' => 'State Added Successfully', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'State Added Failed', 'statusCode' => 404])->setStatusCode(404);
        }

    }

    function getState(Request $request)
    {
        return StateModel::all();
    }

    function deleteState(Request $request)
    {
        $id = $request->input('id');
        $result = StateModel::where('id', $id)->delete();
        if ($result == true) {
            return response()->json(['message' => 'Delete Success', 'statusCode' => 200])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Fail ! Try Again', 'statusCode' => 404])->setStatusCode(404);

        }
    }

    function updateState(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $code = $request->input('code');
        $result = StateModel::where('id', $id)->update(['name' => $name, 'code' => $code]);

        if ($result == true) {
            return response()->json(['message' => 'Update Successfully', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Fail ! Try Again', 'statusCode' => 404])->setStatusCode(404);

        }
    }
}