<?php
// +----------------------------------------------------------------------
// | 冷月博客 thinkphp5 版本
// +----------------------------------------------------------------------
// | Copyright (c) 2013~2016 http://loveteemo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: long <admin@loveteemo.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
use app\admin\logic\Login as LoginLogic;

class Login extends Controller
{
    /**
     * 登陆
     * @return mixed
     */
    public function index()
    {
        $LoginLogic = new LoginLogic();
        if($LoginLogic->checkaccess()){
            $this->redirect('Admin/Index/index');
        }
        return $this->fetch();
        /*if( !Session::has('qq.openid') ){
            $tip = 0;
            $this->assign('tip',$tip);
        }else if( !$LoginLogic->checkaccess()){
            $tip = 1;
            $this->assign('tip',$tip);
        }else{
            $this->redirect('Admin/Index/index');
        }*/
    }

    /*
     * 登录
     * */
    public function login(){
        $post = $_POST;
        if(!$_POST['username'] || !$_POST['password'] ) $this->result([],1,"缺少数据");
        $res = Db::table("lt_admin")->where(['username'=>$post['username']])->find();
        if(!$res) $this->result([],4,"账号不存在,请去注册");
        $res = Db::table("lt_admin")->where($post)->find();
        if(!$res) $this->result([],1,"密码错误");
        Session::set("admin.id",$res['id']);
        $this->result([],0);
    }
    /**
     * QQ登陆
     * @return mixed
     */
    public function qq()
    {
        $LoginLogic = new LoginLogic();
        if( !Session::has('qq.openid') ){
            $tip = 0;
            $this->assign('tip',$tip);
        }else if( !$LoginLogic->checkaccess(Session::get('qq.mem_id'))){
            $tip = 1;
            $this->assign('tip',$tip);
        }else{
            $this->redirect('Admin/Index/qq');
        }
        return $this->fetch();
    }

    /*
     * 后台注册
     * */
    public function register(){
        $post = $_POST;
        if(!$_POST['username'] || !$_POST['password'] || !$_POST['checkpassword']) $this->result([],1,"缺少数据");
        if($_POST['password'] != $_POST['checkpassword']) $this->result([],1,"两次输入的密码不一样");
        $codedata = Db::table("lt_admin_code")->where(['code'=>$post['code'],'status'=>0])->find();
        if(!$codedata) $this->result([],1,"注册码不存在或已被使用");
        $data = Db::table("lt_admin")->where(['username'=>$post['username']])->find();
        if($data || !empty($data)) $this->result([],1,"该账号已被注册");
        $savedata = [
            'username'=>$post['username'],
            'password'=>$post['password'],
            'create_time'=>time(),
        ];
        $res = Db::table("lt_admin")->insert($savedata);
        Db::table("lt_admin_code")->where(['code'=>$post['code']])->update(['status'=>1]);
        if(!$res) $this->result([],1,"注册失败");
        $this->result([],0,"注册成功");
    }
}