<?php

/**
 * Date: 2018/7/30
 * Time: 15:24
 */

namespace app\v1\controller;

use think\Db;
use think\Exception;
use think\Log;
use app\common\controller\Common as CommonCon;

//use app\v1\common as v1Common;
class Index extends CommonCon {

    // 用户表
    public $user = 'juejin_user';
    // 栏目表
    public $category = 'juejin_category';
    // 文章表
    public $article = 'juejin_article';
    // 用户收藏记录表
    public $collection = 'juejin_user_collection';
    // 用户关注记录表
    public $follow = 'juejin_user_follow';
    // 媒体表
    public $media = 'juejin_media';

    public function index() {
        return view();
    }

    // 测试图片上传功能
    /* public function uploads() {
      var_export(v1Common::upload());
      } */

    // 用户添加频道
    public function add_column() {
        $uid = input('get.uid');
        $columnId = input('get.columnId');
        $arr = ['status' => 1, 'msg' => '成功', 'data' => []];
        if ($uid && $columnId) {
            $res = Db::table($this->user)->where('id', $uid)->setField('column', $columnId);
            if ($res) {
                $this->arr2json($arr);
            }
            $arr['status'] = 0;
            $arr['msg'] = '添加失败';
            $this->arr2json($arr);
        }
        $arr['status'] = 0;
        $arr['msg'] = '用户id/频道id不能为空';
        $this->arr2json($arr);
    }

    // 获取频道（首次注册【想看分类】和登录之后点击加号+）
    public function get_columns() {
        $uid = input('get.uid');
        $arr = ['status' => 1, 'msg' => '成功', 'data' => []];
        $arr['data'] = $this->get_column($uid);
        $this->arr2json($arr);
    }

    // 收藏文章/取消收藏
    public function collection() {
        $uid = input('get.uid');
        $aid = input('get.aid');
        $mid = input('get.mid');
        $status = input('get.status');
        $source_style = input('get.source_style');
        $arr = ['status' => 1, 'msg' => '成功', 'data' => []];
        if (empty($uid) || empty($aid) || empty($mid) || empty($status)) {
            $arr['status']=0;
            $arr['msg'] = '用户id/文章id/媒体id 都不能为空';
            $this->arr2json($arr);
        }
        //$common = new Common();
        switch ($status) {
            case 1://收藏
                $data = [
                    'uid' => $uid,
                    'aid' => $aid,
                    'mid' => $mid,
                    'create_time' => time(),
                    'iplog' => $this->real_ip(),
                    'source_style' => $source_style,
                ];
                Db::startTrans();
                try {
                    // 记录用户收藏
                    Db::table($this->collection)->insert($data);
                    // 用户收藏数量+1
                    Db::table($this->user)->where('id', $uid)->setInc('collection');
                    // 文章收藏数量+1
                    Db::table($this->article)->where('id', $aid)->setInc('collection');
                    Db::commit();
                    //$this->arr2json($arr);
                } catch (Exception $e) {
                    Db::rollback();
                    Log::error($e->getMessage());
                    $arr['status'] = 0;
                    $arr['msg'] = '收藏文章失败';
                    //$this->arr2json($arr);
                }
                $arr['msg'] = '收藏成功';
                break;
            case 2:// 取消收藏，cancel_time取消的时间戳 //status
                $where['uid'] = $uid;
                $where['mid'] = $mid;
                $where['aid'] = $aid;
                Db::startTrans();
                try {
                    
                    Db::table($this->collection)->where($where)->update(['cancel_time' => time(), 'status' => 0]);
                    Db::table($this->user)->where('id', $uid)->setDec('collection');
                    //                Db::table($this->media)->where('id', $mid)->setDec('fans');
                    Db::commit();
                    //$this->arr2json($arr);
                } catch (Exception $e) {
                    Db::rollback();
                    Log::error($e->getMessage());
                    $arr['status'] = 0;
                    $arr['msg'] = '取消收藏文章失败';
                    //$this->arr2json($arr);
                }
                $arr['msg'] = '取消收藏成功';
                break;
            default:
                $arr['status'] = 0;
                $arr['msg'] = '收藏/取消参数有误: ' . json_encode(input('get.'));
                break;
        }
        $this->arr2json($arr);
    }

    // 媒体添/取关
    public function follow() {
        $status = input('get.status');
        $uid = input('get.uid');
        $aid = input('get.aid');
        $mid = input('get.mid');
        $source_style = input('get.source', 0);
        $arr = ['status' => 1, 'msg' => '成功', 'data' => []];
        if (empty($uid) || empty($mid) || empty($aid) || empty($status)) {
            $arr['status'] = 0;
            $arr['msg'] = '参数不能为空';
            $this->arr2json($arr);
        }
//        $common = new Common();
        // 添加关注
        switch ($status) {
            case 1://关注
                $data = [
                    'uid' => $uid,
                    'aid' => $aid,
                    'mid' => $mid,
                    'create_time' => time(),
                    'iplog' => $this->real_ip(),
                    'source_style' => $source_style,
                ];
                // 添加关注记录
                Db::table($this->follow)->insert($data);
                Db::table($this->user)->where('id', $uid)->setInc('follow');
                Db::table($this->media)->where('id', $mid)->setInc('fans');
                $arr['msg'] = '关注成功';
                break;
            case 2:// 取消关注，cancel_time取消的时间戳 //status
                $where['uid'] = $uid;
                $where['mid'] = $mid;
                $where['aid'] = $aid;
                Db::table($this->follow)->where($where)->update(['cancel_time' => time(), 'status' => 0]);
                Db::table($this->user)->where('id', $uid)->setDec('follow');
                Db::table($this->media)->where('id', $mid)->setDec('fans');
                $arr['msg'] = '取消关注成功';
                break;
            default:
                $arr['status'] = 0;
                $arr['msg'] = '关注/取消参数有误: ' . json_encode(input('get.'));
                break;
        }
        $this->arr2json($arr);
    }

}
