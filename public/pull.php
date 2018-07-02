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
//    header("Content-type: text/html; charset=utf-8");
//    file_put_contents('test_input.txt',var_export($payload,TRUE).PHP_EOL,FILE_APPEND);
    // 本地仓库路径
    $local = '/srv/www/blog';
    $token = 'lenyue';

    $httpToken = isset($_SERVER['HTTP_X_GITLAB_TOKEN']) ? $_SERVER['HTTP_X_GITLAB_TOKEN'] : '';
    if ($token && $httpToken != $token) {
        header('HTTP/1.1 403 Permission Denied');
        die('Permission denied.');
    }

    if (!is_dir($local)) {
        header('HTTP/1.1 500 Internal Server Error');
        die('Local directory is emtype');
    }

    $payload = file_get_contents('php://input');
    if (!$payload) {
        header('HTTP/1.1 400 Bad Request');
        die('HTTP HEADER or POST is emtype.');
    }
    file_put_contents('test.txt','自动pull成功 ------->form to '.date('Y-m-d H:i:s',time()).PHP_EOL.PHP_EOL,FILE_APPEND);
    `cd /srv/www/blog && git pull origin master 2>&1`;
}
die("done " . date('Y-m-d H:i:s', time()));