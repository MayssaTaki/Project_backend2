<?php
namespace App\Repositories\Contracts;
use App\Models\ExamAttempt;

interface ExamAttemptRepositoryInterface
{
    public function startExam(int $examId, int $studentId): ExamAttempt;
    public function submitAnswers(int $attemptId, array $answers): ExamAttempt;
}
