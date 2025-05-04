<?php
namespace App\Events;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Teacher;

class TeacherRegistered
{
    use Dispatchable, SerializesModels;
    
    public function __construct(public Teacher $teacher) {}
}
