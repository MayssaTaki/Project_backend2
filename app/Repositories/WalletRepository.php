<?php
namespace App\Repositories;

use App\Models\Wallet;
use App\Repositories\Contracts\WalletRepositoryInterface;


class WalletRepository implements WalletRepositoryInterface
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
