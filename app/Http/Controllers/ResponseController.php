<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResponseRequest;
use App\Models\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResponseController extends Controller
{
    public function storeResponse(StoreResponseRequest $request, int $questionnaireId, int $studentId)
    {
        DB::beginTransaction();
        try {
            $response = Response::create([
                'questionnaire_id' => $questionnaireId,
                'student_id' => $studentId,
            ]);
            // Prepare the response details data
            $responseDetailsData = collect($request->response)->map(function ($item) use ($response) {
                return [
                    'response_id' => $response->id,
                    'question_id' => $item['question_id'],
                    'answer_id' => $item['answer_id'],
                ];
            });

            // Create response details
            $response->responseDetails()->createMany($responseDetailsData->toArray());

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $response->load('responseDetails.questions'),
                'message' => 'response stored'
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'data' => [],
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
