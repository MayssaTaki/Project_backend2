<?php
namespace App\Listeners;

use App\Events\TeacherApproved;
use App\Notifications\TeacherApprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyTeacherOfApproval implements ShouldQueue
{
    public function handle(TeacherApproved $event): void
    {
        $event->teacher->user->notify(new TeacherApprovedNotification());
    }
}
