<?php

namespace Workshop\Domains\Wallet\Reactors;

use Carbon\Carbon;
use EventSauce\EventSourcing\EventConsumption\EventConsumer;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use Workshop\Domains\Wallet\Events\TokensDeposited;
use Workshop\Domains\Wallet\Events\TokensWithdrawn;
use Workshop\Domains\Wallet\Infra\WalletsReadModelRepository;

final class WalletsReactor extends EventConsumer
{
    public function __construct(
        private WalletsReadModelRepository $walletsReadModelRepository
    ) {
    }

    public function handleTokensDeposited(TokensDeposited $event, Message $message): void
    {
        dd($event);
    }
}
