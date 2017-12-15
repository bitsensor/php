<?php

namespace BitSensor\Hook;

use BitSensor\Core\Singleton;

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
        if (!extension_loaded('uopz') || $this->started) {
            trigger_error('uopz extension is not loaded. Skipped hooks', E_USER_ERROR);
            return;
        }
        if (version_compare(phpversion('uopz'), self::VERSION_REQUIREMENT) < 0)
            trigger_error("Hooks not starting with 'uopz' version (" . phpversion('uopz') . ") lower than " . self::VERSION_REQUIREMENT,
                E_USER_WARNING);

        $this->started = true;

        $this->init();
    }

    /**
     * Removes all execution hooks.
     */
    public function stop()
    {
        if (!$this->started)
            return;

        $this->started = false;

        $this->destroy();
    }

    abstract function init();

    abstract function destroy();
}