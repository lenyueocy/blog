<?php
namespace app\v1\model;

use think\Db;
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
    public function getData($params = '')
    {
        $where = [
            'art_view' => ['in','1,2']
        ];
        if(isset($params['type']) && !empty($params['type']) && $params['type']=="rand"){
            $sql = "select * from lt_article where art_id NOT IN ({$params['notin']}) and art_view in (1,2) ORDER BY RAND() limit 0,6";
            $data = Db::query($sql);
            return $data;
        }
        $articleData = Db::table('lt_article')->field('art_id,art_title,art_content,art_img,art_author,art_addtime,art_hit,art_collection,art_view')
            ->where($where)
            ->page($params['page'],$params['limit'])
            ->order('art_view desc,art_addtime desc')
            ->select();
        return $articleData;
    }
}