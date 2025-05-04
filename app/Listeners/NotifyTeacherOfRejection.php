<?php
namespace App\Listeners;

use App\Events\TeacherRejected;
use App\Notifications\TeacherRejectedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyTeacherOfRejection implements ShouldQueue
{
    public function handle(TeacherRejected $event): void
    {
        $event->teacher->user->notify(new TeacherRejectedNotification());
    }
}
