<?php

namespace tests\Helpers;

use Kisphp\Entity\ToggleableInterface;

class Order implements ToggleableInterface
{
    /**
     * @var int
     */
    protected $id = 1;

    /**
     * @var int
     */
    protected $status;

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}
