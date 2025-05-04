<?php

namespace App\Notifications;
use App\Models\Teacher;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTeacherRegisteredNotification extends Notification implements ShouldQueue 
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected Teacher $teacher;

    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
   

    

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
        return ([
            'message' => 'تم تسجيل أستاذ جديد: ' . $this->teacher->first_name . ' ' . $this->teacher->last_name,
        ]);
}  }
