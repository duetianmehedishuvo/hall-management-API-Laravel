<?php

namespace App\Http\Controllers;

use App\Models\BookModel;
use App\Models\BookPurchedModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;

class LibraryController extends Controller
{
    function book(Request $request)
    {
        $title = $request->input('title');
        $author = $request->input('author');
        $category = $request->input('category');
        $price = $request->input('price');
        $isUpdate = $request->input('isUpdate');

        $jj = [
            'title' => $title,
            'category' => $category,
            'price' => $price,
            'author' => $author
        ];

        if ($isUpdate == 1) {
            $id = $request->input('id');
            $result = BookModel::where('id', $id)->update($jj);
        } else {
            $result = BookModel::insert($jj);
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

    public function deleteBook(Request $request)
    {
        $id = $request->input('id');

        $result = BookModel::where('id', $id)->delete();

        if ($result == true) {
            return response()->json(['message' => 'Delete successfully.', 'statusCode' => 200])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Failed to Delete Post', 'statusCode' => 404])->setStatusCode(404);
        }
    }

    function getAllBooks(Request $request)
    {

        $category = $request->input('category');

        if ($category != 'All') {
            $result = BookModel::where('category', $category)->orderBy('id', 'desc')->paginate(10);
        } else {
            $result = BookModel::orderBy('id', 'desc')->paginate(10);
        }

        if ($result == true) {
            return response()->json($result)->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }


    public function searchQueryBook(Request $request): JsonResponse
    {
        $query = $request->input('query');

        $tagResult = DB::table('book_table')
            ->where(DB::raw('lower(title)'), 'LIKE', "%" . strtolower($query) . "%")
            ->orWhere(DB::raw('lower(author)'), 'LIKE', "%" . strtolower($query) . "%")
            ->orWhere(DB::raw('lower(category)'), 'LIKE', "%" . strtolower($query) . "%");

        if ($tagResult->exists()) {
            return response()->json($tagResult
                ->orderBy('id', 'desc')
                ->paginate(10))->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Data Not Found', 'statusCode' => 404])->setStatusCode(404);

        }
    }


    function bookIssue(Request $request)
    {
        $book_id = $request->input('book_id');
        $student_id = $request->input('student_id');
        $isUpdate = $request->input('isUpdate');

        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');


        if ($isUpdate == 1) {
            $id = $request->input('id');
            $result = BookPurchedModel::where('id', $id)->update([
                'updated_at' => $current_date,
                'status' => 2
            ]);
        } else {
            $result = BookPurchedModel::insert([
                'book_id' => $book_id,
                'student_id' => $student_id,
                'created_at' => $current_date,
                'updated_at' => $current_date,
                'status' => 0
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


    function bookPurchedHistoryByStudentID(Request $request)
    {
        $studentID = $request->input('studentID');
        $type = $request->input('type');

        if ($type == 'All') {
            $result = BookPurchedModel::where('student_id', $studentID)->orderBy('updated_at', 'desc')->paginate(10);
        } else if ($type == 'Renew') {
            $result = BookPurchedModel::where(['student_id' => $studentID, 'status' => 0])->orderBy('updated_at', 'desc')->paginate(10);
        } else if ($type == 'Return') {
            $result = BookPurchedModel::where(['student_id' => $studentID, 'status' => 2])->orderBy('updated_at', 'desc')->paginate(10);
        }

        if ($result == true) {
            return response()->json($result)->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }


    function bookPurchedHistoryForAdmin(Request $request)
    {
        $type = $request->input('type');

        if ($type == 'All') {
            $result = BookPurchedModel::where('status', 0)->orWhere('status', 2)->orderBy('updated_at', 'desc')->paginate(10);
        } else if ($type == 'Renew') {
            $result = BookPurchedModel::where([ 'status' => 0])->orderBy('updated_at', 'desc')->paginate(10);
        } else if ($type == 'Return') {
            $result = BookPurchedModel::where(['status' => 2])->orderBy('updated_at', 'desc')->paginate(10);
        }

        if ($result == true) {
            return response()->json($result)->setStatusCode(200);

        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }

}
