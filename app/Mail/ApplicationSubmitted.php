<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Application;

class ApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        return $this->subject('Acknowledgement of Your Application Submission')
                    ->view('emails.application_submitted')
                    ->with([
                        'name' => $this->application->name,
                        'applicationId' => $this->application->application_id,
                    ]);
    }
}
