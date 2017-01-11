<?php

namespace Logging;

require_once __DIR__ . '/../../include.php';

use Exception;
use PHPUnit_Framework_TestCase;
use Logging\Logger;
use Logging\Log;
use Logging\Adapters\StreamLogAdapter;
use Logging\Adapters\FileLogAdapter;
use Logging\Adapters\CustomLogAdapter;

class LoggerTest extends PHPUnit_Framework_TestCase {
    function testRun() {
        // Initialize a file logger:
        // $logFile = __DIR__ . '/test-{date}.log';
        // Logger::setAdapter(new FileLogAdapter($logFile));
        
        // Customize output format, if you like:
        // Logger::setAdapter(
        //     new FileLogAdapter($logFile, function ($logParams)  {
        //        ...
        //     }));
        
        // Initialize a logger to log out to STDOUT:
        //Logger::setAdapter(new FileLogAdapter('php://stdout'));
        Logger::setAdapter(new StreamLogAdapter(STDOUT));
       
        /* 
        // Customize logging completely
        Logger::setAdapter(new CustomLogAdapter(function ($logParams) {
            if ($logParams['cause'] !== null) {
                $logParams['cause'] = $logParams['cause']->getMessage();
            }
            
            print_r($logParams);  
        }));
        */
        
        Logger::setDefaultThreshold(Log::DEBUG);
        
        // Get the log instance:
        // It would surely be better to inject/pass the log by constructor
        // $log = Logger::getLog('name-of-logger');
        // $log = Logger::getLog(self::class);
        // $log = Logger::getLog(__CLASS__);
        $log = Logger::getLog($this);
        
        $log->debug('Just a debug message (with no placeholder)');
        $log->info('Hey {name}, just wanna say hello', ['name' => 'Marge']);
        
        $error = new Exception('Evil error', 911);

        // Include error message:
        $log->error(
            'Ooops, there was an error: {errNo}',
            ['err' => $error->getMessage(), 'errNo' => 123]);
        
        // Include error message and stack trace:
        $log->critical(
            'Help, there was a critical error: {exception}',
            ['exception' => $error]);

        // Include error message and stack trace and some extra log data
        $log->emergency(
            'Run for your lives, there was a core melt accident: {exception}',
            ['exception' => $error, 'location' => 'Sector 7G']);
    }
}
