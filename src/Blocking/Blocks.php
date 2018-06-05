<?php

namespace BitSensor\Core;


use BitSensor\Core\Blocking\BlockingDatapoint;

class Blocks
{
    /**
     * Blocking identifier
     *
     * @var string
     */
    public $_id;

    /**
     * True if block is enabled
     *
     * @var bool
     */
    public $blocked;

    /**
     * Description of the blocking
     *
     * @var string
     */
    public $description;

    /**
     * Array of datapoints of which one should match
     *
     * @var BlockingDatapoint[]
     */
    public $blockedDatapoints;

}