<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRegisterRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Models\Student;

use App\Http\Resources\StudentResource;
use Illuminate\Http\Request;

use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use App\Exceptions\StudentRegistrationException;
use App\Exceptions\UserRegistrationException;
use Exception;

class StudentController extends Controller
{
    protected StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function register(StudentRegisterRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $student = $this->studentService->register($data);
    
            return response()->json([
                'status' => 'success',
                'message' => 'تم تسجيل الطالب والمستخدم بنجاح.',
                'data' => $student
            ], 201);
        }

        catch (StudentRegistrationException | UserRegistrationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
         
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ غير متوقع.',
            ], 500);
        }
    }


    public function getAllStudents()
    {
        $students = $this->studentService->getAllStudent();
    
        if ($students->count() > 0) { 
            return response()->json([
                'status' => 'success',
                'message' => 'تم استرجاع جميع الطلاب  بنجاح',
'data' => \App\Http\Resources\StudentResource::collection($students)
            ], 200); 
        }
    
        return response()->json([
            'status' => 'fail',
            'message' => 'لم يتم العثور على الطلاب',
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

        $results = $this->studentService->search($query);

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

    public function update(StudentUpdateRequest $request, Student $student): JsonResponse
    {
        try {
            $updatedStudent = $this->studentService->update($student, $request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث بيانات الطالب بنجاح.',
                'data' => $updatedStudent
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function countStudents(): JsonResponse
    {
        try {
            $studentCount = $this->studentService->countStudents();
            return response()->json([
                'status' => 'success',
                'message' => 'تم جلب عدد الطلاب  بنجاح.',
                'data' => [
                    'student_count' => $studentCount
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 403);
        }
    }


    public function ban($id): JsonResponse
    {
        $student = $this->studentService->ban($id);
        return response()->json([
            'message' => 'تم حظر الطالب.',
            'student_id' => $student->id,
        ]);
    }

    public function unban($id): JsonResponse
    {
        $student = $this->studentService->unban($id);
        return response()->json([
            'message' => 'تم فك الحظر عن الطالب.',
            'student_id' => $student->id,
        ]);
    }

    public function checkStatus($id): JsonResponse
    {
        $status = $this->studentService->checkIfBanned($id) ? 'محظور' : 'نشط';

        return response()->json([
            'student_id' => $id,
            'status' => $status
        ]);
    }

    public function block()
{
    $students = $this->studentService->getBanStudents();
    $count = $students->count();  

    if ($count > 0) {
        return response()->json([
            'message' => 'تم العثور على الطلاب المحظورين.',
            'count' => $count,
            'data' => $students
        ], 200);
    } else {
        return response()->json([
            'message' => 'لا يوجد طلاب محظورة حتى الآن.',
            'count' => $count
        ], 200);
    }
}
public function active()
{
    $students = $this->studentService->getActiveStudents();
    $count = $students->count();  

    if ($count > 0) {
        return response()->json([
            'message' => 'تم العثور على الطلاب النشطة.',
            'count' => $count,
            'data' => $students
        ], 200);
    } else {
        return response()->json([
            'message' => 'لا يوجد طلاب نشطة حتى الآن.',
            'count' => $count
        ], 200);
    }
}
    
}    
