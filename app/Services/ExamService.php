<?php

namespace App\Services;

use App\Services\Interfaces\ExamServiceInterface;
use App\Services\TransactionService;
use App\Repositories\Contracts\ExamRepositoryInterface;
use App\Exceptions\ExamException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\Exam;
use App\Models\Question;
use App\Models\ExamAttemptQuestion;
use Carbon\Carbon;

class ExamService implements ExamServiceInterface
{
    protected $examRepo;
    protected $transactionService;

    public function __construct(
        ExamRepositoryInterface $examRepo,
        TransactionService $transactionService
    ) {
        $this->examRepo = $examRepo;
        $this->transactionService = $transactionService;
    }

   public function createExamWithQuestions(array $data)
{
    try {
        return $this->transactionService->run(function () use ($data) {

            $exam = $this->examRepo->createExamWithQuestions($data);

            return $exam;
        });
    } catch (\Exception $e) {
        throw $e; 
    }
}
 public function getExamQuestionsWithChoicesByCourseId(int $courseId)
    {
        return $this->examRepo->getExamQuestionsWithChoicesByCourseId($courseId);
    }

    public function getExamQuestionsForStudent(int $courseId, int $studentId)
    {
        return $this->examRepo->getExamQuestionsForStudent($courseId, $studentId);
    }
}


