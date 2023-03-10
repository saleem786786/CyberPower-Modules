<?php

/* * ********************************************************************
 * HetznerVps product developed. (2016-12-15)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\Servers\HetznerVps\App\Service;

use Illuminate\Database\Capsule\Manager as DB;

/**
 * Description of Utility
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 * @version 1.0.0
 */
class Utility
{

    public static function unitFormat(&$value, $inUnit, $outUnit)
    {
        if (!in_array($inUnit, ['bytes', 'mb', 'gb']))
        {
            $debug = debug_backtrace();
            throw new \Exception(sprintf("Unit value ('%s') is invalid. File: %s:%s", $inUnit, $debug[0]['file'],$debug[0]['line'] ));
        }

        if (!in_array($outUnit, ['mb', 'gb', 'bytes']))
        {
            $debug = debug_backtrace();
            throw new \Exception(sprintf("Unit value ('%s') is invalid. File: %s:%s", $outUnit, $debug[0]['file'],$debug[0]['line']));
        }

        if ($value == 0)
        {
            return;
        }
        if (empty($value) || !is_numeric($value))
        {
            $debug = debug_backtrace();
            throw new \Exception(sprintf("Unit value ('%s') is invalid. File: %s:%s", $value, $debug[0]['file'],$debug[0]['line']));
        }

        if ($inUnit == 'mb' && $outUnit == 'gb' && $value < 1024)
        {
            $debug = debug_backtrace();
            throw new \Exception(sprintf("Unit value %sMB is smaller than 1 GB. File: %s:%s", $value, $debug[0]['file'],$debug[0]['line']));
        }

        if ($inUnit == $outUnit)
        {
            return;
        }
        else
        {
            if ($inUnit == 'bytes' && $outUnit == 'mb')
            {
                $value /= pow(1024, 2);
            }
            else
            {
                if ($inUnit == 'bytes' && $outUnit == 'gb')
                {
                    $value /= pow(1024, 3);
                }
                else
                {
                    if ($inUnit == 'mb' && $outUnit == 'gb')
                    {
                        $value = ceil($value / 1024);
                    }
                    else
                    {
                        if ($inUnit == 'gb' && $outUnit == 'mb')
                        {
                            $value *= 1024;
                        }
                        else
                        {
                            if ($inUnit == 'gb' && $outUnit == 'bytes')
                            {
                                $value *= pow(1024, 3);
                            }
                            else
                            {
                                if ($inUnit == 'mb' && $outUnit == 'bytes')
                                {
                                    $value *= pow(1024, 2);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    static function timeStamp($strTime = 'now')
    {
        return date('Y-m-d H:i:s', strtotime($strTime));
    }

    static function obClean()
    {
        $outputBuffering = ob_get_contents();
        if ($outputBuffering !== false)
        {
            if (!empty($outputBuffering))
            {
                ob_clean();
            }
            else
            {
                ob_start();
            }
        }
        http_response_code(200);
    }

    /**
     * FUNCTION MG_uptime
     * Calculate uptime
     * @param int $uptime
     * @return boolean
     */
    public static function uptime($uptime)
    {
        if (!$uptime)
        {
            return false;
        }
        $days  = floor($uptime / 60 / 60 / 24);
        $hours = $uptime / 60 / 60 % 24;
        $mins  = $uptime / 60 % 60;
        $secs  = $uptime % 60;
        $hours = ($hours < 10) ? "0" . $hours : $hours;
        $mins  = ($mins < 10) ? "0" . $mins : $mins;
        $secs  = ($secs < 10) ? "0" . $secs : $secs;
        if ($days)
        {
            return "{$days} days $hours:$mins:$secs";
        }
        else
        {
            return "$hours:$mins:$secs";
        }
    }

    public static function generatePassword($length = 8, $chars = "")
    {
        if (!$chars)
        {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        }
        $count = mb_strlen($chars);
        for ($i = 0, $result = ''; $i < $length; $i++)
        {
            $index  = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }
        return $result;
    }


    public static function isAddon($name)
    {
        if (DB::table('tbladdonmodules')->where("module", $name)->count())
        {
            $file = ROOTDIR . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $name . '.php';
            return file_exists($file);
        }
        return false;
    }

    static function isIpManagerHetznerVpsIntegration()
    {
        if (!self::isAddon('ipmanager2'))
        {
            return false;
        }
        return DB::table('ip_manager_modules')->where("modulename", "HetznerVpsIntegration")->where("enabled", "1")->count();
    }

    static function isIpManagerHetznerCloudIntegration()
    {
        if (!self::isAddon('ipmanager2'))
        {
            return false;
        }
        return DB::table('ip_manager_modules')->where("modulename", "HetznerCloudIntegration")->where("enabled", "1")->count();
    }

    static function replaceSpecialChars($string)
    {
        $replace = [
            '&lt;'   => '', '&gt;' => '', '&#039;' => '', '&amp;' => '',
            '&quot;' => '', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'Ae',
            '&Auml;' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'Ae',
            '??'      => 'C', '??' => 'C', '??' => 'C', '??' => 'C', '??' => 'C', '??' => 'D', '??' => 'D',
            '??'      => 'D', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'E',
            '??'      => 'E', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'G', '??' => 'G',
            '??'      => 'G', '??' => 'G', '??' => 'H', '??' => 'H', '??' => 'I', '??' => 'I',
            '??'      => 'I', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I',
            '??'      => 'I', '??' => 'IJ', '??' => 'J', '??' => 'K', '??' => 'K', '??' => 'K',
            '??'      => 'K', '??' => 'K', '??' => 'K', '??' => 'N', '??' => 'N', '??' => 'N',
            '??'      => 'N', '??' => 'N', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O',
            '??'      => 'Oe', '&Ouml;' => 'Oe', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O',
            '??'      => 'OE', '??' => 'R', '??' => 'R', '??' => 'R', '??' => 'S', '??' => 'S',
            '??'      => 'S', '??' => 'S', '??' => 'S', '??' => 'T', '??' => 'T', '??' => 'T',
            '??'      => 'T', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'Ue', '??' => 'U',
            '&Uuml;' => 'Ue', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U',
            '??'      => 'W', '??' => 'Y', '??' => 'Y', '??' => 'Y', '??' => 'Z', '??' => 'Z',
            '??'      => 'Z', '??' => 'T', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a',
            '??'      => 'ae', '&auml;' => 'ae', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a',
            '??'      => 'ae', '??' => 'c', '??' => 'c', '??' => 'c', '??' => 'c', '??' => 'c',
            '??'      => 'd', '??' => 'd', '??' => 'd', '??' => 'e', '??' => 'e', '??' => 'e',
            '??'      => 'e', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e',
            '??'      => 'f', '??' => 'g', '??' => 'g', '??' => 'g', '??' => 'g', '??' => 'h',
            '??'      => 'h', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i',
            '??'      => 'i', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'ij', '??' => 'j',
            '??'      => 'k', '??' => 'k', '??' => 'l', '??' => 'l', '??' => 'l', '??' => 'l',
            '??'      => 'l', '??' => 'n', '??' => 'n', '??' => 'n', '??' => 'n', '??' => 'n',
            '??'      => 'n', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'oe',
            '&ouml;' => 'oe', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'oe',
            '??'      => 'r', '??' => 'r', '??' => 'r', '??' => 's', '??' => 'u', '??' => 'u',
            '??'      => 'u', '??' => 'ue', '??' => 'u', '&uuml;' => 'ue', '??' => 'u', '??' => 'u',
            '??'      => 'u', '??' => 'u', '??' => 'u', '??' => 'w', '??' => 'y', '??' => 'y',
            '??'      => 'y', '??' => 'z', '??' => 'z', '??' => 'z', '??' => 't', '??' => 'ss',
            '??'      => 'ss', '????' => 'iy', '??' => 'A', '??' => 'B', '??' => 'V', '??' => 'G',
            '??'      => 'D', '??' => 'E', '??' => 'YO', '??' => 'ZH', '??' => 'Z', '??' => 'I',
            '??'      => 'Y', '??' => 'K', '??' => 'L', '??' => 'M', '??' => 'N', '??' => 'O',
            '??'      => 'P', '??' => 'R', '??' => 'S', '??' => 'T', '??' => 'U', '??' => 'F',
            '??'      => 'H', '??' => 'C', '??' => 'CH', '??' => 'SH', '??' => 'SCH', '??' => '',
            '??'      => 'Y', '??' => '', '??' => 'E', '??' => 'YU', '??' => 'YA', '??' => 'a',
            '??'      => 'b', '??' => 'v', '??' => 'g', '??' => 'd', '??' => 'e', '??' => 'yo',
            '??'      => 'zh', '??' => 'z', '??' => 'i', '??' => 'y', '??' => 'k', '??' => 'l',
            '??'      => 'm', '??' => 'n', '??' => 'o', '??' => 'p', '??' => 'r', '??' => 's',
            '??'      => 't', '??' => 'u', '??' => 'f', '??' => 'h', '??' => 'c', '??' => 'ch',
            '??'      => 'sh', '??' => 'sch', '??' => '', '??' => 'y', '??' => '', '??' => 'e',
            '??'      => 'yu', '??' => 'ya'
        ];
        return str_replace(array_keys($replace), $replace, $string);
    }

    public static  function cpuUsage($usege){
        $usege *= 100;
        return number_format($usege, 2, '.', '');
    }
}
