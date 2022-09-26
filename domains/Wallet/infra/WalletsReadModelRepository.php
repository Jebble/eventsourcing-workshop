<?php

namespace Workshop\Domains\Wallet\Infra;

use Workshop\Domains\Wallet\ReadModels\Wallet;

interface WalletsReadModelRepository
{
    public function increaseBalance(
        string $walletId,
        int $amount
    ): void;

    public function decreaseBalance(
        string $walletId,
        int $amount
    ): void;

    public function getBalance(
        string $walletId,
    ): int;
}
