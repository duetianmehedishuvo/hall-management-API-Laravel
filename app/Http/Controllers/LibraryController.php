<?php

namespace App\Http\Controllers;

use App\Models\BookModel;
use App\Models\BookPurchedModel;
use App\Models\CardHelperModel;
use App\Models\RegistrationModel;
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

    function bookPurchedHistory(Request $request)
    {
        $studentID = $request->input('studentID');
        $type = $request->input('type');
        $isAdmin = $request->input('isAdmin');

        $selectColumns = ['book_purched_table.id',
            'book_purched_table.book_id',
            'book_purched_table.student_id',
            'book_purched_table.student_id',
            'book_purched_table.updated_at',
            'book_purched_table.created_at',
            'book_purched_table.status',
            'book_table.title',
            'book_table.author',
            'book_table.author',
            'book_table.price',
            'book_table.category'];

        $result = DB::table('book_purched_table')
            ->leftJoin('book_table', 'book_table.id', '=', 'book_purched_table.book_id')
            ->select($selectColumns);

        if ($type == 'All') {
            if ($isAdmin == 1)
                $result = $result->where('book_purched_table.status', 0)->orWhere('book_purched_table.status', 2);
            else
                $result = $result->where(['book_purched_table.student_id' => $studentID]);


        } else if ($type == 'Renew') {
            if ($isAdmin == 1)
                $result = $result->where('book_purched_table.status', 0);
            else
                $result = $result->where(['book_purched_table.student_id' => $studentID, 'status' => 0]);


        } else if ($type == 'Return') {
            if ($isAdmin == 1)
                $result = $result->where('book_purched_table.status', 2);
            else
                $result = $result->where(['book_purched_table.student_id' => $studentID, 'status' => 2]);
        }

        $result = $result->orderBy('book_purched_table.updated_at', 'desc')->paginate(10);

        if ($result == true) {
            return response()->json($result)->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }


    function bookHistory(Request $request)
    {
        $bookID = $request->input('bookID');


        $selectColumns = ['book_purched_table.id',
            'book_purched_table.book_id',
            'book_purched_table.student_id',
            'studenttable.name',
            'studenttable.department'
            ];

        $result = DB::table('book_purched_table')
            ->leftJoin('studenttable', 'studenttable.studentID', '=', 'book_purched_table.student_id')
            ->select($selectColumns)->where('book_purched_table.book_id', $bookID)
            ->orderBy('book_purched_table.updated_at', 'desc')->paginate(10);

        if ($result == true) {
            return response()->json($result)->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }



    function cardIssue(Request $request)
    {
        $card_id = $request->input('card_id');
        $for_registrtion = $request->input('for_registrtion');

        $card_result = CardHelperModel::get();

        if ($card_result != null) {
            CardHelperModel::truncate();
        }
        if($for_registrtion!==0){
            $resultInsert = CardHelperModel::insert([
                'student_id' => 0,
                'card_id' => $card_id]);

            if ($resultInsert != 0) {
                return response()->json(['message' => 'Card Valid for all student', 'code' => 1])->setStatusCode(200);
            } else {
                return response()->json(['message' => 'Fail Please Try Again Later', 'statusCode' => 404, 'code' => 0])->setStatusCode(404);
            }
        }else{
            $result = RegistrationModel::where('rfID', $card_id)->get();

            if (count($result) != 0) {
                $resultInsert = CardHelperModel::insert([
                    'student_id' => $result[0]['studentID'],
                    'card_id' => $card_id]);

                if ($resultInsert != 0) {
                    return response()->json(['message' => 'Card Valid', 'code' => 1])->setStatusCode(200);
                } else {
                    return response()->json(['message' => 'Fail Please Try Again Later', 'statusCode' => 404, 'code' => 0])->setStatusCode(404);
                }

            } else {
                return response()->json(['message' => 'Card Not Valid', 'statusCode' => 404, 'code' => 0])->setStatusCode(404);
            }
        }

    }

    function deleteAllCard(Request $request)
    {
        $card_result = CardHelperModel::get();
        $result = 1;
        if (count($card_result) != 0) {
            $resultStatus = CardHelperModel::whereNotNull('id')->delete();
            $result = $resultStatus;
        }
        if ($result == 1) {
            return response()->json(['message' => 'Delete Successful', 'code' => 1])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Fail Please Try Again Later', 'statusCode' => 404, 'code' => 0])->setStatusCode(404);
        }
    }

    function checkCardIssue(Request $request)
    {
        $card_result = CardHelperModel::get();
        if (count($card_result) != 0) {
            return response()->json($card_result[0])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Trying to finding card data', 'statusCode' => 404])->setStatusCode(404);
        }

    }

}
