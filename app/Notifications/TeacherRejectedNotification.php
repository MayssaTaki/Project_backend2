<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    

    public function toDatabase($notifiable)
    {
        return ([
            'message' => 'تم رفض حسابك من قبل الإدارة.',
        ]);
    }
}
