<?php

namespace App\Repositories;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UserRegistrationException;

use App\Models\User;

class UserRepository
{





    public function create(array $data): User
    {
        return User::create($data);
    }
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }
    




    

  

}
