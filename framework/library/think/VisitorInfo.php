<?php
/**
 * author by OCY, 2018/06/13 01:02.
 */

namespace think;

use think\Config;
//use think\exception\ClassNotFoundException;
//use think\Log;
//use think\Request;
//use think\Response;
//use think\response\Redirect;

class VisitorInfo
{
    public static $ip;
    public static $lang;
    public static $os;

    public static function get()
    {
        self::$ip = self::GetIp();
        self::$lang = self::GetLang();
        self::$os = self::GetOs();


    }

    public static function GetIp()
    {
        $request = Request::instance();
        $ip_address = $request->ip();
        $ip_address = $ip_address ? $ip_address : '未知ip';
        return $ip_address;
    }

    public static function GetLang()
    {
        if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            $lang = substr($lang, 0, 5);
            if (preg_match("/zh-cn/i", $lang)) {
                $lang = "简体中文";
            } elseif (preg_match("/zh/i", $lang)) {
                $lang = "繁体中文";
            } else {
                $lang = "English";
            }
            return $lang;
        } else {
            return '未知语言';
        }
    }

    public static function GetOs()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];

        $os = false;
        if (preg_match('/iPhone/i', $agent) && strpos($agent, 'OS')) {
            $os = 'iPhone';
        }else if (preg_match('/Mac/i', $agent) && strpos($agent, 'OS')) {
            $os = 'Mac';
        }else if (preg_match('/Android/i', $agent)) {
            $os = 'Android';
        }else if (preg_match('/win/i', $agent) && strpos($agent, '95')) {
            $os = 'Windows 95';
        } else if (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90')) {
            $os = 'Windows ME';
        } else if (preg_match('/win/i', $agent) && preg_match('/98/i', $agent)) {
            $os = 'Windows 98';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent)) {
            $os = 'Windows Vista';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent)) {
            $os = 'Windows 7';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent)) {
            $os = 'Windows 8';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent)) {
            $os = 'Windows 10';#添加win10判断
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent)) {
            $os = 'Windows XP';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent)) {
            $os = 'Windows 2000';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent)) {
            $os = 'Windows NT';
        } else if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent)) {
            $os = 'Windows 32';
        } else if (preg_match('/linux/i', $agent)) {
            $os = 'Linux';
        } else if (preg_match('/unix/i', $agent)) {
            $os = 'Unix';
        } else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent)) {
            $os = 'SunOS';
        } else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent)) {
            $os = 'IBM OS/2';
        } else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent)) {
            $os = 'Macintosh';
        } else if (preg_match('/PowerPC/i', $agent)) {
            $os = 'PowerPC';
        } else if (preg_match('/AIX/i', $agent)) {
            $os = 'AIX';
        } else if (preg_match('/HPUX/i', $agent)) {
            $os = 'HPUX';
        } else if (preg_match('/NetBSD/i', $agent)) {
            $os = 'NetBSD';
        } else if (preg_match('/BSD/i', $agent)) {
            $os = 'BSD';
        } else if (preg_match('/OSF1/i', $agent)) {
            $os = 'OSF1';
        } else if (preg_match('/IRIX/i', $agent)) {
            $os = 'IRIX';
        } else if (preg_match('/FreeBSD/i', $agent)) {
            $os = 'FreeBSD';
        } else if (preg_match('/teleport/i', $agent)) {
            $os = 'teleport';
        } else if (preg_match('/flashget/i', $agent)) {
            $os = 'flashget';
        } else if (preg_match('/webzip/i', $agent)) {
            $os = 'webzip';
        } else if (preg_match('/offline/i', $agent)) {
            $os = 'offline';
        } else {
            $os = '未知操作系统';
        }
        return $os;
    }
    public static function getIpInfo(){
        $url = config('auth.IpInfoApi');
        $data = Curl::get($url,['ip',self::GetIp()]);
        if(!is_null(json_decode($data))){
            return json_decode($data,TRUE);
        }else{
            return $data;
        }
    }
}