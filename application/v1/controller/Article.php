<?php

/**
 * Date: 2018/7/30
 * Time: 15:24
 */

namespace app\v1\controller;

use think\Db;
use think\Log;
use think\Cache;

class Article{
    public $limit = '6';
    // 用户表
    public $article = 'lt_article';

    // 频道列表文章
    public function lists() {
        $page = request()->param('page')?:1;
        $where = [
            'art_view' => ['in','1,2']
        ];
        $count = Db::table($this->article)
            ->field('art_id')
            ->count();

        $pageCount = ceil($count/$this->limit);
        if ($page > $pageCount){
            arr2json(['code'=>'1','msg'=>'没有更多文章了','data'=>[]]);
        }
        $articleData = Db::table($this->article)->field('art_id,art_title,art_img,art_author,art_addtime,art_hit,art_collection,art_view')
            ->where($where)
            ->page($page,$this->limit)
            ->order('art_view desc,art_addtime desc')
            ->select();
        foreach ($articleData as &$val){
            $val['art_addtime'] = date('y-m-d H:i',$val['art_addtime']);
        }
        $pageData = [
            'page'=>$page,
            'pageCount'=>$pageCount
        ];
        arr2json(['code'=>0,'msg'=>'成功','data'=>[
            'list'=>$articleData,
            'page'=>$pageData
        ]]);
    }
    public function detail(){
        $art_id = request()->param('art_id');
        if (!$art_id){
            arr2json(['code'=>'1','msg'=>'没有获取到文章数据','data'=>[]]);
        }
        $where = [
            'art_id'=>$art_id
        ];
        $articleData = Db::table($this->article)->field('art_id,art_title,art_img,art_author,art_addtime,art_hit,art_collection,art_view,art_content')
            ->where($where)
            ->find();
        
        $articleData['art_addtime'] = date('Y-m-d H:i',$val['art_addtime']);


        arr2json(['code'=>0,'msg'=>'成功','data'=>[
            'list'=>$articleData,
        ]]);
    }

}
