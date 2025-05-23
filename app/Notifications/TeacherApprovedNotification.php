<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

   
 
    public function toDatabase($notifiable)
    {
        return ([
            'message' => 'تمت الموافقة على حسابك. يمكنك الآن الدخول إلى المنصة.',
        ]);
    }
}
