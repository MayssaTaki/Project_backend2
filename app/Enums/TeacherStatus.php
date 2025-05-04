<?php

namespace App\Enums;

enum TeacherStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
}