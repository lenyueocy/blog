<?php
/**
 * author by OCY, 2018/07/01 13:12.111
 */
$data = $_REQUEST;
$payload = file_get_contents('php://input');
file_put_contents('test_input.txt',$payload.PHP_EOL,FILE_APPEND);
file_put_contents('test.txt','自动pull成功------->form to '.time().PHP_EOL,FILE_APPEND);
echo system('cd /srv/www/blog && git pull 2>&1');
echo `cd /srv/www/blog && git pull`;
$msg = `git pull`;
echo '<br />';
//echo `git pull`;
echo '<br />';
echo '。。。。。。。。。。。。。。。。。。。。。。。。<br />';
echo 'pull成功';
