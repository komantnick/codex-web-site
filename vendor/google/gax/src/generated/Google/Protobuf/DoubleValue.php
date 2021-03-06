<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/protobuf/wrappers.proto

namespace Google\Protobuf;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Wrapper message for `double`.
 * The JSON representation for `DoubleValue` is JSON number.
 *
 * Protobuf type <code>Google\Protobuf\DoubleValue</code>
 */
class DoubleValue extends \Google\Protobuf\Internal\Message
{
    /**
     * The double value.
     *
     * Generated from protobuf field <code>double value = 1;</code>
     */
    private $value = 0.0;

    public function __construct() {
        \GPBMetadata\Google\Protobuf\Wrappers::initOnce();
        parent::__construct();
    }

    /**
     * The double value.
     *
     * Generated from protobuf field <code>double value = 1;</code>
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * The double value.
     *
     * Generated from protobuf field <code>double value = 1;</code>
     * @param float $var
     */
    public function setValue($var)
    {
        GPBUtil::checkDouble($var);
        $this->value = $var;
    }

}

