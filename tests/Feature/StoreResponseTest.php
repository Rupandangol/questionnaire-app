<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionnaireDetail;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreResponseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_store_a_response_successfully()
    {
        $questionnaire = Questionnaire::factory()->create([
            'expiry_date' => Carbon::now()->addDay(), // Ensure it's not expired
        ]);
        $student = Student::factory()->create();

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

        // Prepare request data
        $requestData = [
            'response' => [
                [
                    'question_id' => $questions[0]->id,
                    'answer_id' => $questions[0]->answers->first()->id,
                ],
                [
                    'question_id' => $questions[1]->id,
                    'answer_id' => $questions[1]->answers->first()->id,
                ]
            ]
        ];

        // Make the API request
        $response = $this->postJson("/api/questionnaire/{$questionnaire->id}/student/{$student->id}", $requestData);

        // Assert the response
        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'response stored',
                     'data' => [
                         'questionnaire_id' => $questionnaire->id,
                         'student_id' => $student->id,
                         'response_details' => [
                             [
                                 'question_id' => $questions[0]->id,
                                 'answer_id' => $questions[0]->answers->first()->id,
                             ],
                             [
                                 'question_id' => $questions[1]->id,
                                 'answer_id' => $questions[1]->answers->first()->id,
                             ],
                         ],
                     ]
                 ]);

        // Assert the response is stored in the database
        $this->assertDatabaseHas('responses', [
            'questionnaire_id' => $questionnaire->id,
            'student_id' => $student->id,
        ]);

        foreach ($requestData['response'] as $item) {
            $this->assertDatabaseHas('response_details', [
                'question_id' => $item['question_id'],
                'answer_id' => $item['answer_id'],
            ]);
        }
    }

}
