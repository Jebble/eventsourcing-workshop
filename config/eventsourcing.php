<?php

use Workshop\Domains\Wallet\WalletId;
use Workshop\Domains\Wallet\Events\TokensDeposited;
use Workshop\Domains\Wallet\Events\TokensWithdrawn;
use Workshop\Domains\Wallet\Events\TokenWithdrawalFailed;

return [
    'class_map' => [
        Wallet::class => [
            'wallet',
            'workshop.domains.wallet.wallet',
        ],
        WalletId::class => [
            'wallet_id',
            'workshop.domains.wallet.wallet_id',
        ],
        TokensDeposited::class => [
            'tokens_deposited',
            'workshop.domains.wallet.events.tokens_deposited',
        ],
        TokensWithdrawn::class => [
            'tokens_withdrawn',
            'workshop.domains.wallet.events.tokens_withdrawn',
        ],
        TokenWithdrawalFailed::class => [
            'token_withdrawal_failed',
            'workshop.domains.wallet.events.token_withdrawal_failed',
        ],
    ]
];
