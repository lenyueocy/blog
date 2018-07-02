<?php
/**
 * author by OCY, 2018/07/01 13:12.1
 */


$payload = json_decode(file_get_contents('php://input'), true);
if(!$payload){
    `cd /srv/www/blog && git pull origin master 2>&1`;
    echo '<br />';
    echo '<br />';
    echo '~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br />';
    echo '触发pull成功';
}else{
    header("Content-type: text/html; charset=utf-8");
    file_put_contents('test_input.txt',var_export($payload,TRUE).PHP_EOL,FILE_APPEND);
    file_put_contents('test.txt','自动pull成功------->form to '.date('Y-m-d H:i',time()).PHP_EOL,FILE_APPEND);
    `cd /srv/www/blog && git pull origin master 2>&1`;
}
exit;