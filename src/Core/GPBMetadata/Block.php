<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: Block.proto

namespace GPBMetadata;

class Block
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \GPBMetadata\Datapoint::initOnce();
        $pool->internalAddGeneratedFile(hex2bin(
            "0adf010a0b426c6f636b2e70726f746f120570726f746f229b010a05426c" .
            "6f636b120a0a026964180120012809120e0a066163746976651802200128" .
            "0812130a0b6465736372697074696f6e18032001280912110a0963726561" .
            "746564427918042001280912140a0c6372656174696f6e44617465180520" .
            "01280312120a0a7570646174654461746518062001280312240a0a646174" .
            "61706f696e747318072003280b32102e70726f746f2e44617461706f696e" .
            "7442210a1d696f2e62697473656e736f722e6c69622e656e746974792e70" .
            "726f746f50015000620670726f746f33"
        ));

        static::$is_initialized = true;
    }
}
