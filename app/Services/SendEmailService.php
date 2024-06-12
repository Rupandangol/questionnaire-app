<?php

namespace App\Services;

use App\Jobs\TestJob;
use App\Models\Student;

class SendEmailService
{
    public function sendEmail($questionnaire)
    {
        Student::chunk(100, function ($students) use ($questionnaire) {
            TestJob::dispatch(['questionnaire_id' => $questionnaire->id, 'students' => $students]);
        });
    }
}
