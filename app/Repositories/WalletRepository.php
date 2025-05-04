<?php
namespace App\Repositories;

use App\Models\Wallet;

class WalletRepository
{
    public function getByModel($model): Wallet
    {
        return $model->wallet()->firstOrCreate([]);
    }

    public function addBalance(Wallet $wallet, $amount): Wallet
    {
        $wallet->balance += $amount;
        $wallet->save();
        return $wallet;
    }
}
