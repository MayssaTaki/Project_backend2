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
}
