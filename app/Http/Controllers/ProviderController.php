<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProviderModel;

class ProviderController extends Controller
{
    function addProvider(Request $request)
    {

        $type = $request->input('type');
        $name = $request->input('name');
        $code = $request->input('code');
        $api_code = $request->input('api_code');
        $providerCommition = $request->input('providerCommition');
        $customerCommition = $request->input('customerCommition');
        $fileData = $request->file('image');
        $reportNo = $request->input('reportNo');
        $reportImage = '';

        if ($fileData != null) {
            $imageUrl = $reportNo . '.' . $fileData->extension();
            $fileData->move('images', $imageUrl);
            $reportImage = $imageUrl;
        } else {
            $reportImage = 'no-image-found.jpg';
        }

        $result = ProviderModel::insert([
            'type' => $type,
            'name' => $name,
            'code' => $code,
            'api_code' => $api_code,
            'providerCommition' => $providerCommition,
            'customerCommition' => $customerCommition,
            'image' => $reportImage

        ]);

        if ($result == true) {
            return response()->json(['message' => 'Provider Added Successfully', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Provider Added Failed', 'statusCode' => 404])->setStatusCode(404);

        }
    }


    function updateProvider(Request $request)
    {

        $id = $request->input('id');
        $type = $request->input('type');
        $name = $request->input('name');
        $code = $request->input('code');
        $api_code = $request->input('api_code');
        $providerCommition = $request->input('providerCommition');
        $customerCommition = $request->input('customerCommition');
        $fileData = $request->file('image');
        $reportNo = $request->input('reportNo');
        $reportImage = '';

        if ($fileData != null) {

            $imageUrl = $reportNo . '.' . $fileData->extension();
            $fileData->move('images', $imageUrl);
            $reportImage = $imageUrl;

            $result = ProviderModel::where(['id' => $id])->update([
                'type' => $type,
                'name' => $name,
                'code' => $code,
                'api_code' => $api_code,
                'providerCommition' => $providerCommition,
                'customerCommition' => $customerCommition,
                'image' => $reportImage
            ]);
        } else {

            $result = ProviderModel::where(['id' => $id])->update([
                'type' => $type,
                'name' => $name,
                'code' => $code,
                'api_code' => $api_code,
                'providerCommition' => $providerCommition,
                'customerCommition' => $customerCommition
            ]);
        }

        if ($result == true) {
            return response()->json(['message' => 'Provider Updated Successfully', 'statusCode' => 200])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Provider Updated Failed, Please Change Somethings', 'statusCode' => 404])->setStatusCode(404);

        }
    }


    function deleteProvider(Request $request)
    {

        $id = $request->input('id');

        $result = ProviderModel::where(['id' => $id])->delete();
        if ($result == true) {
            return response()->json(['message' => 'Provider Deleted Successfully', 'statusCode' => 200])->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Provider Deleted Failed', 'statusCode' => 404])->setStatusCode(404);

        }
    }
    function getAllProvider(Request $request)
    { 
        return ProviderModel::all();
    }

    function filterProvider(Request $request)
    {

        $filterStatus = $request->input('filterStatus');
        
        $type = $filterStatus==0 ? "mobile" : "DTH";

        $result = ProviderModel::where(['type' => $type])->get();
        
        if ($result == true) {
            
            return $result;

        } else {
            return response()->json(['message' => 'Provider Deleted Failed', 'statusCode' => 404])->setStatusCode(404);

        }
    }

}