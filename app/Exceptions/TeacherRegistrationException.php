<?php

namespace App\Exceptions;

use Exception;

class TeacherRegistrationException extends Exception
{
    public function __construct($message = 'حدث خطأ أثناء تسجيل الأستاذ.', $code = 500)
    {
        parent::__construct($message, $code);
    }
}
