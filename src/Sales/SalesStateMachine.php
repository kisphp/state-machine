<?php

namespace Kisphp\Sales;

use Kisphp\Entity\ToggleableInterface;

class SalesStateMachine
{
    const STATE_DELETED = 0;
    const STATE_NEW = 1;
    const STATE_PROCESS = 2;
    const STATE_SEND = 3;
    const STATE_CANCEL = 4;

    /**
     * @var ToggleableInterface
     */
    protected $object;

    /**
     * @var int[]
     */
    protected $states = [];

    /**
     * @var int
     */
    protected $state;

    /**
     * @var array
     */
    protected $transitions = [];

    /**
     * @param int $initialState
     */
    public function __construct(int $initialState = self::STATE_NEW)
    {
        $this->loadDefaultStates();
        $this->setTransitions();
        $this->state = $initialState;
    }

    /**
     * @param ToggleableInterface $object
     */
    public function setObject(ToggleableInterface $object)
    {
        $this->object = $object;
        $this->object->setStatus($this->getCurrentState());
    }

    public function getObject()
    {
        return $this->object;
    }

    protected function setTransitions()
    {
        $this->transitions = [
            self::STATE_DELETED => [],
            self::STATE_NEW => [
                self::STATE_PROCESS,
            ],
            self::STATE_PROCESS => [
                self::STATE_SEND,
                self::STATE_CANCEL,
            ],
            self::STATE_SEND => [
                self::STATE_CANCEL,
            ],
            self::STATE_CANCEL => [
                self::STATE_DELETED,
            ],
        ];
    }

    protected function loadDefaultStates()
    {
        $this->registerState(self::STATE_DELETED);
        $this->registerState(self::STATE_NEW);
        $this->registerState(self::STATE_PROCESS);
        $this->registerState(self::STATE_SEND);
        $this->registerState(self::STATE_CANCEL);
    }

    /**
     * @param int $id
     *
     * @throws StateAlreadyRegistered
     */
    protected function registerState(int $id)
    {
        if (array_key_exists($id, $this->states)) {
            throw new StateAlreadyRegistered();
        }

        $this->states[$id] = $id;
    }

    /**
     * @param int $stateId
     *
     * @throws IllegalStateTransition
     * @throws StateNotFound
     */
    public function setState(int $stateId)
    {
        if (array_key_exists($stateId, $this->states) === false) {
            throw new StateNotFound();
        }

        if (in_array($stateId, $this->transitions[$this->getCurrentState()]) === false) {
            throw new IllegalStateTransition(sprintf('Current state will allow only the following states: %s', implode(', ', $this->transitions[$this->getCurrentState()])));
        }

        $this->state = $this->states[$stateId];
        $this->object->setStatus($stateId);
    }

    /**
     * @return int
     */
    public function getCurrentState() : int
    {
        return $this->state;
    }
}
