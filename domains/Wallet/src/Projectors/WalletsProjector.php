<?php

namespace Workshop\Domains\Wallet\Projectors;

use Carbon\Carbon;
use EventSauce\EventSourcing\EventConsumption\EventConsumer;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use Workshop\Domains\Wallet\Events\TokensDeposited;
use Workshop\Domains\Wallet\Events\TokensWithdrawn;
use Workshop\Domains\Wallet\Infra\WalletsReadModelRepository;

final class WalletsProjector extends EventConsumer
{
    public function __construct(
        private WalletsReadModelRepository $walletsReadModelRepository
    ) {
    }

    public function handleTokensDeposited(TokensDeposited $event, Message $message): void
    {
        $this->walletsReadModelRepository->increaseBalance(
            $message->aggregateRootId()->toString(),
            $event->tokens,
        );
    }

    public function handleTokensWithdrawn(TokensWithdrawn $event, Message $message): void
    {
        $this->walletsReadModelRepository->decreaseBalance(
            $message->aggregateRootId()->toString(),
            $event->tokens,
        );
    }
}
