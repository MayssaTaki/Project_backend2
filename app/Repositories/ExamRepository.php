<?php
namespace App\Repositories;

use App\Models\Exam;
use App\Models\Choice;
use App\Models\Booking;
use App\Models\ExamAttempt;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Repositories\Contracts\ExamRepositoryInterface;

class ExamRepository implements ExamRepositoryInterface
{


public function createExamWithQuestions(array $data): Exam
{
    $exam = Exam::create([
        'duration_minutes' => $data['duration_minutes'],
        'course_id' => $data['course_id'],
    ]);

    $questionsData = [];
    $choicesData = [];

    foreach ($data['questions'] as $qIndex => $q) {
        $questionsData[] = [
            'exam_id' => $exam->id,
            'question_text' => $q['question_text'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    $exam->questions()->insert($questionsData);
    $questions = $exam->questions()->get();

    foreach ($questions as $index => $question) {
        $originalChoices = $data['questions'][$index]['choices'] ?? [];

        foreach ($originalChoices as $choice) {
            $choicesData[] = [
                'question_id' => $question->id,
                'choice_text' => $choice['text'],
                'is_correct' => $choice['is_correct'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    }

    Choice::insert($choicesData);

    return $exam->load('questions.choices');
}

 public function getExamQuestionsWithChoicesByCourseId(int $courseId)
    {
        return Exam::where('course_id', $courseId)
            ->with(['questions.choices'])
            ->first();
    }

     public function getExamQuestionsForStudent(int $courseId, int $studentId)
    {
        $isRegistered = DB::table('course_registrations')
            ->where('course_id', $courseId)
            ->where('student_id', $studentId)
            ->exists();

        if (!$isRegistered) {
            throw new \Exception('الطالب غير مسجل في هذا الكورس.');
        }

        return Exam::where('course_id', $courseId)
            ->with([
                'questions' => function ($q) {
                    $q->with([
                        'choices:id,question_id,choice_text'
                    ]);
                }
            ])
            ->get(['id', 'course_id', 'duration_minutes']);
    }
}