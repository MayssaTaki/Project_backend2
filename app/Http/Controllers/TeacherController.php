<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRegisterRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\TeacherUpdateRequest;
use App\Models\Teacher;
use App\Http\Resources\TeacherResource;
use Illuminate\Http\Request;

use App\Services\TeacherService;
use Illuminate\Http\JsonResponse;

class TeacherController extends Controller
{
    protected TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function register(TeacherRegisterRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $teacher = $this->teacherService->register($data);
    
            return response()->json([
                'status' => 'success',
                'message' => 'تم تسجيل الأستاذ والمستخدم بنجاح.',
                'data' => $teacher
            ], 201);
        } catch (TeacherRegistrationException | UserRegistrationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ غير متوقع.',
            ], 500);
        }
    }

    public function update(TeacherUpdateRequest $request, Teacher $teacher): JsonResponse
    {
        try {
            $updatedTeacher = $this->teacherService->update($teacher, $request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث بيانات الاستاذ بنجاح.',
                'data' => $updatedTeacher
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

public function getAllTeachers()
    {
        $teachers = $this->teacherService->getAllTeachers();
    
        if ($teachers->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'تم استرجاع جميع االمدربين بنجاح',
'data' => \App\Http\Resources\TeacherResource::collection($teachers)
            ], 200); 
        }
    
        return response()->json([
            'status' => 'fail',
            'message' => 'لم يتم العثور على المدربين',
            'data' => []
        ], 404); 
    }


    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([
                'status' => 'error',
                'message' => 'يرجى إدخال كلمة للبحث.'
            ], 400);
        }

        $results = $this->teacherService->search($query);

        if ($results->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'لم يتم العثور على نتائج مطابقة.',
                'data' => []
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تم العثور على النتائج المطابقة.',
            'data' => $results
        ]);
    }

    public function countTeachers(): JsonResponse
    {
        try {
            $teacherCount = $this->teacherService->countTeachers();
            return response()->json([
                'status' => 'success',
                'message' => 'تم جلب عدد المعلمين بنجاح.',
                'data' => [
                    'teacher_count' => $teacherCount
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 403);
        }
    }


    public function approve($id): JsonResponse
    {
        $teacher = $this->teacherService->approveTeacher($id);

        return response()->json([
            'message' => 'تمت الموافقة على حساب الأستاذ.',
            'teacher' => $teacher,
        ]);
    }

    public function reject($id): JsonResponse
    {
        $this->teacherService->rejectTeacher($id);

        return response()->json([
            'message' => 'تم رفض حساب الأستاذ.'
        ]);
    }

    public function approved()
{
    $teachers = $this->teacherService->getApprovedTeachers();
    $count = $teachers->count();

    if ($count > 0) {
        return response()->json([
            'message' => 'تم العثور على الأساتذة المقبولين.',
            'count' => $count,
            'data' => $teachers
        ], 200);
    } else {
        return response()->json([
            'message' => 'لا يوجد أساتذة مقبولون حتى الآن.',
            'count' => $count
        ], 200);
    }
}

public function rejected()
{
    $teachers = $this->teacherService->getRejectedTeachers();
    $count = $teachers->count(); 

    if ($count > 0) {
        return response()->json([
            'message' => 'تم العثور على الأساتذة المرفوضين.',
            'count' => $count,
            'data' => $teachers
        ], 200);
    } else {
        return response()->json([
            'message' => 'لا يوجد أساتذة مرفوضين حتى الآن.',
            'count' => $count
        ], 200);
    }
}

public function pened()
{
    $teachers = $this->teacherService->getPendingTeachers();
    $count = $teachers->count();  

    if ($count > 0) {
        return response()->json([
            'message' => 'تم العثور على الأساتذة المعلقين.',
            'count' => $count,
            'data' => $teachers
        ], 200);
    } else {
        return response()->json([
            'message' => 'لا يوجد أساتذة معلقين حتى الآن.',
            'count' => $count
        ], 200);
    }
}

}    
