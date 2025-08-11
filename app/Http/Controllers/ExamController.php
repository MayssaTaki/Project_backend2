<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\StartExamRequest;
use App\Http\Requests\ShowRandomQuestionsRequest;

use App\Http\Resources\ExamResource;
use App\Http\Requests\SubmitExamRequest;
use Illuminate\Support\Arr;

use App\Services\ExamService;
use App\Services\Interfaces\ExamServiceInterface;

use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamServiceInterface $examService)
    {
        $this->examService = $examService;
    }


public function store(StoreExamRequest $request)
{
    $validated = $request->validated();


    $exam = $this->examService->createExamWithQuestions($validated);

    return response()->json([
        'message' => 'تم إنشاء الامتحان بنجاح.',
        'exam' => new ExamResource($exam->load('questions.choices'))
    ], 201);
}


public function getExamByCourse($courseId)
{
    $exam = $this->examService->getExamQuestionsWithChoicesByCourseId($courseId);

    if (!$exam) {
        return response()->json([
            'message' => 'لا يوجد امتحان مرتبط بهذا الكورس.',
        ], 404);
    }

    return response()->json([
        'message' => 'تم جلب الامتحان بنجاح.',
        'exam' => $exam,
    ]);
}

 public function showExamForStudent(Request $request, $courseId)
    {
        try {
$studentId = $request->user()->student->id;

            $exam = $this->examService->getExamQuestionsForStudent($courseId, $studentId);

            return response()->json([
                'status' => 'success',
                'exam' => $exam
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل في جلب الامتحان: ' . $e->getMessage()
            ], 403);
        }
    }

}