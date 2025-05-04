<?php
namespace App\Listeners;

use App\Events\TeacherRegistered;
use App\Models\User;
use App\Notifications\NewTeacherRegisteredNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAdminsOfNewTeacher implements ShouldQueue
{
    public function handle(TeacherRegistered $event): void
    {
        User::where('role', 'admin')->get()->each(function ($admin) use ($event) {
            $admin->notify(new NewTeacherRegisteredNotification($event->teacher));
        });
    }
}
