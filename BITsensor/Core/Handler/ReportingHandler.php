<?php
namespace BITsensor\Core\Handler;


use Exception;

class ReportingHandler {

    public static function Handle() {
        global $BITsensor;

        //DEBUG OPTION
        echo('<pre>' . $BITsensor->Serialize(true) . '</pre>');
        return;

        // Get the size of the output.
        $size = ob_get_length();

        // Disable compression (in case content length is compressed).
        header("Content-Encoding: none");

        // Set the content length of the response.
        header("Content-Length: {$size}");

        // Close the connection.
        header("Connection: close");

        // Flush all output.
        ob_end_flush();
        ob_flush();
        flush();

        // Close current session (if it exists).
        if (session_id())
            session_write_close();

        try {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "http://bitsensor.bitsaver.nl/api/data/");

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

            curl_setopt($ch, CURLOPT_POSTFIELDS, "=" . urlencode(json_encode($BITsensor->Get())));

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0.1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0.5);

            curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }
}
