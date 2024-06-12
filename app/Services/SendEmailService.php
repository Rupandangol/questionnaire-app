<?php

namespace App\Services;

use App\Jobs\SendEmailJob;
use App\Models\Student;

class SendEmailService
{
    public function sendEmail($questionnaire)
    {
        Student::chunk(100, function ($students) use ($questionnaire) {
            SendEmailJob::dispatch(['questionnaire_id' => $questionnaire->id, 'students' => $students]);
        });
    }
}
