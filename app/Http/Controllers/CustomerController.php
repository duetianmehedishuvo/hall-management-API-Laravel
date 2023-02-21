<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerModel;

class CustomerController extends Controller
{
    
    function allCustomers(Request $request)
    {
        return CustomerModel::all();
    }

    function addCustomer(Request $request)
    {

        $customer_id = $request->input('customer_id');
        $name = $request->input('name');
        $address = $request->input('address');
        $count=CustomerModel::where(["customer_id" => $customer_id])->count();
        if($count==0){
            $result = CustomerModel::insert([
                'customer_id' => $customer_id,
                'name' => $name,
                'address' => $address,
            ]);
            if ($result == true) {
                return response()->json(['message' => 'Customer Add Success ', 'statusCode' => 200])->setStatusCode(200);
                
            } else {
                return response()->json(['message' => 'Customer add Failed', 'statusCode' => 404])->setStatusCode(404);
               
            }
        }else{
            return response()->json(['message' => 'Customer already exists', 'statusCode' => 404])->setStatusCode(404);
        }
    }

    function getCustomerByID(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $meterCount = CustomerModel::where('customer_id', $customer_id)->count();
        if ($meterCount >= 1) {
            return CustomerModel::where('customer_id', $customer_id)->first();
        } else {
            return response()->json(['message' => 'Customer not found', 'statusCode' => 404])->setStatusCode(404);
        }
    }

    function updateCustomer(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $address = $request->input('address');
        $name = $request->input('name');
        $meterCount = CustomerModel::where('customer_id', $customer_id)->count();
        if ($meterCount >= 1) {
            $result = CustomerModel::where('customer_id', $customer_id)->update([
                'address' => $address,
                'name' => $name
            ]);
            if ($result == true) {
                return response()->json(['message' => 'Update Success', 'statusCode' => 200])->setStatusCode(200);
                
            } else {
                return response()->json(['message' => 'Fail ! Try Again', 'statusCode' => 404])->setStatusCode(404);
                
            }
        } else {
            return response()->json(['message' => 'Customer not found', 'statusCode' => 404])->setStatusCode(404);
        }
    }

    function deleteCustomer(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $meterCount = CustomerModel::where('customer_id', $customer_id)->count();
        if ($meterCount >= 1) {
            $result = CustomerModel::where('customer_id', $customer_id)->delete();
            if ($result == true) {
                return response()->json(['message' => 'Delete Success', 'statusCode' => 200])->setStatusCode(200);
                
            } else {
                return response()->json(['message' => 'Fail ! Try Again', 'statusCode' => 404])->setStatusCode(404);
                
            }
        } else {
            return response()->json(['message' => 'Customer not found', 'statusCode' => 404])->setStatusCode(404);
        }
    }
}
