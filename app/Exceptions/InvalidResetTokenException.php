<?php
namespace App\Exceptions;

use Exception;

class InvalidResetTokenException extends Exception
{
    public function __construct($message = 'رمز إعادة التعيين غير صحيح', $code = 422)
    {
        parent::__construct($message, $code);
    }
}
