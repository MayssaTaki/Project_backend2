<?php

namespace App\Repositories;
use App\Models\ExamAttempt;
use App\Models\Choice;
use App\Models\StudentAnswer;
use App\Repositories\Contracts\ExamAttemptRepositoryInterface;

class ExamAttemptRepository implements ExamAttemptRepositoryInterface
{
   public function startExam(int $examId, int $studentId): ExamAttempt
{
    $existingAttempt = ExamAttempt::where('exam_id', $examId)
        ->where('student_id', $studentId)
        ->where('status', 'completed')
        ->first();

    if ($existingAttempt) {
        throw new \Exception('لقد أكملت هذا الامتحان بالفعل ولا يمكنك إعادة المحاولة.');
    }

    return ExamAttempt::create([
        'exam_id' => $examId,
        'student_id' => $studentId,
        'started_at' => now(),
    ]);
}


public function submitAnswers(int $attemptId, array $answers): ExamAttempt
{
    $attempt = ExamAttempt::with('exam.questions.choices')->findOrFail($attemptId);
    if ($attempt->status === 'completed') {
        throw new \Exception('لقد قمت بتصحيح هذا الامتحان مسبقًا.');
        
    }
    $examEndTime = $attempt->started_at->addMinutes($attempt->exam->duration_minutes);

    if (now()->greaterThan($examEndTime)) {
        $answers = [];
    }

    foreach ($answers as $answer) {
        $choice = Choice::find($answer['choice_id']);
        StudentAnswer::updateOrCreate(
            [
                'exam_attempt_id' => $attemptId,
                'question_id' => $answer['question_id'],
            ],
            [
                'choice_id' => $answer['choice_id'],
                'is_correct' => $choice ? $choice->is_correct : false,
            ]
        );
    }

    $attempt->load('exam.questions.choices', 'exam.questions.answers.choice');

    $totalQuestions = $attempt->exam->questions->count();
    $correctCount = StudentAnswer::where('exam_attempt_id', $attemptId)
        ->where('is_correct', true)
        ->count();

    $attempt->update([
        'score' => $correctCount,
        'ended_at' => now(),
        'status' => 'completed'
    ]);

    $answersDetails = [];
    foreach ($attempt->exam->questions as $question) {
        $studentAnswer = $question->answers
            ->where('exam_attempt_id', $attemptId)
            ->first();

        $correctChoice = $question->choices->where('is_correct', true)->first();

        $answersDetails[] = [
            'question_id' => $question->id,
            'question_text' => $question->question_text,
            'student_choice' => $studentAnswer && $studentAnswer->choice ? $studentAnswer->choice->choice_text : null,
            'is_correct' => $studentAnswer ? $studentAnswer->is_correct : false,
            'correct_choice' => $correctChoice ? $correctChoice->choice_text : null
        ];
    }

    $attempt->setRelation('answers_details', collect($answersDetails));
    $attempt->setAttribute('score_text', "{$correctCount} من {$totalQuestions}");

    return $attempt;
}

}
