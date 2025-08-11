<?php

namespace App\Services;
use App\Services\Interfaces\ExamAttemptServiceInterface;
use App\Repositories\Contracts\ExamAttemptRepositoryInterface;


class ExamAttemptService implements ExamAttemptServiceInterface
{
    protected $attemptRepo;

    public function __construct(ExamAttemptRepositoryInterface $attemptRepo)
    {
        $this->attemptRepo = $attemptRepo;
    }

    public function startExam(int $examId, int $studentId)
    {
        return $this->attemptRepo->startExam($examId, $studentId);
    }

    public function submitExam(int $attemptId, array $answers)
    {
        return $this->attemptRepo->submitAnswers($attemptId, $answers);
    }
}
