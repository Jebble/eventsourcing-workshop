<?php

namespace Workshop\Domains\Wallet\Tests;

use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;
use EventSauce\EventSourcing\TestUtilities\MessageConsumerTestCase;
use Workshop\Domains\Wallet\Events\TokensDeposited;
use Workshop\Domains\Wallet\Events\TokensWithdrawn;
use Workshop\Domains\Wallet\Projectors\WalletsProjector;
use Workshop\Domains\Wallet\WalletId;

class WalletsProjectorTest extends MessageConsumerTestCase
{
    private WalletId $walletId;
    private InMemoryWalletsRepository $walletsRepository;

    public function setUp(): void
    {
        $this->walletId = WalletId::generate();
        parent::setup();
    }

    /** @test */
    public function it_adds_deposit_to_balance(): void
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
                $this->assertEquals(10, $this->walletsRepository->getBalance());
            });
    }

    /** @test */
    public function it_adds_a_transaction_to_the_transactions_on_withdrawal(): void
    {
        $this
            ->givenNextMessagesHaveAggregateRootIdOf($this->walletId)
            ->given(
                (new Message(
                    new TokensDeposited(10)
                ))->withHeaders([
                    Header::EVENT_ID => 'event-id',
                    Header::TIME_OF_RECORDING => '2022-09-08 13:16:35.790434+0000',
                    Header::TIME_OF_RECORDING_FORMAT => 'Y-m-d H:i:s.uO'
                ])
            )
            ->when(
                (new Message(
                    new TokensWithdrawn(5)
                ))->withHeaders([
                    Header::EVENT_ID => 'event-id',
                    Header::TIME_OF_RECORDING => '2022-09-08 13:16:50.790434+0000',
                    Header::TIME_OF_RECORDING_FORMAT => 'Y-m-d H:i:s.uO'
                ])
            )
            ->then(function () {
                $this->assertEquals(5, $this->walletsRepository->getBalance());
            });
    }

    public function messageConsumer(): MessageConsumer
    {
        $this->walletsRepository = new InMemoryWalletsRepository();
        return new WalletsProjector($this->walletsRepository);
    }
}
