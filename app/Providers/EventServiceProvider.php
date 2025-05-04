<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */

     protected $listen = [
        \App\Events\TeacherRegistered::class => [
            \App\Listeners\NotifyAdminsOfNewTeacher::class,
        ],
        \App\Events\TeacherApproved::class => [
            \App\Listeners\NotifyTeacherOfApproval::class,
        ],
        \App\Events\TeacherRejected::class => [
            \App\Listeners\NotifyTeacherOfRejection::class,
        ],
    ];
    
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
