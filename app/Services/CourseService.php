<?php

namespace App\Services;
use Exception;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Services\Interfaces\CourseServiceInterface;
use Illuminate\Support\Facades\Auth;

class CourseService implements CourseServiceInterface
{
    protected CourseRepositoryInterface $CourseRepository;

    public function __construct(CourseRepositoryInterface $CourseRepository)
    {
        $this->CourseRepository = $CourseRepository;
    }

    public function createCourse(array $data)
    {
        try 
        {
            $data['user_id'] = Auth::id();
            $course = $this->CourseRepository->create($data);
            return response()->json([
                'status' => 'success',
                'message' => 'تم إنشاء الكورس بنجاح.',
                'data' => $course
            ]);
        }

        catch(Exception $ex)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل في إنشاء الكورس: ' . $ex->getMessage()
            ], 500);
        }

    }

    public function updateCourse(array $data)
    {
        try 
        {
            $courseId = $data['course_id'];
            unset($data['course_id']); // Remove course_id from update data
            
            $course = $this->CourseRepository->update($courseId, $data);
            
            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث الكورس بنجاح.',
                'data' => $course
            ]);
        }
        catch(Exception $ex)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل في تحديث الكورس: ' . $ex->getMessage()
            ], 500);
        }
    }

    public function deleteCourse(int $courseId)
    {
        try 
        {            
            $this->CourseRepository->delete($courseId);
            
            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف الكورس بنجاح.'
            ]);
        }
        catch(Exception $ex)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'فشل في حذف الكورس: ' . $ex->getMessage()
            ], 500);
        }
    }

        // app/Services/CourseService.php
    public function getCourseDetails(int $courseId)
    {
        try {
            $course = $this->CourseRepository->findWithRelations($courseId, ['category', 'teacher.user']);
            
            return [
                'status' => 'success',
                'data' => [
                    'id' => $course->id,
                    'teacher_name' => $course->teacher->first_name . ' ' . $course->teacher->last_name,
                    'category_name' => $course->category->name,
                    'name' => $course->name,
                    'price' => $course->price,
                    'description' => $course->description
                ]
            ];
            
        } catch (Exception $ex) {
            return [
                'status' => 'error',
                'message' => 'فشل في جلب تفاصيل الكورس: ' . $ex->getMessage()
            ];
        }
    }

    public function getCoursesByTeacherName(string $teacherName)
    {
        try {
            $courses = $this->CourseRepository->findByTeacherName($teacherName);
            
            return [
                'status' => 'success',
                'data' => $courses->map(function ($course) {
                    return [
                        'course_id' => $course->id,
                        'course_name' => $course->name,
                        'price' => $course->price,
                        'teacher_name' => $course->teacher->first_name . ' ' . $course->teacher->last_name,
                        'category_name' => $course->category->name
                    ];
                })->toArray()
            ];
            
        } catch (Exception $ex) {
            return [
                'status' => 'error',
                'message' => 'Failed to fetch courses: ' . $ex->getMessage()
            ];
        }
    }

    public function getCoursesByCategoryName(string $categoryName)
    {
        try {
            $courses = $this->CourseRepository->findByCategoryName($categoryName);
            
            return [
                'status' => 'success',
                'data' => $courses->map(function ($course) {
                    return [
                        'course_id' => $course->id,
                        'course_name' => $course->name,
                        'price' => $course->price,
                        'teacher_name' => $course->teacher->first_name . ' ' . $course->teacher->last_name,
                        'category_name' => $course->category->name
                    ];
                })->toArray()
            ];
            
        } catch (Exception $ex) {
            return [
                'status' => 'error',
                'message' => 'Failed to fetch courses by category: ' . $ex->getMessage()
            ];
        }
    }

    public function searchCoursesByName(string $courseName)
    {
        try {
            $courses = $this->CourseRepository->searchByName($courseName);
            
            return [
                'status' => 'success',
                'data' => $courses->map(function ($course) {
                    return [
                        'course_id' => $course->id,
                        'course_name' => $course->name,
                        'price' => $course->price,
                        'teacher_name' => $course->teacher->first_name . ' ' . $course->teacher->last_name,
                        'category_name' => $course->category->name
                    ];
                })->toArray()
            ];
            
        } catch (Exception $ex) {
            return [
                'status' => 'error',
                'message' => 'Failed to search courses: ' . $ex->getMessage()
            ];
        }
    }

    public function getCoursesByCategoryId(int $categoryId)
    {
        try {
            $courses = $this->CourseRepository->findByCategoryId($categoryId);
            
            return [
                'status' => 'success',
                'data' => $courses->map(function ($course) {
                    return [
                        'course_id' => $course->id,
                        'course_name' => $course->name,
                        'price' => $course->price,
                        'teacher_name' => $course->teacher->first_name . ' ' . $course->teacher->last_name,
                        'category_name' => $course->category->name
                    ];
                })->toArray()
            ];
            
        } catch (Exception $ex) {
            return [
                'status' => 'error',
                'message' => 'Failed to fetch courses by category ID: ' . $ex->getMessage()
            ];
        }
    }
}
