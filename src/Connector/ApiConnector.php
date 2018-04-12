<?php

namespace BitSensor\Connector;

use BitSensor\Core\MetaContext;
use BitSensor\Exception\ApiException;
use BitSensor\Util\Log;
use Proto\Datapoint;


/**
 * Handles the connection with the BitSensor servers.
 * Mandatory configuration:
 * ```
 * type: api
 * user: dev
 * apikey: abc123
 * ```
 * Optional configruation:
 * ```
 * host: dev.bitsensor.io, defaults is {user}.bitsensor.io
 * port: 80, default is 8080
 * ```
 * @package BitSensor\Core
 */
class ApiConnector extends AbstractConnector implements Connector
{

    /**
     * @var string BitSensor server host
     */
    private static $host;
    /**
     * @var string User ID
     */
    private static $user;
    /**
     * @var string API key
     */
    private static $apiKey;
    /**
     * @var int BitSensor server port
     */
    private static $port = 8080;

    /**
     * @param string $host The server to send the data to.
     */
    public static function setHost($host)
    {
        self::$host = $host;
    }

    /**
     * @param int $port
     */
    public static function setPort($port)
    {
        self::$port = $port;
    }

    /**
     * @param string $user The ID of the user.
     */
    public static function setUser($user)
    {
        self::$user = $user;
        if (!isset(self::$host))
            self::setHost($user . ".bitsensor.io");
    }

    /**
     * @param string $apiKey The API key used to authenticate with the BitSensor servers.
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    function __construct($optionalConfiguration = null)
    {
        if (!function_exists('curl_exec'))
            trigger_error("curl in PHP is not available, however you wanted to enable it in the BitSensor configuration. 
            Please install php-curl or choose another Connector",
                E_USER_WARNING);

        if ($optionalConfiguration == null)
            return;

        if (array_key_exists('user', $optionalConfiguration))
            self::setUser($optionalConfiguration['user']);

        if (array_key_exists('apikey', $optionalConfiguration))
            self::setApiKey($optionalConfiguration['apikey']);

        if (array_key_exists('host', $optionalConfiguration))
            self::setHost($optionalConfiguration['host']);

        if (array_key_exists('port', $optionalConfiguration))
            self::setPort($optionalConfiguration['port']);
    }

    /**
     * Sends the data to the server.
     *
     * @param Datapoint $datapoint
     * @return mixed
     * @throws ApiException
     */
    public function send(Datapoint $datapoint)
    {
        $this->addAuthentication($datapoint);

        $json = $datapoint->serializeToJsonString();
        list($encryptedJson, $eKeys) = $this->encryptDataPoint($json);

        $content = json_encode(array(
            MetaContext::DATA => base64_encode($encryptedJson),
            MetaContext::ENCRYPTION => true,
            MetaContext::ENCRYPTION_KEY => base64_encode($eKeys[0])
        ));

        $this->logEncrypted($content);

        $request = $this->createCurlRequest($content);

        $result = curl_exec($request);
        $curl_errno = curl_errno($request);
        $curl_error = curl_error($request);

        if ($curl_errno > 0) {
            throw new ApiException("Server connection to BitSensor endpoint failed! $curl_error Code: $curl_errno", ApiException::CONNECTION_FAILED);
        }

        return $result;
    }

    /**
     * @param Datapoint $datapoint
     */
    private function addAuthentication($datapoint)
    {
        $meta = array(
            MetaContext::USER => self::$user,
            MetaContext::API_KEY => self::$apiKey,
        );

        foreach ($meta as $k => $v) {
            $datapoint->getMeta()[$k] = $v;
        }
    }

    /**
     * @param $json string of JSON serialized {@link \Proto\Datapoint}
     * @return array
     */
    private function encryptDataPoint($json)
    {
        $fp = fopen(dirname(__DIR__) . '/bitbrain.pem', 'r');
        $cert = fread($fp, 8192);
        fclose($fp);
        $pubKey = openssl_get_publickey($cert);

        openssl_seal($json, $encryptedJson, $eKeys, array($pubKey));
        openssl_free_key($pubKey);

        return array($encryptedJson, $eKeys);
    }

    /**
     * @param $content
     */
    private function logEncrypted($content)
    {
        Log::d('<pre>' . $content . '</pre>');
    }

    /**
     * @param $content
     * @return resource
     */
    private function createCurlRequest($content)
    {
        $request = curl_init('http://' . self::$host . ':' . self::$port . '/log');
        curl_setopt_array($request, array(
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $content,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($content)
            ),
            CURLOPT_TCP_NODELAY => true,
            CURLOPT_TIMEOUT_MS => 200,
            CURLOPT_NOSIGNAL => true
        ));
        return $request;
    }

}
