<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhotoUploadModel;

class PhotoUploadController extends Controller
{
    function create(Request $request)
    {

        $imageUrl=$request->file('image')->getClientOriginalName();
        $request->file('image')->move('images', $imageUrl);

       return response()->json(['imageUrl'=>$imageUrl]);
    }
}
