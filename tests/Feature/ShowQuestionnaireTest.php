<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionnaireDetail;
use App\Models\Response;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowQuestionnaireTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_show_a_questionnaire_with_details()
    {
        // Create a questionnaire
        $questionnaire = Questionnaire::factory()->create([
            'expiry_date' => Carbon::now()->addDay(), // Ensure it's not expired
        ]);

        // Create questions and answers
        $questions = Question::factory()->count(2)->create();
        foreach ($questions as $question) {
            Answer::factory()->count(4)->create(['question_id' => $question->id]);
        }


        // Attach questions to the questionnaire
        foreach ($questions as $question) {
            QuestionnaireDetail::create([
                'questionnaire_id' => $questionnaire->id,
                'question_id' => $question->id,
            ]);
        }

        // Make the API request
        $response = $this->getJson("/api/questionnaire/{$questionnaire->id}");

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'id' => $questionnaire->id,
                    'questionnaire_details' => [
                        [
                            'question_id' => $questions[0]->id,
                            'questions' => [
                                'answers' => [
                                    ['question_id' => $questions[0]->id],
                                ],
                            ],
                        ],
                        [
                            'question_id' => $questions[1]->id,
                            'questions' => [
                                'answers' => [
                                    ['question_id' => $questions[1]->id],
                                ],
                            ],
                        ],
                    ],
                ],
                'message' => 'successfully fetched'
            ]);
    }

    /** @test */
    public function it_returns_error_if_questionnaire_expired()
    {
        $questionnaire = Questionnaire::factory()->create([
            'expiry_date' => Carbon::now()->subDay(),
        ]);

        $response = $this->getJson("/api/questionnaire/{$questionnaire->id}");

        $response->assertStatus(403)
            ->assertJson([
                'status' => 'expired',
                'message' => 'Expired',
                'data'=>[]
            ]);
    }
    
    /** @test */
    public function it_returns_error_if_questionnaire_not_found()
    {
        $response = $this->getJson("/api/questionnaire/9999");

        $response->assertStatus(500)
            ->assertJson([
                'status' => 'error',
                'data' => [],
                'message' => 'No query results for model [App\\Models\\Questionnaire] 9999'
            ]);
    }
}
