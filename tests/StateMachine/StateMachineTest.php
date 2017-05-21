<?php

namespace tests\StateMachine;

use Kisphp\Sales\SalesStateMachine;
use PHPUnit\Framework\TestCase;
use tests\Helpers\Order;

class StateMachineTest extends TestCase
{
    public function test_new_state()
    {
        $sm = $this->createStateMachine();

        self::assertEquals(1, $sm->getCurrentState());

        $sm->setState(SalesStateMachine::STATE_PROCESS);
        self::assertEquals(2, $sm->getCurrentState());
    }

    /**
     * @expectedException \Kisphp\Sales\IllegalStateTransition
     */
    public function test_illegal_state()
    {
        $sm = $this->createStateMachine();

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
