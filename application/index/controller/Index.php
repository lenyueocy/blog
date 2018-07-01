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
namespace app\index\controller;
use app\index\controller\Base;
use app\index\model\Article as ArticleModel;
use app\index\model\Tip as TipModel;
use app\index\model\System as SystemModel;

class Index extends Base
{
    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
		$ArticleModel = new ArticleModel();
        $SystemModel  = new SystemModel();
        $indexdata['articles'] = $ArticleModel->getCateList(true,0,5);
		$TipModel = new TipModel();
		$tips = $TipModel::where('tip_view',1)->select();
        // 系统参数
        if( empty( $systeminfo = cache('systeminfo') ) ){
            $systeminfo = $SystemModel::get();
            cache('systeminfo',$systeminfo);
        }
		$this->assign('index_title',$systeminfo['sys_keyword']);
		$this->assign('tips',$tips);
        $this->assign('indexdata', $indexdata);
        $this->assign('title',"首页");
        return $this->fetch('index');
    }
}
