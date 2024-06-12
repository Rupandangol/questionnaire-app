<?php

namespace Tests\Feature;

use App\Jobs\TestJob;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class GenerateQuestionnaireTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_questionnaire()
    {
        // Create test data
        Question::factory()->count(10)->create(['subject' => 'physics','question'=>'test question?']);
        Question::factory()->count(10)->create(['subject' => 'chemistry','question'=>'test question?']);

        // Make API request
        $response = $this->postJson('/api/generate-questionnaire', [
            'title' => 'Test Questionnaire',
            'expiry_date' => '2024-12-31',
        ]);

        // Assert response
        $response->assertStatus(201);
        $response->assertJsonStructure(['status', 'data' => ['id', 'title', 'expiry_date']]);

        // Assert questionnaire was created
        $this->assertDatabaseHas('questionnaires', [
            'title' => 'Test Questionnaire',
            'expiry_date' => '2024-12-31',
        ]);
    }
    
    /** @test */
    public function it_creates_questionnaire_details()
    {
        // Create test data
        Question::factory()->count(10)->create(['subject' => 'physics','question'=>'test question?']);
        Question::factory()->count(10)->create(['subject' => 'chemistry','question'=>'test question?']);

        // Make API request
        $response = $this->postJson('/api/generate-questionnaire', [
            'title' => 'Test Questionnaire',
            'expiry_date' => '2024-12-31',
        ]);

        // Assert response
        $questionnaire = Questionnaire::first();

        $this->assertCount(10, $questionnaire->questionnaireDetails);
    }

    //   /** @test */
    //   public function it_dispatches_jobs_to_send_emails()
    //   {
    //       // Use the Queue fake to test job dispatching
    //       Queue::fake();
  
    //       // Create test data
    //       Question::factory()->count(10)->create(['subject' => 'physics']);
    //       Question::factory()->count(10)->create(['subject' => 'chemistry']);
    //       Student::factory()->count(10)->create();
  
    //       // Make API request
    //       $response = $this->postJson('/api/generate-questionnaire', [
    //           'title' => 'Test Questionnaire',
    //           'expiry_date' => '2024-12-31',
    //       ]);
  
    //       $questionnaire = Questionnaire::first();
  
    //       // Assert jobs were dispatched
    //       Queue::assertPushed(TestJob::class, function ($job) use ($questionnaire) {
    //           return $job->questionnaire_id === $questionnaire->id;
    //       });
    //   }
}
