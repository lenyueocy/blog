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
namespace app\admin\logic;
use app\admin\model\Member as MemberModel;
use think\Session;
use think\Db;

class Login
{
    /**
     * 后台登录权限验证
     * @param $uid
     * @return bool
     */
    public function checkaccess()
    {
        $uid = Session::get('qq.mem_id');
        $adminid = Session::get('admin.id');
        $MemberModel = new MemberModel();
        $accesslist = $MemberModel->getAccess();
        $admindata = Db::table('lt_admin')->field('id')->where(['id'=>$adminid])->find();
        if($admindata && !empty($admindata)){
            return true;
        }
        if( !empty($uid) && !empty($accesslist) && in_array($uid,$accesslist) ){
            return true;
        }else{
            return false;
        }
    }
}