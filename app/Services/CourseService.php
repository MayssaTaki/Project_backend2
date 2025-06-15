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
}
