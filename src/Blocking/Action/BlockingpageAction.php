<?php

namespace BitSensor\Blocking\Action;


use Proto\Datapoint;

class BlockingpageAction extends AbstractBlockingAction
{
    private static $host;
    private static $user;
    private static $port = 2080;

    /**
     * @param string[] $optionalConfiguration
     */
    public static function configure($optionalConfiguration = null)
    {
        if ($optionalConfiguration == null)
            return;

        if (array_key_exists('user', $optionalConfiguration))
            self::setUser($optionalConfiguration['user']);

        if (array_key_exists('host', $optionalConfiguration))
            self::setHost($optionalConfiguration['host']);

        if (array_key_exists('port', $optionalConfiguration))
            self::setPort($optionalConfiguration['port']);
    }

    /**
     * @param mixed $host
     */
    public static function setHost($host)
    {
        self::$host = $host;
    }

    /**
     * @param mixed $user
     */
    public static function setUser($user)
    {
        self::$user = $user;
        if (!isset(self::$host))
            self::setHost($user . ".bitsensor.io");
    }

    /**
     * @param mixed $port
     */
    public static function setPort($port)
    {
        self::$port = $port;
    }

    /**
     * Implement action that should be executed upon BlockingHandler
     * decided a request should be blocked.
     *
     * @param Datapoint $datapoint that is blocked
     * @return mixed
     */
    public function doBlock(Datapoint $datapoint)
    {
        ob_clean();

        $curl = curl_init($this->generateUrl($datapoint));
        $blockingPage = curl_exec($curl);
        curl_close($curl);

        print $blockingPage;
    }

    /**
     * @param Datapoint $datapoint
     * @return string
     */
    protected function generateUrl(Datapoint $datapoint)
    {
        return 'http://' . self::$host . ':' . self::$port . '?' . 'blockingId=' . $datapoint->getEndpoint()['blocking.id'];
    }
}