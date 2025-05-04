<?php

namespace App\Exceptions;

use Exception;

class TeacherUpdateException extends Exception
{
    public function __construct($message = 'حدث خطأ أثناء تعديل بيانات الأستاذ.', $code = 500)
    {
        parent::__construct($message, $code);
    }
}
