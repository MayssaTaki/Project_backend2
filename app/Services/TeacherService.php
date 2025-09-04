<?php

namespace App\Services;
use App\Services\TransactionService;
use App\Exceptions\TeacherUpdateException;
use Illuminate\Support\Facades\Hash;
use App\Models\Teacher;
use App\Events\TeacherApproved;
use App\Events\TeacherRejected;
use App\Events\TeacherRegistered;
use Exception;
use App\Exceptions\TeacherRegistrationException;
use App\Repositories\UserRepository ;
use App\Services\UserService ;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Interfaces\TeacherServiceInterface;




class TeacherService implements TeacherServiceInterface
{
    protected TeacherRepositoryInterface $teacherRepository;
    protected UserRepository $userRepository;
    protected UserService $userService;
    protected TransactionService $transactionService;



    public function __construct(TeacherRepositoryInterface $teacherRepository, UserRepositoryInterface  $userRepository,UserService  $userService,
    TransactionService  $transactionService )
    {
        $this->teacherRepository = $teacherRepository;
        $this->userRepository= $userRepository;
        $this->userService= $userService;
        $this->transactionService= $transactionService;



    }
    public function register(array $data): Teacher
    {
        try {
            return $this->transactionService->run(function () use ($data) {
    
                $data['role'] = 'teacher';
    
                $user = $this->userService->register($data);
                $data['user_id'] = $user->id;
    
                if (isset($data['profile_image'])) {
                    $data['profile_image'] = $data['profile_image']->store('profile_images', 'public');
                }
    
                $teacherData = [
                    'user_id' => $data['user_id'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'specialization' => $data['specialization'],
                    'Previous_experiences' => $data['Previous_experiences'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'profile_image' => $data['profile_image'] ?? null,
                    'country' => $data['country'] ?? null,
                    'city' => $data['city'] ?? null,
                    'gender' => $data['gender'],
                ];
    
                $teacher = $this->teacherRepository->create($teacherData);
event(new TeacherRegistered($teacher));
return $teacher;

            });
    
        } catch (Exception $e) {
            throw new TeacherRegistrationException('فشل تسجيل الأستاذ والمستخدم: ' . $e->getMessage());
        }

    }

    public function update(Teacher $teacher, array $data): Teacher
    {
        try {
            return $this->transactionService->run(function () use ($teacher, $data) {
                $user = $teacher->user;

                $userData = [];
                if (isset($data['email'])) {
                    $userData['email'] = $data['email'];
                }

                if (isset($data['password'])) {
                    $userData['password'] = Hash::make($data['password']);
                }

                if (isset($data['first_name']) || isset($data['last_name'])) {
                    $first = $data['first_name'] ?? $teacher->first_name;
                    $last = $data['last_name'] ?? $teacher->last_name;
                    $userData['name'] = $first . ' ' . $last;
                }

                if (!empty($userData)) {
                    if (!$this->userRepository->update($user, $userData)) {
                        throw new TeacherUpdateException('فشل في تحديث بيانات المستخدم.');
                    }
                }

                if (isset($data['profile_image']) && $data['profile_image'] instanceof \Illuminate\Http\UploadedFile) {
                    $data['profile_image'] = $data['profile_image']->store('profile_images', 'public');
                }

                return $this->teacherRepository->update($teacher, $data);
            });

        } catch (\Exception $e) {
            throw new TeacherUpdateException('فشل التحديث: ' . $e->getMessage());
        }
    }


public function getAllTeachers()
    {
        return $this->teacherRepository->getAllTeachers();
    }

    public function search(string $query)
    {
        return $this->teacherRepository->search($query);
    }

    public function countTeachers(): int
    {
        
        return $this->teacherRepository->countTeachers();
    }


    public function approveTeacher($id)
    {
        $teacher = $this->teacherRepository->find($id);

        if ($teacher->status !== 'pending') {
            throw new \Exception('لا يمكن تعديل حالة حساب تم مراجعته مسبقاً.');
        }

         $this->teacherRepository->approve($teacher);
         $teacher->wallet()->create(['balance' => 0]);
         event(new TeacherApproved($teacher));

         return $teacher;

    }

    public function rejectTeacher($id)
    {
        $teacher = $this->teacherRepository->find($id);

        if ($teacher->status !== 'pending') {
            throw new \Exception('لا يمكن تعديل حالة حساب تم مراجعته مسبقاً.');
        }

        $this->teacherRepository->reject($teacher);
        event(new TeacherRejected($teacher));

    }

    public function getApprovedTeachers()
    {
        return $this->teacherRepository->getApprovedTeachers();
    }
    public function getRejectedTeachers()
    {
        return $this->teacherRepository->getRejectedTeachers();
    }
    public function getPendingTeachers()
    {
        return $this->teacherRepository->getPendingTeachers();
    }


    public function evaluateTeacher(int $teacherId, int $studentId, int $evaluationValue): array
    {
        try {
   
            if ($evaluationValue < 0 || $evaluationValue > 5) {
                throw new \Exception('التقييم يجب أن يكون بين 0 و 5');
            }

            $existingEvaluation = $this->teacherRepository->getStudentEvaluation($teacherId, $studentId);

            if ($existingEvaluation) {
                $evaluation = $this->teacherRepository->updateEvaluation(
                    $existingEvaluation->id,
                    $evaluationValue
                );
                $message = 'تم تحديث تقييم الأستاذ بنجاح';
            } else {          
                $evaluation = $this->teacherRepository->createEvaluation([
                    'teacher_id' => $teacherId,
                    'student_id' => $studentId,
                    'evaluation_value' => $evaluationValue
                ]);
                $message = 'تم تقييم الأستاذ بنجاح';
            }

            return [
                'status' => 'success',
                'message' => $message,
                'evaluation' => $evaluation,
                'average_rating' => $this->teacherRepository->getTeacherAverageRating($teacherId)
            ];

        } catch (Exception $ex) {
            return [
                'status' => 'error',
                'message' => 'فشل في التقييم: ' . $ex->getMessage()
            ];
        }
    }

    public function getTeacherAverageRating(int $teacherId): array
    {
        try {
            $teacher = Teacher::where('user_id', $teacherId)->first();
            if (!$teacher) {
                throw new \Exception('Teacher not found');
            }

            $averageRating = $this->teacherRepository->getTeacherAverageRating($teacherId);

            return [
                'status' => 'success',
                'average_rating' => round($averageRating, 1)
            ];

        } catch (Exception $ex) {
            return [
                'status' => 'error',
                'message' => 'Failed to get teacher rating: ' . $ex->getMessage()
            ];
        }
    }
}
