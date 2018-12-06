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
use think\Session;
use app\admin\logic\Login as LoginLogic;

class Auth extends Controller
{
    /**
     * 验证授权
     */
    public function _initialize()
    {
        $LoginLogic = new LoginLogic();
        /*echo "<pre>";
        print_r(Session::set('qq.mem_id',1));
        print_r(Session::get('qq.mem_id'));
        exit;
        Session::get('qq.mem_id');*/
//        Session::delete('qq.mem_id');
        if( !$LoginLogic->checkaccess()){
            $this->redirect('Admin/Login/index');
        }
    }

    /**
     * 选择主题颜色
     */
    public function color()
    {
        $color = request()->param('color');
        cookie('color',$color);
        return;
    }

	/**
	 * 清空缓存
	 */
	public function clean()
	{
		echo "<span style='color: red;'>缓存清理中……</span> <br/><br/>";
		$path1 = RUNTIME_PATH . "cache/";
		echo delCache($path1);
		$path2 = RUNTIME_PATH . "temp/";
		echo delCache($path2);
		echo "<br/><span style='color: red;'>缓存清理完毕。</span>";
	}
	/**
	 * QQ退出
	 * @return array
	 */
    public function logout()
    {
        Session::delete('qq');
        Session::delete('admin');
		return ["err"=>0,"msg"=>"退出完成!","data"=>""];
    }

}