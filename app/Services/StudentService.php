<?php

namespace App\Services;
use App\Repositories\StudentRepository;

use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Exceptions\StudentRegistrationException;
use App\Repositories\UserRepository ;
use App\Services\UserService ;
use App\Services\TransactionService;




class StudentService
{
    protected StudentRepository $StudentRepository;
    protected UserRepository $userRepository;
    protected UserService $userService;
    protected TransactionService $transactionService;



    public function __construct(StudentRepository $studentRepository, UserRepository  $userRepository,UserService  $userService,
    TransactionService  $transactionService  )
    {
        $this->studentRepository = $studentRepository;
        $this->userRepository= $userRepository;
        $this->userService= $userService;
        $this->transactionService= $transactionService;



    }

    public function register(array $data): Student
    {
        try {
            return $this->transactionService->run(function () use ($data) {

            $data['role'] = 'student';
            $user = $this->userService->register($data);
            $data['user_id'] = $user->id;
            if (isset($data['profile_image'])) {
                $data['profile_image'] = $data['profile_image']->store('profile_images', 'public');
            }
            $studentData = [
                'user_id' => $data['user_id'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'date_of_birth' => $data['date_of_birth'],
                'phone' => $data['phone'] ?? null,
                'profile_image' => $data['profile_image'] ?? null,
                'country' => $data['country'] ?? null,
                'city' => $data['city'] ?? null,
                'gender' => $data['gender'],
                
            ];
    
            $student = $this->studentRepository->create($studentData);

            return $student;  });
        } catch (Exception $e) {
            throw new StudentRegistrationException($e->getMessage());
        }
        
    }

    
public function getAllStudent()
{
    return $this->studentRepository->getAllStudents();
}

public function search(string $query)
{
    return $this->studentRepository->search($query);
}

public function update(Student $student, array $data): Student
    {
        try {
            return $this->transactionService->run(function () use ($student, $data) {
                $user = $student->user;

                $userData = [];
                if (isset($data['email'])) {
                    $userData['email'] = $data['email'];
                }
                if (isset($data['password'])) {
                    $userData['password'] = Hash::make($data['password']);
                }
                if (isset($data['first_name']) || isset($data['last_name'])) {
                    $first = $data['first_name'] ?? $student->first_name;
                    $last = $data['last_name'] ?? $student->last_name;
                    $userData['name'] = $first . ' ' . $last;
                }

                if (!empty($userData)) {
                    if (!$this->userRepository->update($user, $userData)) {
                        throw new StudentUpdateException('فشل في تحديث بيانات المستخدم.');
                    }
                }

                return $this->studentRepository->update($student, $data);
            });
        } catch (\Exception $e) {
            throw new StudentUpdateException('فشل التحديث: ' . $e->getMessage());
        }
    }

    public function countStudents(): int
    {
        
        return $this->studentRepository->countStudents();
    }

    
    public function ban($id): Student
    {
        $student = $this->studentRepository->find($id);
        $this->studentRepository->ban($student);
        return $student;
    }

    public function unban($id): Student
    {
        $student = $this->studentRepository->find($id);
        $this->studentRepository->unban($student);
        return $student;
    }

    public function checkIfBanned($id): bool
    {
        $student = $this->studentRepository->find($id);
        return $this->studentRepository->isBanned($student);
    }

    public function getActiveStudents()
    {
        return $this->studentRepository->getActiveStudents();
    }

    public function getBanStudents()
    {
        return $this->studentRepository->getBanStudents();
    }
}
