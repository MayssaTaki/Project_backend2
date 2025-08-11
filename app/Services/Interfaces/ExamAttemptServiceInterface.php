<?php

namespace App\Services\Interfaces;
interface ExamAttemptServiceInterface
{
    public function startExam(int $examId, int $studentId);
    public function submitExam(int $attemptId, array $answers);
}
