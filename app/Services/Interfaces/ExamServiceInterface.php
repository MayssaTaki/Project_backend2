<?php

namespace App\Services\Interfaces;

interface ExamServiceInterface
{
    public function createExamWithQuestions(array $data);
    public function getExamQuestionsWithChoicesByCourseId(int $courseId);
    public function getExamQuestionsForStudent(int $courseId, int $studentId);
    

}