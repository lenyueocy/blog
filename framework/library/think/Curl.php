<?php
/**
 * author by OCY, 2018/06/14 01:29.
 */
namespace think;
class Curl{
    public static $timeout = 10;
    public static function post($url,$data){
        $con = curl_init((string)$url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_POSTFIELDS, $data);
        curl_setopt($con, CURLOPT_POST,true);
        curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($con, CURLOPT_TIMEOUT,(int)self::$timeout);
        $result = curl_exec($con);
        return $result;
    }
    public static function get($url,$data){
        $url = $url.'?'.implode('=',$data);
        $con = curl_init((string)$url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($con, CURLOPT_TIMEOUT, (int)self::$timeout);
        $result = curl_exec($con);
        return $result;
    }
}