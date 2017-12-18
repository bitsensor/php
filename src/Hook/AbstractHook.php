<?php

namespace BitSensor\Hook;

use BitSensor\Core\Singleton;
use BitSensor\Util\Log;

/**
 * Class AbstractHook.
 *
 * @author Khanh Nguyen
 * @package BitSensor\Hook
 */
abstract class AbstractHook extends Singleton
{
    const VERSION_REQUIREMENT = "5.0.0";

    private $started = false;

    /**
     * Starts execution hooks.
     */
    public function start()
    {
        $className = str_replace(__NAMESPACE__ . '\\', '', get_called_class());
        if ($this->started) {
            return;
        }
        if (!extension_loaded('uopz')) {
            trigger_error($className . ' not starting because uopz extension is not loaded.',
                E_USER_ERROR);
        }
        if (version_compare(phpversion('uopz'), self::VERSION_REQUIREMENT) < 0)
            trigger_error($className . ' not starting with uopz version (' . phpversion('uopz') . ') lower than ' . self::VERSION_REQUIREMENT,
                E_USER_ERROR);

        $this->started = true;
        $this->init();
        Log::d($className . ' started.');
    }

    /**
     * Removes all execution hooks.
     */
    public function stop()
    {
        $className = str_replace(__NAMESPACE__ . '\\', '', get_called_class());

        if (!$this->started) {
            return;
        }

        $this->destroy();
        $this->started = false;
        Log::d($className . ' stopped.');
    }

    abstract function init();

    abstract function destroy();
}