<?php

namespace Logging\Adapters\Internal;

use Exception;
use InvalidArgumentException;
use Throwable;
use Logging\AbstractLog;
use Logging\Log;
use Logging\Logger;
use Logging\LogUtils;

class StreamLog extends AbstractLog {
    private $stream;

    function __construct($name, $stream, callable $logMessageFormatter = null) {
        $this->name = $name;
        $this->stream = $stream;
        $this->logMessageFormatter = $logMessageFormatter;
    }
    
    function log($level, $message, array $context = null) {
        if ($level !== LOG::NONE) {
            if (!LogUtils::isValidLogLevel($level, true)) {
                throw new InvalidArgumentException(
                    '[StreamLog::log] First a rgument $level must be a '
                    . 'valid log level');
            }
            
            if ($this->isEnabled($level)) {
                $output = null;
                $date = date ('Y-m-d H:i:s');
                $levelName = LogUtils::getLogLevelName($level);
                $text = LogUtils::formatLogMessage($message, $context); 
                $name = $this->name;
                
                if ($this->logMessageFormatter !== null) {
                    $formatter = $this->logMessageFormatter;
                    
                    $output = $formatter([
                        'date' => $date,
                        'name' => $name,
                        'level' => $levelName,
                        'code' => $level,
                        'message' => $text,
                        'context' => $context
                    ]);
                } else {
                    $output = "[$date] [$levelName] [$name] $text\n";
                    
                    $exception = @$context['exception'];
                      
                    if (!($exception instanceof \Throwable || $exception instanceof Exception)) {
                        $exception = null;
                    }
                    
                    if (is_array($context)
                        && (count($context) > 0)
                        && (count($context) > 1 || $exception === null)) {
                        
                        $showContext = false; 
                        
                        foreach ($context as $key => $value) {
                            if (!is_scalar($value) || strpos($message, '{' . $key . '}') === false) {
                                $showContext = true;
                                break;
                            }
                        }
                        
                        if ($showContext) {
                            $output .= "---- Context ----\n";
                            
                            foreach ($context as $key => $value) {
                                if ($value instanceof Exception || $value instanceof \Throwable) {
                                    $value = $value->getMessage();
                                } else if (!is_string($value)) {
                                    $value = json_encode($value);
                                }
                                
                                $value = strtr($value, [
                                    "\r\n" => ' ',
                                    "\n" => ' ',
                                    "\r" => ' '
                                ]);
                                
                                $output .= "$key: $value";
                                $output .= "\n";
                            }
                        }
                    }
                    
                    if ($exception !== null) {
                        $output .= "---- Exception ----";
                        $output .= "\nClass: ";
                        $output .= get_class($exception);
                        $output .= "\nMessage: ";
                        $output .= $exception->getMessage();
                        $output .= "\nCode: ";
                        $output .= $exception->getCode();
                        $output .= "\nFile: ";
                        $output .= $exception->getFile();
                        $output .= "\nLine: ";
                        $output .= $exception->getLine();
                        $output .= "\nStack trace:\n";
                        $output .= $exception->getTraceAsString();
                        $output .= "\n";
                    }
                }
                
                fputs($this->stream, $output);
                fflush($this->stream);
            }
        }
    }
    
    function isEnabled($level) {
        return $level >= Logger::getDefaultThreshold();
    }
}
