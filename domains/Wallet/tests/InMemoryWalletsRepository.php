<?php

namespace Workshop\Domains\Wallet\Tests;

use Workshop\Domains\Wallet\Infra\WalletsReadModelRepository;

class InMemoryWalletsRepository implements WalletsReadModelRepository
{
    private int $balance = 0;

    public function increaseBalance(string $walletId, int $amount): void
    {
        $this->balance += $amount;
    }

    public function decreaseBalance(string $walletId, int $amount): void
    {
        $this->balance -= $amount;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }
}
