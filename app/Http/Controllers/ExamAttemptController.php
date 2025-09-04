<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\ExamAttemptServiceInterface;
use Illuminate\Http\Request;

class ExamAttemptController extends Controller
{
    protected $examAttemptService;

    public function __construct(ExamAttemptServiceInterface $examAttemptService)
    {
        $this->examAttemptService = $examAttemptService;
    }

   public function start(Request $request, $examId)
{
    $studentId = $request->user()->student->id;

    try {
        $attempt = $this->examAttemptService->startExam($examId, $studentId);

        return response()->json([
            'message' => 'تم بدء الامتحان.',
            'attempt' => $attempt
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 403);
    }
}

public function submit(Request $request, $attemptId)
{
    try {
        $attempt = $this->examAttemptService->submitExam($attemptId, $request->answers);

        return response()->json([
            'message' => 'تم تصحيح الامتحان.',
            'score' => $attempt->score_text,
            'answers_details' => $attempt->answers_details,
            'attempt' => $attempt->only([
                'id', 'exam_id', 'student_id', 'started_at', 'ended_at', 'score', 'status', 'created_at', 'updated_at'
            ])
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'خطأ: ' . $e->getMessage()
        ], 400);
    }
}

}
