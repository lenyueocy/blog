<?php
namespace app\index\controller;
use app\index\controller\Base;
use app\index\model\Version as VersionModel;
class Qq extends Base
{
    public function index(){
        $qq = isset($_GET['qq']) ? $_GET['qq'] : '492708759';
        $this->assign('qq', $qq);
        $this->assign('title',"跳转QQ");
        return $this->fetch('qq/index');
    }
}