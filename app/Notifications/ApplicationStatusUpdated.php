<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationStatusUpdated extends Notification
{
    use Queueable;

    protected $application;

    public function __construct($application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $application = $this->application;

        $mail = (new MailMessage)
            ->subject('Application Status')
            ->greeting('Dear ' . $notifiable->user_name . ',')
            ->line('We would like to inform you that the status of your application has been updated.')
            ->line('**Application ID:** ' . $application->application_id)
            ->line('**Current Status:** ' . ucfirst($application->application_status));

        if ($application->payment_exception ?? false) {
            $mail->line('Please note: Your application has been approved with a **payment exception**. No payment is required.');
        }

        $mail->line('You may log in to your account to view more details.')
             ->action('View Application', url('/user/applications/' . $application->application_id))
             ->line('If you have any questions, feel free to contact us at prp@uthm.edu.my.')
             ->line('Thank you for choosing our Residential College Accommodation Rental System.');

        return $mail;
    }

    public function toDatabase($notifiable)
    {
        return [
            'application_id' => $this->application->application_id,
            'status' => $this->application->application_status,
            'message' => 'Your application status has been updated.',
        ];
    }
}
