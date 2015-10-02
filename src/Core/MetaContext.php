<?php

namespace BitSensor\Core;

/**
 * Container for metadata.
 * @package BitSensor\Core
 */
class MetaContext extends Context {

    /**
     * Key for metadata.
     */
    const NAME = 'meta';

    /**
     * Indicates whether the data is encrypted.
     */
    const ENCRYPTION = 'encryption';
    /**
     * Key used for the encryption.
     */
    const ENCRYPTION_KEY = 'key';
    /**
     * The data.
     */
    const DATA = 'data';
    /**
     * The BitSensor username.
     */
    const USER = 'user';
    /**
     * The BitSensor API key.
     */
    const API_KEY = 'apiKey';
    /**
     * The provider used to collect the data.
     */
    const PROVIDER = 'provider';

    const PROVIDER_PHP = 'bitsensor-php';
    const PROVIDER_APACHE = 'apache-log';
    const PROVIDER_NODEJS = 'bitsensor-nodejs';
    const PROVIDER_OTHER = 'other';

    /**
     * MetaContext constructor.
     * @param $key
     * @param $value
     */
    public function __construct($key, $value) {
        $this->setName($key);
        $this->setValue($value);
    }


}