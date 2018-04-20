<?php

namespace BitSensor\Handler;

use BitSensor\Core\BitSensor;
use BitSensor\Exception\ApiException;
use Proto\Datapoint;

/**
 * Handler to run after the PHP script finished. Sends logged data to the BitSensor servers.
 * @package BitSensor\Handler
 */
class AfterRequestHandler extends AbstractHandler
{

    /**
     * Flush output at the end of the request, to enable the browser to
     * render the page, while the connection might still be open.
     *
     * @var boolean
     */
    private static $enableOutputFlushing = false;

    /**
     * Execute fastcgi_finish_request(). Turning this on allows the browser to render the page
     * while BitSensor is still working in the background. A side effect is that output
     * will not be flushed from the shutdown_hooks that run after BitSensor.
     *
     * @var boolean
     */
    private static $executeFastcgiFinishRequest = false;

    /**
     * @param bool $enableOutputFlushing
     */
    public static function setEnableOutputFlushing(bool $enableOutputFlushing)
    {
        self::$enableOutputFlushing = $enableOutputFlushing;
    }

    /** @noinspection PhpDocMissingReturnTagInspection */
    /**
     * @param bool $executeFastcgiFinishRequest
     */
    public static function setExecuteFastcgiFinishRequest($executeFastcgiFinishRequest)
    {
        if ($executeFastcgiFinishRequest && !function_exists('fastcgi_finish_request'))
            return trigger_error("fastcgi is not available, however you wanted to enable it in the BitSensor configuration. 
            Please install fastcgi or disable executeFastCgi", E_USER_WARNING);

        self::$executeFastcgiFinishRequest = $executeFastcgiFinishRequest;
    }

    public function configure($config)
    {
        parent::configure($config);

        if (array_key_exists('outputFlushing', $config))
            self::$enableOutputFlushing = $config['outputFlushing'] == 'on' ? true : false;

        if (array_key_exists('executeFastCgi', $config))
            self::setExecuteFastcgiFinishRequest($config['executeFastCgi'] == 'on' ? true : false);
    }

    /**
     * Extend a Datapoint with more fields
     *
     * @param Datapoint $datapoint
     * @return mixed
     */
    public function doHandle(Datapoint $datapoint)
    {
        // Correctly sets working directory
        chdir(BITSENSOR_WORKING_DIR);

        if (self::$enableOutputFlushing) {
            ob_flush();
            flush();
        }

        if (self::$executeFastcgiFinishRequest) {
            fastcgi_finish_request();
        }

        try {
            BitSensor::finish();
        } catch (ApiException $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
    }
}
