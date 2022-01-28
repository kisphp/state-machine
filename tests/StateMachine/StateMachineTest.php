<?php

namespace Tests\StateMachine;

use Kisphp\Sales\IllegalStateTransition;
use Kisphp\Sales\SalesStateMachine;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\Order;

class StateMachineTest extends TestCase
{
    public function test_new_state()
    {
        $sm = $this->createStateMachine();

        self::assertEquals(1, $sm->getCurrentState());

        $sm->setState(SalesStateMachine::STATE_PROCESS);
        self::assertEquals(2, $sm->getCurrentState());
    }

    public function test_illegal_state()
    {
        $sm = $this->createStateMachine();

        $this->expectException(IllegalStateTransition::class);

        self::assertSame(1, $sm->getCurrentState());
        $sm->setState(SalesStateMachine::STATE_PROCESS);

        self::assertSame(2, $sm->getCurrentState());
        $sm->setState(SalesStateMachine::STATE_NEW);
    }

    /**
     * @return SalesStateMachine
     */
    protected function createStateMachine(): SalesStateMachine
    {
        $sm = new SalesStateMachine(SalesStateMachine::STATE_NEW);
        $sm->setObject(new Order());

        return $sm;
    }
}
