<?php
/**
 * Created by PhpStorm.
 * User: clg
 * Date: 2018/7/31
 * Time: 9:41
 */

namespace app\v1\controller;

use app\common\controller\SmsApi;
use app\common\controller\Common as CommonCon;
use think\Db;
use think\Config;
class Sms extends CommonCon
{
    private $table = 'juejin_sms';

    // 短信验证码
    public function verification_code() {
        //获取手机号
        $mobile = input('get.mobile','');
        $arr = ['status'=>1,'msg'=>'success','data'=>[]];
        
        //判断手机号是否存在
        if(empty($mobile)) {//empty($mobile)//==='' 是否需要处理变量为0的时候的判断
            $arr['status']=0;
            $arr['msg'] = '手机号不能为空';
            return json($arr);
        }
        //判断用户是登录还是注册
        $res=$this->user_check($mobile);//返回值为1属于登录 返回值为0为表示注册
        //发送短信
        $sms_api=new SmsApi();
        $res=$sms_api->sms_code($mobile,$user_status=$res);
        //echo 1111111111111;
        return json($res);


    }

}