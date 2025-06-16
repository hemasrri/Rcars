<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewApplicationReceived extends Notification
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
            'name' => $this->application->name,
            'message' => 'New application received.',
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Application Received')
            ->greeting('Hello ' . $notifiable->staff_name)
            ->line('A new application has been submitted.')
            ->line('Application ID: ' . $this->application->application_id)
            ->action('View Application', url('/admin/applications'))
            ->line('Thank you for using our system!');

            
    }
}
