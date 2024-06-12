<?php

namespace App\Http\Middleware;

use App\Models\Response as ModelsResponse;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTestCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $questionnaireId = $request->route('id');
            $studentId = $request->route('student_id');

            $response = ModelsResponse::where(['questionnaire_id' => $questionnaireId, 'student_id' => $studentId])->exists();

            if ($response) {
                return response()->json([
                    'status' => 'failed',
                    'data' => [],
                    'message' => 'you have already completed the test'
                ], 403);
            }
            return $next($request);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'data' => [],
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
