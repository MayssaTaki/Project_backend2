<?php

namespace App\Services;
use App\Repositories\PasswordResetRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Exceptions\InvalidResetTokenException;





class PasswordResetService
{
   

    protected PasswordResetRepository $repository;


    public function __construct(PasswordResetRepository $repository)
    {
        $this->repository = $repository;
    }

    public function sendResetLink(string $email): string
    {
        $token = Str::random(6);
        $this->repository->storeResetToken($email, $token);

        // يمكنك إرسال الإيميل هنا لاحقًا
        return $token;
    }

    public function resetPassword(array $data): void
    {
        $record = $this->repository->getByEmail($data['email']);

        if (!$record || $record->token !== $data['token']) {
            throw new InvalidResetTokenException('رمز التحقق غير صحيح أو منتهي.');
        }

        $user = User::where('email', $data['email'])->first();
        $user->update(['password' => Hash::make($data['password'])]);

        $this->repository->delete($data['email']);
    }
        
    }

    


