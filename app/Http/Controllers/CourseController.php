<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\CourseServiceInterface;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;

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
}
