<?php

namespace BitSensor\Core;


/**
 * Information about a file.
 * @package BitSensor\Core
 * @see $_FILES
 */
class FileContext extends Constants
{
    /**
     * File information.
     */
    const NAME = 'file';
    /**
     * The original name of the file on the client machine.
     */
    const FILENAME = 'name';
    /**
     * The mime type of the file, if the browser provided this information.
     */
    const FILE_TYPE = 'type';
    /**
     * The size, in bytes, of the uploaded file.
     */
    const FILE_SIZE = 'size';
    /**
     * The temporary filename of the file in which the uploaded file was stored on the server.
     */
    const TMP_FILENAME = 'tmp_name';
    /**
     * The error code associated with this file upload.
     */
    const ERROR_CODE = 'error';

}