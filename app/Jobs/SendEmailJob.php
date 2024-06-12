<?php

namespace App\Jobs;

use App\Mail\QuestionnaireMarkdownMail;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->data['students'] as $student) {
            $link = $this->linkGenerator($this->data['questionnaire_id'], $student->id);
            Mail::to($student->email)->send(new QuestionnaireMarkdownMail(['name' => $student->name, 'link' => $link]));
            sleep(5);
        }
    }

    public function failed(Exception $exception)
    {
        dd($exception->getMessage());
    }

    protected function linkGenerator($questionnaire_id, $student_id) : string
    {
        return "http://localhost/questionnaire/" . $questionnaire_id . "/student/" . $student_id;
    }
}
