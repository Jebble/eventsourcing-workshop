<?php

namespace Workshop\Domains\Wallet\Reactors;

use Carbon\Carbon;
use EventSauce\EventSourcing\EventConsumption\EventConsumer;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use Illuminate\Support\Facades\Log;
use Workshop\Domains\Wallet\Events\TokensDeposited;
use Workshop\Domains\Wallet\Events\TokensWithdrawn;
use Workshop\Domains\Wallet\Infra\WalletsReadModelRepository;
use Workshop\Domains\Wallet\ReadModels\Wallet;
use Workshop\Domains\Wallet\Tests\InMemoryNotificationService;

final class WalletsReactor extends EventConsumer
{
    public function __construct(
        private WalletsReadModelRepository $walletsReadModelRepository,
        private InMemoryNotificationService $notificationService
    ) {
    }

    public function handleTokensDeposited(TokensDeposited $event, Message $message): void
    {
        $balance = $this->walletsReadModelRepository->getBalance($message->aggregateRootId()->toString());
        if (
            // $balance <= 100 &&
            $balance + $event->tokens > 100
        ) {
            $this->notificationService->sendWalletHighBalanceNotification($message->aggregateRootId());
        }
    }
}
