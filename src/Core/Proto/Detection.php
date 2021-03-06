<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: Detection.proto

namespace Proto;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>proto.Detection</code>
 */
class Detection extends \Google\Protobuf\Internal\Message
{
    /**
     * System emitting the event
     *
     * Generated from protobuf field <code>string ids = 1;</code>
     */
    private $ids = '';
    /**
     * Name of the detection
     *
     * Generated from protobuf field <code>string name = 2;</code>
     */
    private $name = '';
    /**
     * Description tailored to this event
     *
     * Generated from protobuf field <code>string description = 3;</code>
     */
    private $description = '';
    /**
     * Class of the rule    
     *
     * Generated from protobuf field <code>.proto.Detection.Reason reason = 9;</code>
     */
    private $reason = 0;
    /**
     * Reference to the location, ie. querystring, header
     *
     * Generated from protobuf field <code>string on_key = 16;</code>
     */
    private $on_key = '';
    /**
     * Value that triggered the match
     *
     * Generated from protobuf field <code>string by_input = 17;</code>
     */
    private $by_input = '';
    /**
     * Reference to an hash of an exception or log line
     *
     * Generated from protobuf field <code>repeated string errors = 13;</code>
     */
    private $errors;
    /**
     * Rule that created detection
     *
     * Generated from protobuf field <code>.proto.Detection.Rule rule = 19;</code>
     */
    private $rule = null;
    /**
     * Specifies to what systems the attack is dangerous
     *
     * Generated from protobuf field <code>.proto.Detection.ApplicableTo applicable_to = 22;</code>
     */
    private $applicable_to = null;
    /**
     * Type of the attack, should match Attack
     *
     * Generated from protobuf field <code>string attack = 24;</code>
     */
    private $attack = '';
    /**
     * Mapping to OWASP, PCI, CVE ...
     *
     * Generated from protobuf field <code>.proto.Detection.StandardsMapping standards_mapping = 21;</code>
     */
    private $standards_mapping = null;
    /**
     * Arbitrary tags
     *
     * Generated from protobuf field <code>repeated string tags = 18;</code>
     */
    private $tags;
    /**
     * Generated from protobuf field <code>.proto.Detection.VendorImplementation vendor_implementation = 23;</code>
     */
    private $vendor_implementation = null;
    /**
     * 1.0 = highest business impact, 0.0 = low
     *
     * Generated from protobuf field <code>float severity = 5;</code>
     */
    private $severity = 0.0;
    /**
     * 1.0 = definite attack, 0.0 = def noise
     *
     * Generated from protobuf field <code>float certainty = 6;</code>
     */
    private $certainty = 0.0;
    /**
     * 1.0 = system is vulnerable for this attack, 0.0 means invulnerable
     *
     * Generated from protobuf field <code>float impact = 25;</code>
     */
    private $impact = 0.0;
    /**
     * A-F grade
     *
     * Generated from protobuf field <code>.proto.Grade grade = 20;</code>
     */
    private $grade = 0;
    /**
     * Hash of the ids, name, attack and on_key
     *
     * Generated from protobuf field <code>int64 hash = 14;</code>
     */
    private $hash = 0;
    /**
     * Hash of ids, name and attack
     *
     * Generated from protobuf field <code>int64 rule_hash = 15;</code>
     */
    private $rule_hash = 0;
    /**
     * Generated from protobuf field <code>.proto.GeneratedBy generated_by = 10;</code>
     */
    private $generated_by = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $ids
     *           System emitting the event
     *     @type string $name
     *           Name of the detection
     *     @type string $description
     *           Description tailored to this event
     *     @type int $reason
     *           Class of the rule    
     *     @type string $on_key
     *           Reference to the location, ie. querystring, header
     *     @type string $by_input
     *           Value that triggered the match
     *     @type string[]|\Google\Protobuf\Internal\RepeatedField $errors
     *           Reference to an hash of an exception or log line
     *     @type \Proto\Detection\Rule $rule
     *           Rule that created detection
     *     @type \Proto\Detection\ApplicableTo $applicable_to
     *           Specifies to what systems the attack is dangerous
     *     @type string $attack
     *           Type of the attack, should match Attack
     *     @type \Proto\Detection\StandardsMapping $standards_mapping
     *           Mapping to OWASP, PCI, CVE ...
     *     @type string[]|\Google\Protobuf\Internal\RepeatedField $tags
     *           Arbitrary tags
     *     @type \Proto\Detection\VendorImplementation $vendor_implementation
     *     @type float $severity
     *           1.0 = highest business impact, 0.0 = low
     *     @type float $certainty
     *           1.0 = definite attack, 0.0 = def noise
     *     @type float $impact
     *           1.0 = system is vulnerable for this attack, 0.0 means invulnerable
     *     @type int $grade
     *           A-F grade
     *     @type int|string $hash
     *           Hash of the ids, name, attack and on_key
     *     @type int|string $rule_hash
     *           Hash of ids, name and attack
     *     @type int $generated_by
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Detection::initOnce();
        parent::__construct($data);
    }

    /**
     * System emitting the event
     *
     * Generated from protobuf field <code>string ids = 1;</code>
     * @return string
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * System emitting the event
     *
     * Generated from protobuf field <code>string ids = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setIds($var)
    {
        GPBUtil::checkString($var, True);
        $this->ids = $var;

        return $this;
    }

    /**
     * Name of the detection
     *
     * Generated from protobuf field <code>string name = 2;</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Name of the detection
     *
     * Generated from protobuf field <code>string name = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setName($var)
    {
        GPBUtil::checkString($var, True);
        $this->name = $var;

        return $this;
    }

    /**
     * Description tailored to this event
     *
     * Generated from protobuf field <code>string description = 3;</code>
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Description tailored to this event
     *
     * Generated from protobuf field <code>string description = 3;</code>
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
     * Class of the rule    
     *
     * Generated from protobuf field <code>.proto.Detection.Reason reason = 9;</code>
     * @return int
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Class of the rule    
     *
     * Generated from protobuf field <code>.proto.Detection.Reason reason = 9;</code>
     * @param int $var
     * @return $this
     */
    public function setReason($var)
    {
        GPBUtil::checkEnum($var, \Proto\Detection_Reason::class);
        $this->reason = $var;

        return $this;
    }

    /**
     * Reference to the location, ie. querystring, header
     *
     * Generated from protobuf field <code>string on_key = 16;</code>
     * @return string
     */
    public function getOnKey()
    {
        return $this->on_key;
    }

    /**
     * Reference to the location, ie. querystring, header
     *
     * Generated from protobuf field <code>string on_key = 16;</code>
     * @param string $var
     * @return $this
     */
    public function setOnKey($var)
    {
        GPBUtil::checkString($var, True);
        $this->on_key = $var;

        return $this;
    }

    /**
     * Value that triggered the match
     *
     * Generated from protobuf field <code>string by_input = 17;</code>
     * @return string
     */
    public function getByInput()
    {
        return $this->by_input;
    }

    /**
     * Value that triggered the match
     *
     * Generated from protobuf field <code>string by_input = 17;</code>
     * @param string $var
     * @return $this
     */
    public function setByInput($var)
    {
        GPBUtil::checkString($var, True);
        $this->by_input = $var;

        return $this;
    }

    /**
     * Reference to an hash of an exception or log line
     *
     * Generated from protobuf field <code>repeated string errors = 13;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Reference to an hash of an exception or log line
     *
     * Generated from protobuf field <code>repeated string errors = 13;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setErrors($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->errors = $arr;

        return $this;
    }

    /**
     * Rule that created detection
     *
     * Generated from protobuf field <code>.proto.Detection.Rule rule = 19;</code>
     * @return \Proto\Detection\Rule
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * Rule that created detection
     *
     * Generated from protobuf field <code>.proto.Detection.Rule rule = 19;</code>
     * @param \Proto\Detection\Rule $var
     * @return $this
     */
    public function setRule($var)
    {
        GPBUtil::checkMessage($var, \Proto\Detection_Rule::class);
        $this->rule = $var;

        return $this;
    }

    /**
     * Specifies to what systems the attack is dangerous
     *
     * Generated from protobuf field <code>.proto.Detection.ApplicableTo applicable_to = 22;</code>
     * @return \Proto\Detection\ApplicableTo
     */
    public function getApplicableTo()
    {
        return $this->applicable_to;
    }

    /**
     * Specifies to what systems the attack is dangerous
     *
     * Generated from protobuf field <code>.proto.Detection.ApplicableTo applicable_to = 22;</code>
     * @param \Proto\Detection\ApplicableTo $var
     * @return $this
     */
    public function setApplicableTo($var)
    {
        GPBUtil::checkMessage($var, \Proto\Detection_ApplicableTo::class);
        $this->applicable_to = $var;

        return $this;
    }

    /**
     * Type of the attack, should match Attack
     *
     * Generated from protobuf field <code>string attack = 24;</code>
     * @return string
     */
    public function getAttack()
    {
        return $this->attack;
    }

    /**
     * Type of the attack, should match Attack
     *
     * Generated from protobuf field <code>string attack = 24;</code>
     * @param string $var
     * @return $this
     */
    public function setAttack($var)
    {
        GPBUtil::checkString($var, True);
        $this->attack = $var;

        return $this;
    }

    /**
     * Mapping to OWASP, PCI, CVE ...
     *
     * Generated from protobuf field <code>.proto.Detection.StandardsMapping standards_mapping = 21;</code>
     * @return \Proto\Detection\StandardsMapping
     */
    public function getStandardsMapping()
    {
        return $this->standards_mapping;
    }

    /**
     * Mapping to OWASP, PCI, CVE ...
     *
     * Generated from protobuf field <code>.proto.Detection.StandardsMapping standards_mapping = 21;</code>
     * @param \Proto\Detection\StandardsMapping $var
     * @return $this
     */
    public function setStandardsMapping($var)
    {
        GPBUtil::checkMessage($var, \Proto\Detection_StandardsMapping::class);
        $this->standards_mapping = $var;

        return $this;
    }

    /**
     * Arbitrary tags
     *
     * Generated from protobuf field <code>repeated string tags = 18;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Arbitrary tags
     *
     * Generated from protobuf field <code>repeated string tags = 18;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setTags($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->tags = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.proto.Detection.VendorImplementation vendor_implementation = 23;</code>
     * @return \Proto\Detection\VendorImplementation
     */
    public function getVendorImplementation()
    {
        return $this->vendor_implementation;
    }

    /**
     * Generated from protobuf field <code>.proto.Detection.VendorImplementation vendor_implementation = 23;</code>
     * @param \Proto\Detection\VendorImplementation $var
     * @return $this
     */
    public function setVendorImplementation($var)
    {
        GPBUtil::checkMessage($var, \Proto\Detection_VendorImplementation::class);
        $this->vendor_implementation = $var;

        return $this;
    }

    /**
     * 1.0 = highest business impact, 0.0 = low
     *
     * Generated from protobuf field <code>float severity = 5;</code>
     * @return float
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * 1.0 = highest business impact, 0.0 = low
     *
     * Generated from protobuf field <code>float severity = 5;</code>
     * @param float $var
     * @return $this
     */
    public function setSeverity($var)
    {
        GPBUtil::checkFloat($var);
        $this->severity = $var;

        return $this;
    }

    /**
     * 1.0 = definite attack, 0.0 = def noise
     *
     * Generated from protobuf field <code>float certainty = 6;</code>
     * @return float
     */
    public function getCertainty()
    {
        return $this->certainty;
    }

    /**
     * 1.0 = definite attack, 0.0 = def noise
     *
     * Generated from protobuf field <code>float certainty = 6;</code>
     * @param float $var
     * @return $this
     */
    public function setCertainty($var)
    {
        GPBUtil::checkFloat($var);
        $this->certainty = $var;

        return $this;
    }

    /**
     * 1.0 = system is vulnerable for this attack, 0.0 means invulnerable
     *
     * Generated from protobuf field <code>float impact = 25;</code>
     * @return float
     */
    public function getImpact()
    {
        return $this->impact;
    }

    /**
     * 1.0 = system is vulnerable for this attack, 0.0 means invulnerable
     *
     * Generated from protobuf field <code>float impact = 25;</code>
     * @param float $var
     * @return $this
     */
    public function setImpact($var)
    {
        GPBUtil::checkFloat($var);
        $this->impact = $var;

        return $this;
    }

    /**
     * A-F grade
     *
     * Generated from protobuf field <code>.proto.Grade grade = 20;</code>
     * @return int
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * A-F grade
     *
     * Generated from protobuf field <code>.proto.Grade grade = 20;</code>
     * @param int $var
     * @return $this
     */
    public function setGrade($var)
    {
        GPBUtil::checkEnum($var, \Proto\Grade::class);
        $this->grade = $var;

        return $this;
    }

    /**
     * Hash of the ids, name, attack and on_key
     *
     * Generated from protobuf field <code>int64 hash = 14;</code>
     * @return int|string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Hash of the ids, name, attack and on_key
     *
     * Generated from protobuf field <code>int64 hash = 14;</code>
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
     * Hash of ids, name and attack
     *
     * Generated from protobuf field <code>int64 rule_hash = 15;</code>
     * @return int|string
     */
    public function getRuleHash()
    {
        return $this->rule_hash;
    }

    /**
     * Hash of ids, name and attack
     *
     * Generated from protobuf field <code>int64 rule_hash = 15;</code>
     * @param int|string $var
     * @return $this
     */
    public function setRuleHash($var)
    {
        GPBUtil::checkInt64($var);
        $this->rule_hash = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.proto.GeneratedBy generated_by = 10;</code>
     * @return int
     */
    public function getGeneratedBy()
    {
        return $this->generated_by;
    }

    /**
     * Generated from protobuf field <code>.proto.GeneratedBy generated_by = 10;</code>
     * @param int $var
     * @return $this
     */
    public function setGeneratedBy($var)
    {
        GPBUtil::checkEnum($var, \Proto\GeneratedBy::class);
        $this->generated_by = $var;

        return $this;
    }

}

