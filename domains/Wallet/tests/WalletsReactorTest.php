<?php

namespace Workshop\Domains\Wallet\Tests;

use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\TestUtilities\MessageConsumerTestCase;
use Workshop\Domains\Wallet\Events\TokensDeposited;
use Workshop\Domains\Wallet\Events\TokensWithdrawn;
use Workshop\Domains\Wallet\Projectors\WalletsProjector;
use Workshop\Domains\Wallet\Reactors\WalletsReactor;
use Workshop\Domains\Wallet\WalletId;

class WalletsReactorTest extends MessageConsumerTestCase
{
    private WalletId $walletId;
    private InMemoryWalletsRepository $walletsRepository;
    private InMemoryNotificationService $notificationService;

    public function setUp(): void
    {
        $this->walletId = WalletId::generate();
        parent::setup();
    }

    /** @test */
    public function it_does_not_send_notification_if_balance_below_100(): void
    {
        $this
            ->givenNextMessagesHaveAggregateRootIdOf($this->walletId)
            ->when(
                (new Message(
                    new TokensDeposited(10)
                ))->withHeaders([
                    Header::EVENT_ID => 'event-id',
                    Header::TIME_OF_RECORDING => '2022-09-08 13:16:35.790434+0000',
                    Header::TIME_OF_RECORDING_FORMAT => 'Y-m-d H:i:s.uO'
                ])
            )
            ->then(function () {
                $this->assertFalse($this->notificationService->notificationSendExactlyOnceForWallet($this->walletId));
            });
    }

    /** @test */
    public function it_sends_notification_if_balance_goes_above_100(): void
    {
        $this
            ->givenNextMessagesHaveAggregateRootIdOf($this->walletId)
            ->when(
                (new Message(
                    new TokensDeposited(101)
                ))->withHeaders([
                    Header::EVENT_ID => 'event-id',
                    Header::TIME_OF_RECORDING => '2022-09-08 13:16:35.790434+0000',
                    Header::TIME_OF_RECORDING_FORMAT => 'Y-m-d H:i:s.uO'
                ])
            )
            ->then(function () {
                $this->assertTrue($this->notificationService->notificationSendExactlyOnceForWallet($this->walletId));
            });
    }

    /** @test */
    public function it_doesnt_send_notification_if_already_above_100(): void
    {
        // This is flaky, the balance for `given` isn't 101 in the `when`
        $this
            ->givenNextMessagesHaveAggregateRootIdOf($this->walletId)
            ->given(
                (new Message(
                    new TokensDeposited(101)
                ))->withHeaders([
                    Header::EVENT_ID => 'event-id',
                    Header::TIME_OF_RECORDING => '2022-09-08 13:16:35.790434+0000',
                    Header::TIME_OF_RECORDING_FORMAT => 'Y-m-d H:i:s.uO'
                ])
            )
            ->when(
                (new Message(
                    new TokensDeposited(10)
                ))->withHeaders([
                    Header::EVENT_ID => 'event-id',
                    Header::TIME_OF_RECORDING => '2022-09-08 13:16:50.790434+0000',
                    Header::TIME_OF_RECORDING_FORMAT => 'Y-m-d H:i:s.uO'
                ])
            )
            ->then(function () {
                $this->assertTrue($this->notificationService->notificationSendExactlyOnceForWallet($this->walletId));
            });
    }

    public function messageConsumer(): MessageConsumer
    {
        $this->walletsRepository = new InMemoryWalletsRepository();
        $this->notificationService = new InMemoryNotificationService();
        return new WalletsReactor($this->walletsRepository, $this->notificationService);
    }
}
