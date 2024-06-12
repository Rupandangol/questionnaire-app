<?php

namespace App\Http\Middleware;

use App\Models\Questionnaire;
use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTestIsExpired
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
            $questionnaire = Questionnaire::findOrFail($questionnaireId);
            if (Carbon::now()->greaterThan($questionnaire->expiry_date)) {
                return response()->json([
                    'status' => 'expired',
                    'message' => 'Expired',
                    'data' => []
                ], 403);
            }
            return $next($request);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
