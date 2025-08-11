<?php
namespace App\Repositories\Contracts;
use App\Models\Exam;

interface ExamRepositoryInterface
{
        public function createExamWithQuestions(array $data): Exam;
public function getExamQuestionsWithChoicesByCourseId(int $courseId);
    public function getExamQuestionsForStudent(int $courseId, int $studentId);

}