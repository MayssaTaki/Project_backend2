<?php
namespace App\Repositories\Contracts;
use App\Models\Wallet;

interface WalletRepositoryInterface{

    public function getByModel($model): Wallet;
    public function addBalance(Wallet $wallet, $amount): Wallet;
}