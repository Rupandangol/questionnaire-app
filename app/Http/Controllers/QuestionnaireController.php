<?php

namespace App\Http\Controllers;

use App\Http\Requests\generateQuestionnaireRequest;
use App\Jobs\TestJob;
use App\Mail\TestEmail;
use App\Mail\TestMarkdownMail;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionnaireDetail;
use App\Models\Student;
use App\Services\SendEmailService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PDO;

class QuestionnaireController extends Controller
{
    protected $service;
    public function __construct(SendEmailService $service)
    {
        $this->service = $service;
    }

    public function generateQuestionnaire(generateQuestionnaireRequest $request)
    {
        DB::beginTransaction();
        try {
            $data['title'] = $request->title;
            $data['expiry_date'] = $request->expiry_date;
            $questionnaire = Questionnaire::create($data);

            $physicQuestions = Question::where('subject', 'physics')->inRandomOrder()->take(5)->get();
            $chemistryQuestions = Question::where('subject', 'chemistry')->inRandomOrder()->take(5)->get();
            $questions = $physicQuestions->merge($chemistryQuestions);
            foreach ($questions as $question) {
                QuestionnaireDetail::create([
                    'questionnaire_id' => $questionnaire->id,
                    'question_id' => $question->id
                ]);
            }

            DB::commit();

            // Send Email Service for sending email in a job queue
            $this->service->sendEmail($questionnaire);

            return response()->json([
                'status' => 'success',
                'data' => $questionnaire->load('questionnaireDetails.questions')
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

    public function showQuestionnaire(int $questionnaireId)
    {
        try {
            $questionnaire = Questionnaire::with('questionnaireDetails.questions.answers')->find($questionnaireId);
            return response()->json([
                'status' => 'success',
                'data' => $questionnaire,
                'message' => 'successfully fetched'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'data' => [],
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function allQuestionnaire()
    {
        try {
            $currentDateTime = Carbon::now();
            $questionnaire = Questionnaire::where('expiry_date', '>', $currentDateTime)->orderBy('id','desc')->paginate(5); //not expired only
            return response()->json([
                'status' => 'success',
                'message' => 'fetched successfully',
                'data' => $questionnaire
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }
    }
}
