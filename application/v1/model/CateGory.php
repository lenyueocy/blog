<?php
namespace app\v1\model;

use think\Model;

class CateGory extends Model
{
    public function getCateGory(){
        $data = db('menu')->field('menu_id,menu_url,menu_name,menu_remark')
            ->where(['menu_parent'=>8,'menu_view'=>1])
            ->select();
        return $data;
    }
}