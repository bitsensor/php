<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: Error.proto

namespace Proto;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>proto.Error</code>
 */
class Error extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>int32 code = 1;</code>
     */
    private $code = 0;
    /**
     * Generated from protobuf field <code>string description = 2;</code>
     */
    private $description = '';
    /**
     * Generated from protobuf field <code>string location = 3;</code>
     */
    private $location = '';
    /**
     * Generated from protobuf field <code>int32 line = 4;</code>
     */
    private $line = 0;
    /**
     * Generated from protobuf field <code>string type = 5;</code>
     */
    private $type = '';
    /**
     * Generated from protobuf field <code>.proto.GeneratedBy generatedby = 6;</code>
     */
    private $generatedby = 0;
    /**
     * Generated from protobuf field <code>int64 hash = 7;</code>
     */
    private $hash = 0;
    /**
     * Generated from protobuf field <code>repeated string context = 8;</code>
     */
    private $context;

    public function __construct() {
        \GPBMetadata\Error::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>int32 code = 1;</code>
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Generated from protobuf field <code>int32 code = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setCode($var)
    {
        GPBUtil::checkInt32($var);
        $this->code = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string description = 2;</code>
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Generated from protobuf field <code>string description = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setDescription($var)
    {
        GPBUtil::checkString($var, True);
        $this->description = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string location = 3;</code>
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Generated from protobuf field <code>string location = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setLocation($var)
    {
        GPBUtil::checkString($var, True);
        $this->location = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int32 line = 4;</code>
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Generated from protobuf field <code>int32 line = 4;</code>
     * @param int $var
     * @return $this
     */
    public function setLine($var)
    {
        GPBUtil::checkInt32($var);
        $this->line = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string type = 5;</code>
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Generated from protobuf field <code>string type = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setType($var)
    {
        GPBUtil::checkString($var, True);
        $this->type = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.proto.GeneratedBy generatedby = 6;</code>
     * @return int
     */
    public function getGeneratedby()
    {
        return $this->generatedby;
    }

    /**
     * Generated from protobuf field <code>.proto.GeneratedBy generatedby = 6;</code>
     * @param int $var
     * @return $this
     */
    public function setGeneratedby($var)
    {
        GPBUtil::checkEnum($var, \Proto\GeneratedBy::class);
        $this->generatedby = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 hash = 7;</code>
     * @return int|string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Generated from protobuf field <code>int64 hash = 7;</code>
     * @param int|string $var
     * @return $this
     */
    public function setHash($var)
    {
        GPBUtil::checkInt64($var);
        $this->hash = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated string context = 8;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Generated from protobuf field <code>repeated string context = 8;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setContext($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->context = $arr;

        return $this;
    }

}

