<?php
namespace App\Services;

use App\Repositories\WalletRepository;
use App\Models\Wallet;

class WalletService
{
    protected $repo;

    public function __construct(WalletRepository $repo)
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
   
}
