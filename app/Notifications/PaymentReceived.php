<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Payment $payment)
    {
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Received - ' . $this->payment->order->order_number)
            ->line('Payment of ' . $this->payment->amount . ' has been received.')
            ->action('View Payment', route('vendor.payments.show', $this->payment))
            ->line('Thank you for using our platform!');
    }

    public function toArray($notifiable): array
    {
        return [
            'payment_id' => $this->payment->id,
            'order_id' => $this->payment->order_id,
            'amount' => $this->payment->amount,
            'message' => 'Payment of ' . $this->payment->amount . ' has been received.',
            'action_url' => route('vendor.payments.show', $this->payment),
            'type' => 'payment_received',
        ];
    }
}