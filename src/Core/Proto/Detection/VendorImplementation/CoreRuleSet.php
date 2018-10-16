<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: Detection.proto

namespace Proto\Detection\VendorImplementation;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Transient CRS object
 *
 * Generated from protobuf message <code>proto.Detection.VendorImplementation.CoreRuleSet</code>
 */
class CoreRuleSet extends \Google\Protobuf\Internal\Message
{
    /**
     * paranoia level of 1 to 4
     *
     * Generated from protobuf field <code>int64 paranoia = 1;</code>
     */
    private $paranoia = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int|string $paranoia
     *           paranoia level of 1 to 4
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Detection::initOnce();
        parent::__construct($data);
    }

    /**
     * paranoia level of 1 to 4
     *
     * Generated from protobuf field <code>int64 paranoia = 1;</code>
     * @return int|string
     */
    public function getParanoia()
    {
        return $this->paranoia;
    }

    /**
     * paranoia level of 1 to 4
     *
     * Generated from protobuf field <code>int64 paranoia = 1;</code>
     * @param int|string $var
     * @return $this
     */
    public function setParanoia($var)
    {
        GPBUtil::checkInt64($var);
        $this->paranoia = $var;

        return $this;
    }

}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CoreRuleSet::class, \Proto\Detection_VendorImplementation_CoreRuleSet::class);

