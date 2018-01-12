<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: Error.proto

namespace Proto;

use Google\Protobuf\Internal\GPBUtil;

/**
 * Protobuf type <code>proto.Error</code>
 */
class Error extends \Google\Protobuf\Internal\Message
{
    /**
     * <code>int32 code = 1;</code>
     */
    private $code = 0;
    /**
     * <code>string description = 2;</code>
     */
    private $description = '';
    /**
     * <code>string location = 3;</code>
     */
    private $location = '';
    /**
     * <code>int32 line = 4;</code>
     */
    private $line = 0;
    /**
     * <code>string type = 5;</code>
     */
    private $type = '';
    /**
     * <code>.proto.GeneratedBy generatedby = 6;</code>
     */
    private $generatedby = 0;
    /**
     * <code>int64 hash = 7;</code>
     */
    private $hash = 0;
    /**
     * <code>repeated string context = 8;</code>
     */
    private $context;

    public function __construct()
    {
        \GPBMetadata\Error::initOnce();
        parent::__construct();
    }

    /**
     * <code>int32 code = 1;</code>
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * <code>int32 code = 1;</code>
     */
    public function setCode($var)
    {
        GPBUtil::checkInt32($var);
        $this->code = $var;
    }

    /**
     * <code>string description = 2;</code>
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * <code>string description = 2;</code>
     */
    public function setDescription($var)
    {
        GPBUtil::checkString($var, True);
        $this->description = $var;
    }

    /**
     * <code>string location = 3;</code>
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * <code>string location = 3;</code>
     */
    public function setLocation($var)
    {
        GPBUtil::checkString($var, True);
        $this->location = $var;
    }

    /**
     * <code>int32 line = 4;</code>
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * <code>int32 line = 4;</code>
     */
    public function setLine($var)
    {
        GPBUtil::checkInt32($var);
        $this->line = $var;
    }

    /**
     * <code>string type = 5;</code>
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * <code>string type = 5;</code>
     */
    public function setType($var)
    {
        GPBUtil::checkString($var, True);
        $this->type = $var;
    }

    /**
     * <code>.proto.GeneratedBy generatedby = 6;</code>
     */
    public function getGeneratedby()
    {
        return $this->generatedby;
    }

    /**
     * <code>.proto.GeneratedBy generatedby = 6;</code>
     */
    public function setGeneratedby($var)
    {
        GPBUtil::checkEnum($var, \Proto\GeneratedBy::class);
        $this->generatedby = $var;
    }

    /**
     * <code>int64 hash = 7;</code>
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * <code>int64 hash = 7;</code>
     */
    public function setHash($var)
    {
        GPBUtil::checkInt64($var);
        $this->hash = $var;
    }

    /**
     * <code>repeated string context = 8;</code>
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * <code>repeated string context = 8;</code>
     */
    public function setContext(&$var)
    {
        GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->context = $var;
    }

}
