<?php

namespace App\Services;
use App\Repositories\TeacherRepository;
use App\Services\TransactionService;
use App\Exceptions\TeacherUpdateException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;
use Illuminate\Support\Facades\Storage;
use App\Events\TeacherApproved;
use App\Events\TeacherRejected;
use App\Events\TeacherRegistered;
use Exception;
use App\Exceptions\TeacherRegistrationException;
use App\Repositories\UserRepository ;
use App\Services\UserService ;



class TeacherService
{
    protected TeacherRepository $teacherRepository;
    protected UserRepository $userRepository;
    protected UserService $userService;
    protected TransactionService $transactionService;



    public function __construct(TeacherRepository $teacherRepository, UserRepository  $userRepository,UserService  $userService,
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
}
