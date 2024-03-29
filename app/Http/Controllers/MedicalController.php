<?php

namespace App\Http\Controllers;

use App\Models\MedicalServiceModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalController extends Controller
{
    function addMedicalService(Request $request)
    {
        $student_id = $request->input('student_id');
        $service_type = $request->input('service_type');
        $provider_name = $request->input('provider_name');
        $details = $request->input('details');
        $isUpdate = $request->input('isUpdate');
        $date = Carbon::now("Asia/Dhaka");
        $current_date = $date->format('Y-m-d H:i:s');
        $document_url = '';

        if ($request->hasfile('document')) {
            $imageUrl = Carbon::now("Asia/Dhaka")->timestamp . '.' . $request->file('document')->getClientOriginalExtension();
            $request->file('document')->move('medical_image', $imageUrl);
            $document_url = $imageUrl;
        }

        $jj = [
            'student_id' => $student_id,
            'service_type' => $service_type,
            'provider_name' => $provider_name,
            'details' => $details,
            'created_at' => $current_date,
            'docuemnt_url' => $document_url
        ];

        if ($isUpdate == 1) {
            $id = $request->input('id');
            $result = MedicalServiceModel::where('id', $id)->update($jj);
        } else {
            $result = MedicalServiceModel::insert($jj);
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

    public function deleteMedicalService(Request $request)
    {
        $id = $request->input('id');

        $result = MedicalServiceModel::where('id', $id)->delete();

        if ($result == true) {
            return response()->json(['message' => 'Delete successfully.', 'statusCode' => 200])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Failed to Delete Post', 'statusCode' => 404])->setStatusCode(404);
        }
    }


    function medicalHistoryDetails(Request $request)
    {
        $id = $request->input('id');
        $selectColumns = ['medical_service_table.id',
            'medical_service_table.student_id',
            'medical_service_table.service_type',
            'medical_service_table.provider_name',
            'medical_service_table.details',
            'medical_service_table.created_at',
            'medical_service_table.docuemnt_url',
            'studenttable.name',
            'studenttable.department'];

        $result = DB::table('medical_service_table')
            ->leftJoin('studenttable', 'studenttable.studentID', '=', 'medical_service_table.student_id')
            ->select($selectColumns)->where(['medical_service_table.id' => $id])
            ->orderBy('medical_service_table.id', 'desc')->get();

        if (sizeof($result)!=0) {
            return response()->json($result[0])->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Not found', 'statusCode' => 404])->setStatusCode(404);
        }
    }


    function medicalHistory(Request $request)
    {
        $selectColumns = ['medical_service_table.id',
            'medical_service_table.student_id',
            'medical_service_table.service_type',
            'medical_service_table.provider_name',
            'studenttable.name',
            'studenttable.department'];

        $result = DB::table('medical_service_table')
            ->leftJoin('studenttable', 'studenttable.studentID', '=', 'medical_service_table.student_id')
            ->select($selectColumns);

        $studentID = $request->input('studentID');

        $isAll = $request->input('isAll');
        if ($isAll == 0)
            $result = $result->where(['medical_service_table.student_id' => $studentID]);

        $result = $result->groupBy($selectColumns)->orderBy('medical_service_table.id', 'desc')->paginate(10);

        if ($result == true) {
            return response()->json($result)->setStatusCode(200);
        } else {
            return response()->json(['message' => 'Failed!! Plase Try Again Later', 'statusCode' => 404])->setStatusCode(404);

        }
    }
}
