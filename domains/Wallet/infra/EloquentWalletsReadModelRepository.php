<?php

namespace Workshop\Domains\Wallet\Infra;

use Workshop\Domains\Wallet\ReadModels\Transaction;
use Workshop\Domains\Wallet\ReadModels\Wallet;

class EloquentWalletsReadModelRepository implements WalletsReadModelRepository
{
    public function increaseBalance(string $walletId, int $amount): void
    {
        $wallet = Wallet::firstOrNew(
            ['wallet_id' => $walletId]
        );
        $wallet->balance += $amount;
        $wallet->save();
    }

    public function decreaseBalance(string $walletId, int $amount): void
    {
        $wallet = Wallet::firstOrNew(
            ['wallet_id' => $walletId]
        );
        $wallet->balance -= $amount;
        $wallet->save();
    }

    public function getBalance(string $walletId): int
    {
        $wallet = Wallet::firstOrFail(
            ['wallet_id' => $walletId]
        );
        return $wallet->balance;
    }
}
