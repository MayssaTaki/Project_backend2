<?php

namespace App\Services;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\UserRepository;
use App\Exceptions\UserRegistrationException;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Arr;
use App\Repositories\Contracts\UserRepositoryInterface;


class UserService
{

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }







    public function register(array $data): User
    {
        try {
            $userData = [
               'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ];

            return $this->userRepository->create($userData);

        } catch (Exception $e) {
            throw new UserRegistrationException('فشل إنشاء حساب المستخدم.');
        }
    }



    public function getAllUsers()
    {
        return $this->userRepository->getAllUsers();
    }


    public function searchUserByFirstName($firstName)
    {
        return $this->userRepository->searchUserByFirstName($firstName);
    }

    public function deleteUser($id)
{
    try {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new Exception('المستخدم غير موجود.');
        }

        if (!$user->hasRole('user')) {
            throw new Exception('لا يمكنك حذف هذا المستخدم.');
        }

        return $this->userRepository->deleteUserById($id);
    } catch (Exception $e) {
        \Log::error('Error deleting user: ' . $e->getMessage()); 
        throw $e;
    }
}

public function countUsers(): int
{
    try {
        return $this->userRepository->countUsersByRole('user');
    } catch (Exception $e) {
        Log::error("Error fetching user count: " . $e->getMessage());
        throw new Exception("حدث خطأ أثناء جلب عدد المستخدمين.");
    }
}


public function update(User $user, array $data): User
{
    $updateData = Arr::only($data, ['name', 'email']);

    if (!empty($data['password'])) {
        $updateData['password'] = Hash::make($data['password']);
    }

    return $this->userRepository->update($user, $updateData);
}





}
