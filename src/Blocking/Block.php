<?php

namespace BitSensor\Core;


use BitSensor\Core\Blocking\BlockDatapoint;

class Block
{
    /**
     * Blocking identifier
     *
     * @var string
     */
    public $id;

    /**
     * True if block is enabled
     *
     * @var bool
     */
    public $active;

    /**
     * Description of the block
     *
     * @var string
     */
    public $description;

    /**
     * Array of datapoints of which one should match
     *
     * @var BlockDatapoint[]
     */
    public $datapoints;

}
