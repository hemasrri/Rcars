<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationAcknowledged extends Notification
{
    use Queueable;

    protected $application;

    public function __construct($application)
    {
        $this->application = $application;
    }

public function via($notifiable)
{
    return $this->application->application_status === 'pending'
        ? ['mail', 'database']
        : ['database'];
}
public function toDatabase($notifiable)
{
    return [
        'application_id' => $this->application->application_id,
        'message' => 'Your application has been received and is currently pending review.',
    ];
}

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Acknowledgement of Your Application Submission')
            ->greeting('Dear ' . $this->application->name)
            ->line('We are pleased to inform you that your application has been successfully received.')
            ->line('Application ID: ' . $this->application->application_id)
            ->line('Our team will review your submission and notify you of the next steps via email.')
            ->line('If you have any questions, feel free to contact us at prp@uthm.edu.my.')
            ->line('Thank you for choosing our Residential College Accommodation Rental System.');

    }
}
