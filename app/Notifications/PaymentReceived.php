<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Payment;
class PaymentReceived extends Notification
{
    use Queueable;

    protected $payment;

    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
{
    return $this->payment->payment_status === 'paid'
        ? ['mail', 'database']
        : ['mail','database']; 
}

    public function toMail($notifiable)
{
    $payment = $this->payment;

    return (new MailMessage)
        ->subject('Payment Received')
        ->greeting('Good Day ' . $notifiable->user_name)
        ->line('We have received your payment of RM' . number_format($payment->amount, 2) . '.')
        ->line('Thank you for your payment.')
        ->line('Thank you for using our system!');
}


    public function toDatabase($notifiable)
    {
        return [
        'title' => 'Payment Received',
        'payment_id' => $this->payment->payment_id,
        'amount' => $this->payment->amount,
        'message' => 'We received your payment of RM' . number_format($this->payment->amount, 2) . '.',
        'timestamp' => now()->toDateTimeString(), // Optional but useful
    ];
    }
}
