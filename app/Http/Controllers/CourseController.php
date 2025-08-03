<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\CourseServiceInterface;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected CourseServiceInterface $courseService;

    public function __construct(CourseServiceInterface $courseService)
    {
        $this->courseService = $courseService;
    }

    public function addCourse(StoreCourseRequest $request)
    {
        try {
            $validated = $request->validated();
            $course = $this->courseService->createCourse($validated);
            
            return response()->json([
                'status' => 'success',
                'course' => $course
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create course'
            ], 500);
        }
    }

    public function updateCourse(UpdateCourseRequest $request) {
        try {
            $validated = $request->validated();
            $course = $this->courseService->updateCourse($validated);
            
            return response()->json([
                'status' => 'success',
                'course' => $course
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update course'
            ], 500);
        }
    }

    public function deleteCourse(int $courseId)
    {
        try {
            $this->courseService->deleteCourse($courseId);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Course deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete course: ' . $e->getMessage()
            ], 500);
        }
    }

    // app/Http/Controllers/CourseController.php
    public function getCourseDetails($courseId)
    {
        $result = $this->courseService->getCourseDetails($courseId);
        
        return response()->json(
            $result,
            $result['status'] === 'error' ? 400 : 200
        );
    }

    public function getCoursesByTeacherName(Request $request)
    {
        $request->validate([
            'teacher_name' => 'required|string|min:2'
        ]);
        
        $result = $this->courseService->getCoursesByTeacherName($request->teacher_name);
        
        return response()->json(
            $result,
            $result['status'] === 'error' ? 400 : 200
        );
    }

    public function getCoursesByCategoryName(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|min:2'
        ]);
        
        $result = $this->courseService->getCoursesByCategoryName($request->category_name);
        
        return response()->json(
            $result,
            $result['status'] === 'error' ? 400 : 200
        );
    }

    public function searchCoursesByName(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|min:2'
        ]);
        
        $result = $this->courseService->searchCoursesByName($request->course_name);
        
        return response()->json(
            $result,
            $result['status'] === 'error' ? 400 : 200
        );
    }
    
    public function getCoursesByCategoryId($categoryId)
    {
        $result = $this->courseService->getCoursesByCategoryId($categoryId);
        
        return response()->json(
            $result,
            $result['status'] === 'error' ? 400 : 200
        );
    }

    public function registerForCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer|exists:courses,id'
        ]);

        $student = auth()->user()->student;
        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'المستخدم ليس طالبًا'
            ], 403);
        }

        $result = $this->courseService->registerStudentForCourse(
            $request->course_id, 
            $student->id
        );

        return response()->json(
            $result,
            $result['status'] === 'error' ? 400 : 200
        );
    }
}
