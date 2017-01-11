<?php

namespace Logging\Adapters;

use Exception;
use Logging\Adapters\StreamLogAdapter;

class FileLogAdapter extends StreamLogAdapter {
    function __construct($path, callable $logMessageFormatter = null) {
        $logFilePath = str_replace('{date}', date('Y-m-d'), $path);
        $stream = @fopen($logFilePath, 'ab') ?: null;
        
        if ($stream === null) {
            $error = error_get_last()['message'];
            
            // TODO - is this really good idea?
            throw new Exception(
                "Could not open log file ('$logFilePath'): $error");
        }
        
        parent::__construct($stream, $logMessageFormatter);
    }
    
    function __destruct() {
        @fclose($this->getStream());
    }
}
