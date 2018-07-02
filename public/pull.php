<?php
/**
 * author by OCY, 2018/07/01 13:12.1
 */


$payload = json_decode(file_get_contents('php://input'), true);

if(!$payload){
    echo `cd /srv/www/blog && git pull origin master 2>&1`;
    echo '<br />';
    echo '<br />';
    echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br />';
    echo '触发pull成功';
}else{
    header("Content-type: text/html; charset=utf-8");
    // 本地仓库路径
    $local = '/srv/www/blog';
    $token = '';

    $httpToken = isset($_SERVER['HTTP_X_GITLAB_TOKEN']) ? $_SERVER['HTTP_X_GITLAB_TOKEN'] : '';
    $status = 'success';
    $msg = '触发pull成功';
    if ($token && $httpToken != $token) {
        header('HTTP/1.1 403 Permission Denied');
        $status = 'error';
        $msg = '哈哈哈，密钥错误.';
    }

    if (!is_dir($local)) {
        header('HTTP/1.1 500 Internal Server Error');
        $status = 'error';
        $msg = '哈哈哈，仓库目录错误或不存在';
    }

    $payload = file_get_contents('php://input');
    if (!$payload) {
        header('HTTP/1.1 400 Bad Request');
        $status = 'error';
        $msg = '哈哈哈，请求的数据为空.';
    }
    file_put_contents('test.txt','status：'.$status.' | Msg：'.$msg.' | 自动pull ------->time to '.date('Y-m-d H:i:s',time()).PHP_EOL.PHP_EOL,FILE_APPEND);
    if ($status == 'error')
        die($msg.' 于'.date('Y-m-d H:i:s', time()));
    `cd /srv/www/blog && git pull origin master 2>&1`;
}
die("耶耶耶 触发并执行成功  于" . date('Y-m-d H:i:s', time()));