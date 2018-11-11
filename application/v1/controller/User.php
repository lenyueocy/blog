<?php

/**
 * Date: 2018/7/30
 * Time: 15:24
 */

namespace app\v1\controller;

use fast\Http;
use upload\Upload;
use think\Db;
use think\Log;
use think\Cache;
use app\common\controller\Common as CommonCon;

class User extends CommonCon {

    // 用户表
    public $user = 'juejin_user';
    // 用户分享记录表
    public $sharelog = 'juejin_user_share_log';
    // 用户附属表
    public $attached = 'juejin_user_attached';
    // 栏目表
    public $category = 'juejin_category';
    // 关注表
    public $follow = 'juejin_user_follow';
    // 媒体表
    public $media = 'juejin_media';
    // 收藏表
    public $collection = 'juejin_user_collection';
    // 历史表
    public $history = 'juejin_user_history';
    // 文章表
    public $article = 'juejin_article';
    // 意见反馈表
    public $feedback = 'juejin_feedback';
    // 系统表
    public $conf = 'juejin_config';
    // 系统表
    public $sms = 'juejin_sms';

    public function index() {
        /* header("Content-type: text/html; charset=utf-8");
          $sign = 'str';
          $str = '1';
          $enStr = Mcrypt::do_mencrypt($str);
          if ($enStr == $sign){

          }
          var_dump($enStr); */
        #echo $str.'---'.$enStr;
        #var_export(($deStr == $str));
    }

    // 用户登录
    public function login() {
        $get_mobile = trim(input('get.mobile', ''));
        $get_code = trim(input('get.code', ''));
        $arr = ['status' => 1, 'msg' => '成功', 'data' => []];
        //验证手机号和验证码的长度
        if (strlen($get_mobile) !== 11 || strlen(($get_code)) !== 4) {
            $arr['status'] = 0;
            $arr['msg'] = '手机号或者验证码长度不符合规则';
            $this->arr2json($arr);
        }

        //验证手机号和验证码的转换的类型不能等于0[验证码有0开头的，所以不判断验证码]
        if (((int) $get_mobile) == 0) {
            $arr['status'] = 0;
            $arr['msg'] = '手机号或者验证码不能为空';
            $this->arr2json($arr);
        }

        //判断手机号是否符合规则
        $is_mobile = $this->get_phone_type($get_mobile);
        if ($is_mobile > 4) {
            $arr['status'] = 0;
            $arr['msg'] = '手机号不符合规则';
            $this->arr2json($arr);
        }
//         // 获取验证码
//        $sms_data = Db::table($this->sms)
//                ->field('createtime,code')
//                ->where('mobile', $get_mobile)
//                ->order('id desc')
//                ->find();
//        //判断验证码是否一致
//        if ($sms_data['code'] !== $get_code) {
//            $arr['status'] = 0;
//            $arr['msg'] = '验证码错误';
//            $this->arr2json($arr);
//        }
//        //判断验证码是否过期
//        if(($sms_data['createtime']+600)<time()) {
//            $arr['status'] = 0;
//            $arr['msg'] = '验证码已过期';
//            $this->arr2json($arr);
//        } 
        //处理注册和登录
        if ($get_mobile && $get_code) {

            //查询用户是否存在
            $status = $this->user_check($get_mobile);

            switch ($status) {
                case 1://登录
                    $res = Db::table($this->user)->where('mobile', $get_mobile)->update(['last_time' => time()]);
                    if ($res) {
                        // 用户信息
                        $userInfo = $this->getUserInfo($get_mobile);
                        $userInfo['column'] = $this->get_column($res);
                        $arr['msg'] = '登录成功';
                        $arr['data'] = $userInfo;
                        $this->arr2json($arr);
                    }
                    Log::error('用户登陆失败:mobile-' . $get_mobile . ',code-' . $get_code);
                    $arr['status'] = 0;
                    $arr['msg'] = '登陆失败';
                    $this->arr2json($arr);
                    break;
                case 2://注册
                    $res = $this->register($get_mobile);

                    if ($res) {
                        $userInfo = $this->getUserInfo($get_mobile, $res);

                        // 返回分类（频道）
                        $userInfo['column'] = $this->get_column($res);
                        $arr['msg'] = '注册成功';
                        $arr['data'] = $userInfo;
                    } else {
                        $arr['status'] = 0;
                        $arr['msg'] = '注册失败';
                        Log::error('注册失败:mobile-' . $get_mobile . ',code-' . $get_code);
                        $this->arr2json($arr);
                    }
                    break;
                default:
                    $arr['status'] = 0;
                    $arr['msg'] = '非法操作';
                    break;
            }
            $this->arr2json($arr);
        }
    }

    public function register($mobile) {
        $source_style = input('get.source_style', 0);
        $uid = Db::table($this->user)->insertGetId([
            'mobile' => $mobile,
            'user_name' => $mobile,
            'reg_ip' => $this->real_ip(), //获取用户的真实ip
            'create_time' => time(),
            'source_style' => $source_style,
        ]);
        //nick_name 暂时给用户创建一个系统默认的昵称
        $str = '掘金宝' . sprintf("%06d", $uid);
        if ($res = Db::table($this->attached)->insert(['uid' => $uid, 'nick_name' => $str])) {
            return $uid;
        }
    }

    // 获取频道（前端注册成功弹窗显示，选择频道）
    public function getCategory() {
        //$get_mobile = input('mobile');
        /* $common=new Common();
          $status = $common->user_check($get_mobile); */
        $column = Db::table($this->category)->field('id,name')->where('type', 'column')->select();
        /* if(!$status) {
          return $column;
          }
          return json($column); */
        return $column;
    }

    // 设置首次注册用户选择的频道
//    public function set_column() {
//        $uid = input('get.uid');
//        $columnId = input('get.columnId');
//        $arr = ['status' => 1, 'msg' => '成功', 'data' => []];
//        if ($uid && $columnId) {
//            //$data = Db::table($this->user)->where('id', $uid)->value('column');
//            $res = Db::table($this->user)->where('id', $uid)->update(['column' => ($data . ',' . $columnId)]);
//            if ($res) {
//                $this->arr2json($arr);
//            }
//            $arr['status'] = 0;
//            $arr['msg'] = '选择失败';
//            $this->arr2json($arr);
//        }
//        $arr['status'] = 0;
//        $arr['msg'] = '用户id/频道id不能为空';
//        $this->arr2json($arr);
//    }
    // 个人信息页
    public function personal() {
        $mobile = input('get.mobile', '');
        $arr = ['status' => 1, 'msg' => '成功', 'data' => []];
        if ($mobile) {
            $userInfo = $this->getUserInfo($mobile);
            $arr['data'] = $userInfo;
            $this->arr2json($arr);
        }
        $arr['status'] = 0;
        $arr['msg'] = '手机号不能为空';
        $this->arr2json($arr);
    }

    // 修改个人信息
    public function edit() {
        $arr = ['status' => 1, 'msg' => '成功', 'data' => []];
        $uid = input('get.uid');
        if (empty($uid)) {
            $arr['msg'] = '用户id不能为空';
            $this->arr2json($arr);
        }
        $mobile = input('get.mobile');
        $avatar = input('get.avatar');
        $nick_name = input('get.nick_name');
        $gender = input('get.gender');
        $birthday = input('get.birthday');
        $wx_nick_name = input('get.wx_nick_name');
        $data = [];
        if ($mobile) {
            $data['mobile'] = $mobile;
            $res = Db::table($this->user)->where('id', $uid)->update($data);
            if ($res !== false) {
                $this->arr2json($arr);
            }
            $arr['msg'] = '修改手机号失败';
            $this->arr2json($arr);
        }
        if ($avatar) {
            $data['avatar'] = $avatar;
        }
        if ($nick_name) {
            $data['nick_name'] = $nick_name;
        }
        if ($gender) {
            $data['gender'] = $gender;
        }
        if ($birthday) {
            $data['birthday'] = strtotime($birthday);
        }
        if ($wx_nick_name) {
            $data['wx_nick_name'] = $wx_nick_name;
        }
        if (empty($data)) {
            $arr['status'] = 0;
            $arr['msg'] = '提交修改参数不能为空';
            $this->arr2json($arr);
        }
        $res = Db::table($this->attached)->where('uid', $uid)->update($data);
        if ($res !== false) {
            $this->arr2json($arr);
        }
        $arr['status'] = 0;
        $arr['msg'] = '修改失败';
        $this->arr2json($arr);
    }

    // 关注列表
    public function follow_lists() {
        $uid = input('get.uid');
        $page = input('get.page', 1);
        $arr = ['status' => 1, 'msg' => '成功', 'data' => []];
        if (empty($uid)) {
            $arr['status'] = 0;
            $arr['msg'] = '用户id不能为空';
            $this->arr2json($arr);
        }

        //$where['f.cancel_time'] = array('gt',0);
        $where['f.status'] = 1;
        $where['f.uid'] = $uid;
        $total = Db::table($this->follow)->alias('f')
                ->join($this->media . ' m', 'f.mid=m.id', 'left')
                //->join($this->media . ' m', 'f.mid=m.id and f.status=1 and f.uid=' . $uid, 'left')
                ->where($where)
                ->count();

        $pageSize = ($page > 1) ? 20 : 10;
        $data = Db::table($this->follow)->alias('f')
                ->field('m.name,m.logo,m.id as mid,f.aid')//,from_unixtime(f.create_time,"%m/%d") as create_time
                ->join($this->media . ' m', 'f.mid=m.id', 'left')
                ->where($where)
                ->order('f.create_time desc')
                ->page($page, $pageSize)
                ->select();

        if ($data) {
            $page = ['page' => $page, 'pageSize' => $pageSize, 'total' => ceil($total / $pageSize)];
            $arr['data'] = [
                'page' => $page,
                'list' => $data
            ];
        }
        $this->arr2json($arr);
    }

    // 用户收藏/历史列表
    public function ch_lists() {
        $uid = input('get.uid');
        $page = input('get.page', 1);
        $type = input('get.type'); //类型 1为收藏 2为历史
        $arr = ['status' => 1, 'msg' => '成功', 'data' => []];
        if (empty($uid) || empty($type)) {
            $arr['status'] = 0;
            $arr['msg'] = '不能为空';
            $this->arr2json($arr);
        }
        $pageSize = ($page > 1) ? 20 : 10;
        if ($type == 1) {
            $total = Db::table($this->collection)->alias('c')
                    ->join($this->article . ' a', 'c.aid=a.id and c.uid=' . $uid, 'left')
                    ->join($this->media . ' m', 'a.mid=m.id')
                    ->count();
            $data = Db::table($this->collection)->alias('c')
                    ->field('c.uid,c.aid,a.title,a.status,a.img_url,a.read_num,m.name as mname')
                    ->join($this->article . ' a', 'c.aid=a.id and c.uid=' . $uid, 'left')
                    ->join($this->media . ' m', 'a.mid=m.id')
                    ->order('c.create_time desc')
                    ->page($page, $pageSize)
                    ->select();
            //list($arr['data']['page'], $arr['data']['total'], $arr['data']['pageSize'], $arr['data']['list']) = [$page, $total, $pageSize, $data];
            $page = ['page' => $page, 'pageSize' => $pageSize, 'total' => ceil($total / $pageSize)];
            $arr['data'] = [
                'page' => $page,
                'list' => $data
            ];
            $this->arr2json($arr);
        }
        if ($type == 2) {
            $arr = $this->history($uid, $page, $pageSize);
            $this->arr2json($arr);
        }
        Log::error('查询用户收藏/历史列表时参数错误.' . json_encode(input('get.')));
        $arr['status'] = 0;
        $arr['msg'] = '参数错误';
        $this->arr2json($arr);
    }

    // 用户历史
    public function history($uid, $page, $pageSize) {
        $total = Db::table($this->history)->alias('h')
                ->join($this->article . ' a', 'h.aid=a.id and h.uid=' . $uid, 'left')
                ->join($this->media . ' m', 'a.mid=m.id')
                ->count();
        $data = Db::table($this->history)->alias('h')
                ->field('h.uid,h.aid,a.title,a.status,a.img_url,read_num,m.name as mname')
                ->join($this->article . ' a', 'h.aid=a.id and h.uid=' . $uid, 'left')
                ->join($this->media . ' m', 'a.mid=m.id')
                ->order('h.create_time desc')
                ->page($page, $pageSize)
                ->select();
        $arr = ['status' => 1, 'msg' => '成功', 'data' => []];
        //list($arr['data']['page'], $arr['data']['total'], $arr['data']['pageSize'], $arr['data']['list']) = [$page, $total, $pageSize, $data];
        $page = ['page' => $page, 'pageSize' => $pageSize, 'total' => ceil($total / $pageSize)];
        $arr['data'] = [
            'page' => $page,
            'list' => $data
        ];
        return $arr;
    }

    // 意见反馈
    public function feedback() {
        $uid = input('post.uid', 0);
        $content = input('post.content');
        $source_style = input('post.source_style', 0);
        $contact = input('post.contact', 0);
        $imgs = Request()->post('imgs/a',0);

        $arr = ['status' => 1, 'msg' => '成功', 'data' => []];

        if (empty($content)) {
            $arr['status'] = 0;
            $arr['msg'] = '反馈内容不能为空';
            $this->arr2json($arr);
        }
        $data = [
            'uid' => $uid,
            'content' => $content,
            'create_time' => time(),
            'contact' => $contact,
            'iplog' => $this->real_ip(), // long2ip 获取真实IP
            'source_style' => $source_style, // 来源。比如：小程序、网站、安卓
        ];
        if(isset($imgs) && !empty($imgs)){
            $data['img_url'] = implode(',',$imgs);
        }
        $res = Db::table($this->feedback)->insert($data);

        if ($res === false) {
            $arr['status'] = 0;
            $arr['msg'] = '提交反馈失败';
        }
        $this->arr2json($arr);
    }

    // 关于我们
    public function about() {
        $str = 'site_name,site_version,site_slogan,site_qqqun,site_wechat,site_logo';
        //基础信息
        $about = Db::table($this->conf)
                ->field('name,title,value')
                ->where('name', 'in', $str)
                ->select();
        //将某个列的值作为键值
        $newArray2 = array_column($about, NULL, 'name');

        //版本信息
        $data = Db::name('version_notice')
                ->field('new_version_num,content')
                ->where('status', 1)->order('id desc')
                ->select();
//        echo Db::name('version_notice')->getLastSql();
        $arr['status'] = 1;
        $arr['msg'] = '提交成功';
        $arr['data'] = [
            'about' => $newArray2,
            'list' => $data
        ];
        $this->arr2json($arr);
    }

    // 获取用户信息
    protected function getUserInfo($mobile) {//,$uid
        $userInfo = Db::table($this->user)->alias('u')
                ->field('u.id as uid,u.user_name,u.mobile,u.collection,u.follow,u.column,u.history,ua.nick_name,ua.wx_nick_name,ua.avatar,ua.gender,ua.birthday')
                ->join($this->attached . ' ua', 'u.id=ua.uid', 'left')
                ->where('u.mobile', $mobile)->where('u.status', 1)
                //->where('u.id', $uid)
                ->find();
        return $userInfo;
    }

    /**
     * 关注、收藏、历史精彩推荐
     * 当关注和收藏没有相关数据需要使用此函数加载推荐文章
     */
    public function fch_lists() {
        $page=input('get.page');
        $uid = input('get.uid');
        $arr = ['status' => 1, 'msg' => '成功', 'data' => []];
        if (empty($uid)) {
            $arr['status'] = 0;
            $arr['msg'] = '会员不存在';
            $this->arr2json($arr);
        }
        //查询频道数据的条数
        $wheres['status'] = 1;
        $status = Db::table($this->article . ' a')->where($wheres)->count();
        if (empty($status)) {
            $arr['status'] = 1;
            $arr['msg'] = '暂无文章';
            $this->arr2json($arr);
        }
        //大于第六页停止
        if($page>6){
            $arr['status'] = 1;
            $arr['msg'] = '推荐数据达到最大';
            $this->arr2json($arr);
        }
        //推荐条数
        $num = 4;
        $article_list_fch = Cache::get('article_list_fch');   //自己定义一个cache_key
//        $article_list_fch = '';  
        $where['a.status'] = 1;
        if (empty($article_list_fch)) {
            $article_list_fch = Db::table($this->article)->alias('a')
                    ->field('a.id,a.title,a.type,case a.img_url when isnull(a.img_url) then a.img_url else "" end img_url,a.type,a.is_top,a.read_num,a.comments,a.mid,m.name as mname')
                    ->join($this->media . ' m', 'm.id=a.mid', 'left')
                    ->where($where)
                    ->cache('article_list_fch',1)
                    ->limit(50)
//                    ->page($page,$num)
                    ->select();
           // echo Db::table($this->article)->getLastSql();
        }
        $random_keys = array_rand($article_list_fch, $num);
        foreach ($random_keys as $key => $value) {
            $data[] = $article_list_fch[$value];
        }

        $arr['data']['list'] = $data;
        $this->arr2json($arr);
    }
    public function uploadImg(){
        $base64Img = input('post.img');
        $result = Upload::base64Upload($base64Img);
        exit($this->arr2json($result));
    }
    public function sharelog(){
        $uid = input('get.uid',0);
        $share_type = input('get.share_type',0);
        $cookieid = input('get.cookieid',0);
        $source_style = input('get.source_style',0);
        $res = ['status'=>1,'msg'=>'成功','data'=>[]];
        if(!$share_type){
            $res['status'] = 0;
            $res['msg'] = '分享去向不能为空';
        }
        $data = [
            'uid' => $uid,
            'share_type' => $share_type,
            'cookieid' => $cookieid,
            'sessionid' => session_id(),
            'create_time' => time(),
            'iplog' => $this->real_ip(), // long2ip 获取真实IP
            'source_style' => $source_style, // 来源。比如：小程序、网站、安卓
        ];
        $result = Db::table($this->sharelog)->insert($data);
        if(!$result){
            $res['status'] = 0;
            $res['msg'] = '添加数据失败';
        }
        $this->arr2json($res);
    }

}
