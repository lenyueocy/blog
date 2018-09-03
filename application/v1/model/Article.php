<?php
namespace app\v1\model;

use think\Model;

class Article extends Model
{
    public function getCateGoryArticle($cate_id){
        $data = db('article')
            ->field('art_id,art_title,art_img,art_remark,art_keyword,art_pid,art_addtime,art_content,art_collection,art_hit,art_author')
            ->where(['art_pid'=>$cate_id,'art_view'=>['in','1,2']])
            ->select();
        return $data;
    }
}