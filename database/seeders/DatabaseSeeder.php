<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionnaireDetail;
use App\Models\Student;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Student::factory(5)->create();
        Question::factory()
            ->count(100)
            ->create()
            ->each(function ($question) {
                // Create 4 answers for each question
                $answers = Answer::factory()->count(4)->make();

                // Randomly pick one answer to be correct
                $correctAnswerIndex = rand(0, 3);
                $answers[$correctAnswerIndex]->is_correct = true;

                // Save answers to the question
                $question->answers()->saveMany($answers);
            });


        Questionnaire::factory()
            ->count(10)
            ->create()
            ->each(function ($questionnaire) {
                // Fetch 5 physics and 5 chemistry questions
                $physicsQuestions = Question::where('subject', 'physics')->inRandomOrder()->take(5)->get();
                $chemistryQuestions = Question::where('subject', 'chemistry')->inRandomOrder()->take(5)->get();

                // Merge the questions
                $questions = $physicsQuestions->merge($chemistryQuestions);

                // Create the QuestionnaireDetail entries
                foreach ($questions as $question) {
                    QuestionnaireDetail::create([
                        'questionnaire_id' => $questionnaire->id,
                        'question_id' => $question->id,
                    ]);
                }
            });



        $this->call([
            UserSeeder::class,
            // QuestionSeeder::class,
            // AnswerSeeder::class
        ]);
    }
}
