<?php

namespace App\Services;
use Exception;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Services\Interfaces\CourseServiceInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseService implements CourseServiceInterface
{
    protected CourseRepositoryInterface $CourseRepository;
    protected WalletService $walletService;

    public function __construct(CourseRepositoryInterface $CourseRepository, WalletService $walletService)
    {
        $this->CourseRepository = $CourseRepository;
        $this->walletService = $walletService;
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
            unset($data['course_id']); 
            
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

    public function getCourseDetails(int $courseId)
    {
        try {
            $course = $this->CourseRepository->findWithRelations($courseId, ['category', 'teacher']);
            
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

    public function registerStudentForCourse(int $courseId, int $studentId)
    {
        try {
          
            $student = Student::findOrFail($studentId);
            if ($student->isBanned()) {
                throw new \Exception('Student is banned and cannot register for courses');
            }

            
            $course = Course::with('teacher')->where('id', $courseId)
                        ->where('accepted', true)
                        ->firstOrFail();

            if($course == null) {
                throw new \Exception('course does not exist');
            }

       
            if ($this->CourseRepository->isStudentRegistered($courseId, $studentId)) {
                throw new \Exception('Student is already registered for this course');
            }

            $this->walletService->transferFromStudentToTeacher(
                $studentId,
                $course->teacher->id,
                $course->price
            );

           
            $registration = $this->CourseRepository->registerStudent($courseId, $studentId);

            return [
                'status' => 'success',
                'message' => 'تم تسجيل الطالب في الكورس بنجاح',
                'registration' => $registration
            ];

        } catch (Exception $ex) {
            return [
                'status' => 'error',
                'message' => 'فشل في التسجيل للكورس: ' . $ex->getMessage()
            ];
        }
    }

    public function uploadCourseVideo(int $courseId, UploadedFile $videoFile): array
    {
        try {
            $validated = validator([
                'video' => $videoFile
            ], [
                'video' => 'required|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska|max:204800' 
            ])->validate();

            $course = $this->CourseRepository->findById($courseId);
            if (!$course) {
                throw new \Exception('Course not found');
            }

            $courseDirectory = 'courses/' . Str::slug($course->name);
            Storage::makeDirectory($courseDirectory);

            $fileNameInPath = time() . '_' . Str::random(10) . '.' . $videoFile->getClientOriginalExtension();


            $path = $videoFile->storeAs($courseDirectory, $fileNameInPath, 'public');

            $video = $this->CourseRepository->createVideo([
                'course_id' => $courseId,
                'video_url' => Storage::url($path),
                'uploaded_at' => now(),
                'file_size' => $videoFile->getSize(),
                'video_name' => $videoFile->getClientOriginalName()
            ]);

            return [
                'status' => 'success',
                'message' => 'تم رفع الفيديو بنجاح',
                'video' => $video
            ];

        } catch (Exception $ex) {
            
            if (isset($path) && Storage::exists($path)) {
                Storage::delete($path);
            }

            return [
                'status' => 'error',
                'message' => 'فشل في رفع الفيديو: ' . $ex->getMessage()
            ];
        }
    }


    public function getCourseVideos(int $courseId): array
    {
        try {
            $videos = $this->CourseRepository->getVideosByCourseId($courseId);
            
            return [
                'status' => 'success',
                'videos' => $videos->map(function ($video) {
                    return [
                        'original_filename' => $video->video_name,
                        'video_url' => url($video->video_url),
                        'uploaded_at' => $video->uploaded_at->format('Y-m-d H:i:s')
                    ];
                })->toArray()
            ];
            
        } catch (Exception $ex) {
            return [
                'status' => 'error',
                'message' => 'Failed to fetch course videos: ' . $ex->getMessage()
            ];
        }
    }

    public function acceptCourse(int $courseId, $user)
    {
    

        return $this->CourseRepository->acceptCourse($courseId);
    }

    public function rejectCourse(int $courseId, $user)
    {
    

        return $this->CourseRepository->rejectCourse($courseId);
    }
        public function getAll(){
     return $this->CourseRepository->getAll();

        }


          public function getStudentsByCourse(int $courseId)
{
         return $this->CourseRepository->getStudentsByCourse($courseId);

}
}
