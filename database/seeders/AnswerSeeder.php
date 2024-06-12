<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $questions = DB::table('questions')->pluck('id');
        foreach ($questions as $item) {
            $correctAnswerIndex = rand(0, 3); // Randomly select the index for the correct answer
            for ($i = 0; $i < 4; $i++) {
                DB::table('answers')->insert([
                    'question_id'=>$item,
                    'answer'=> $faker->sentence,
                    'is_correct'=>$i==$correctAnswerIndex, // Randomly mark one answer as correct
                    'updated_at'=>now(),
                    'created_at'=>now(),
                ]);
            }
        }
    }
}
