<?php

namespace BitSensor\Blocking;


use BitSensor\Blocking\Action\BlockingAction;
use BitSensor\Core\Blocking\BlockingDatapoint;
use BitSensor\Core\Blocks;
use Exception;
use Proto\Datapoint;

class Blocking
{
    private static $filePath = "/etc/bitsensor/blocks.json";
    private static $enabled = true;
    private static $action;

    /**
     * @param bool $enabled
     */
    public static function setEnabled($enabled)
    {
        self::$enabled = $enabled;
    }

    /**
     * @param mixed $filePath
     */
    public static function setFilePath($filePath)
    {
        self::$filePath = $filePath;
    }

    /**
     * @param string|string[] $config
     */
    public static function configure($config)
    {
        if (empty($config))
            return;

        if (array_key_exists('enabled', $config))
            self::setEnabled($config['enabled']);

        if (array_key_exists('filePath', $config))
            self::setFilePath($config['filePath']);

        if (array_key_exists('action', $config))
            self::setAction($config['action']);
    }

    /**
     * When set to string, this specifies the connector name.
     * This can be short for a BlockingAction in the {@link \BitSensor\Handler\Blocking} namespace,
     * or fully qualified when in a different namespace.
     *
     * When set to string[], this contains an assoc string[] with configuration, where the
     * 'type' key specifies the connector name.
     *
     * @param string|string[] $action
     */
    public static function setAction($action)
    {
        if (empty($action)) {
            trigger_error("BitSensor is configured without blocking action. Connector configuration should be specified.",
                E_USER_WARNING);
            return;
        }

        /** If configuration is set using an assoc string[] array, pass it along */
        if (is_string($action)) {
            $type = $action;
            $passConfiguration = false;
        } else {
            $type = $action['type'];
            $passConfiguration = true;
        }

        if (strpos($type, '\\')) {
            self::$action = new $type($passConfiguration ? $action : null);
        } else {
            $bitSensorType = '\\BitSensor\\Blocking\\Action\\' . ucfirst($type) . "Action";
            self::$action = new $bitSensorType ($passConfiguration ? $action : null);
        }
    }

    /**
     * @return bool
     */
    public static function isEnabled()
    {
        return self::$enabled;
    }

    /**
     * @return BlockingAction
     */
    public static function getAction()
    {
        return self::$action;
    }

    /**
     * @param Datapoint $datapoint
     * @return boolean|string blockingId
     * @throws Exception to halt script execution
     */
    public function handle(Datapoint $datapoint)
    {
        if (!self::isEnabled())
            return true;

        /** @var Blocks[] $blocks */
        $blocks = json_decode(file_get_contents(self::$filePath, true))->blocks;

        $id = $this->isBlocked($datapoint, $blocks);

        if ($id == false)
            return false;

        self::getAction()->block($datapoint, $id);

        error_log("User blocked by BitSensor for block with id $id", E_USER_ERROR);
        throw new Exception("User blocked by BitSensor for block with id $id");
    }

    /**
     * @param Datapoint $datapoint
     * @param Blocks[] $blocks
     * @return bool|string
     */
    protected function isBlocked(Datapoint $datapoint, $blocks)
    {
        foreach ($blocks as $block) {
            if ($block->blocked != true)
                continue;

            foreach ($block->blockedDatapoints as $blockedDatapoint) {
                if (self::datapointContextMatches($blockedDatapoint, $datapoint) &&
                    self::datapointEndpointMatches($blockedDatapoint, $datapoint) &&
                    self::datapointMetaMatches($blockedDatapoint, $datapoint))
                    return $block->_id;
            }
        }

        return false;
    }

    /**
     * @param BlockingDatapoint $blockedDatapoint just a type hint for object fields
     * @param $datapoint
     * @return bool
     */
    protected static function datapointContextMatches($blockedDatapoint, Datapoint $datapoint)
    {
        if (!empty($blockedDatapoint->context)) {
            foreach ($blockedDatapoint->context as $key => $value) {
                if (!isset($datapoint->getContext()[$key]) || $datapoint->getContext()[$key] != $value)
                    return false;
            }
        }

        return true;
    }

    /**
     * @param BlockingDatapoint $blockedDatapoint just a type hint for object fields
     * @param $datapoint
     * @return bool
     */
    protected static function datapointEndpointMatches($blockedDatapoint, Datapoint $datapoint)
    {
        if (!empty($blockedDatapoint->endpoint)) {
            foreach ($blockedDatapoint->endpoint as $key => $value) {
                if (!isset($datapoint->getEndpoint()[$key]) || $datapoint->getEndpoint()[$key] != $value)
                    return false;
            }
        }

        return true;
    }

    /**
     * @param BlockingDatapoint $blockedDatapoint just a type hint for object fields
     * @param $datapoint
     * @return bool
     */
    protected static function datapointMetaMatches($blockedDatapoint, Datapoint $datapoint)
    {
        if (!empty($blockedDatapoint->meta)) {
            foreach ($blockedDatapoint->meta as $key => $value) {
                if (!isset($datapoint->getMeta()[$key]) || $datapoint->getMeta()[$key] != $value)
                    return false;
            }
        }

        return true;
    }
}