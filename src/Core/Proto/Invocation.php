<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: Invocation.proto

namespace Proto;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>proto.Invocation</code>
 */
class Invocation extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated .proto.Invocation.SQLInvocation sqlInvocations = 1;</code>
     */
    private $sqlInvocations;

    public function __construct() {
        \GPBMetadata\Invocation::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>repeated .proto.Invocation.SQLInvocation sqlInvocations = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getSqlInvocations()
    {
        return $this->sqlInvocations;
    }

    /**
     * Generated from protobuf field <code>repeated .proto.Invocation.SQLInvocation sqlInvocations = 1;</code>
     * @param \Proto\Invocation_SQLInvocation[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setSqlInvocations($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Proto\Invocation_SQLInvocation::class);
        $this->sqlInvocations = $arr;

        return $this;
    }

}

