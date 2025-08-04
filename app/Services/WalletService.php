<?php
namespace App\Services;

use App\Repositories\WalletRepository;
use App\Models\Wallet;  
use App\Models\Student;
use App\Models\Teacher;

use App\Repositories\Contracts\WalletRepositoryInterface;
use  Illuminate\Support\Facades\DB;

class WalletService
{
    protected $repo;

    public function __construct(WalletRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function chargeStudent($student, $amount)
    {
        if ($amount <= 0) {
            throw new \Exception('قيمة الشحن يجب أن تكون أكبر من صفر');
        }

        $wallet = $this->repo->getByModel($student);
        return $this->repo->addBalance($wallet, $amount);
    }

    public function getBalance($model)
    {
        return $this->repo->getByModel($model)->balance;
    }

    // app/Services/WalletService.php
    public function transferFromStudentToTeacher(int $studentId, int $teacherId, float $amount): void
    {
        if ($amount <= 0) {
            throw new \Exception('المبلغ يجب أن يكون أكبر من صفر');
        }

        $student = Student::findOrFail($studentId);

        $teacher = Teacher::findOrFail($teacherId);

        DB::transaction(function () use ($student, $teacher, $amount) {
            // Get wallets
            $studentWallet = $this->repo->getByModel($student);
            $teacherWallet = $this->repo->getByModel($teacher);

            // Check student balance
            if ($studentWallet->balance < $amount) {
                throw new \Exception('الرصيد غير كافي');
            }

            // Perform transfer
            $studentWallet->balance -= $amount;
            $teacherWallet->balance += $amount;

            // Save changes
            $studentWallet->save();
            $teacherWallet->save();
        });
    }
   
}
