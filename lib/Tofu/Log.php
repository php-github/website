<?php
namespace Tofu;
class Log
{
    protected static function buildBacktrace()
    {
        $arrBacktrace = debug_backtrace();
        $strBacktrace = '';
        if (!is_array($arrBacktrace) || 0 === count($arrBacktrace)) {
            return $strBacktrace;
        }
        $intNum = 0;
        foreach ($arrBacktrace as $arrItem) {
            if (empty($arrItem['file']) || empty($arrItem['line'])) {
                continue;
            }
            if (isset($arrItem['class']) && __CLASS__ === $arrItem['class']) {
                continue;
            }
            $arrArgs = array();
            if (!isset($arrItem['args'])) {
                continue;
            }
            foreach ($arrItem['args'] as $mixArg) {
                $arrArgs[] = str_replace(array(' ', "\n"), '', var_export($mixArg, true));
            }
            $strArgs = str_replace("\n", '', implode(', ', $arrArgs));
            $strBacktrace .= sprintf("#%s %s(%s): %s(%s)\n", $intNum++, $arrItem['file'], $arrItem['line'], $arrItem['function'], $strArgs);
        }
        return trim($strBacktrace);
    }

    public static function errorHandler($constErrno, $strErrorMessage, $strErrorFile, $intErrorLine)
    {
        $bolNeedBacktrace = true;
        switch ($constErrno) {
            case E_USER_ERROR:
                $strErrorType = 'Fatal error';
                break;
            case E_USER_WARNING:
                $strErrorType = 'Warning';
                break;
            case E_USER_NOTICE:
                $strErrorType = 'Notice';
                $bolNeedBacktrace = false;
                break;
            case E_ERROR:
                $strErrorType = 'Fatal error';
                break;
            case E_WARNING:
                $strErrorType = 'Warning';
                break;
            case E_NOTICE:
                $strErrorType = 'Notice';
                $bolNeedBacktrace = false;
                break;
            default:
                $strErrorType = 'Unknown';
                break;
        }
        $strBacktrace = '';
        if ($bolNeedBacktrace) {
            $strBacktrace = self::buildBacktrace();
            if ($strBacktrace) {
                $strBacktrace = "\n".$strBacktrace;
            }
        }
        $strLog = sprintf(": %s in %s on line %s%s", $strErrorMessage, $strErrorFile, $intErrorLine, $strBacktrace);
        //日志写到php系统日志中
        error_log("PHP {$strErrorType}$strLog");
        /*
        //如果打开了错误报告，打印出日志
        if (ini_get("error_reporting")) {
            $strPrintLog = sprintf("\n<b>%s</b>:  %s in <b>%s</b> on line <b>%s</b>\n%s\n", $strErrorType, $strErrorMessage, $strErrorFile, $intErrorLine, $strBacktrace);
            //$strPrintLog = str_replace("\n", "</br>", $strPrintLog);
            printf($strPrintLog);
        }
        */
        return true;
    }

    public function init()
    {
        set_error_handler(array('Tofu\Log', 'errorHandler'));
    }
}
