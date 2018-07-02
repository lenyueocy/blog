<?php
/**
 * author by OCY, 2018/07/01 13:12.1
 */
$data = $_REQUEST;
file_put_contents('test.txt','自动pull成功------->form to '.tiem().PHP_EOL,FILE_APPEND);
echo system('cd /srv/www/blog && git pull 2>&1');
echo `cd /srv/www/blog && git pull`;
$msg = `git pull`;
echo '<br />';
//echo `git pull`;
echo '<br />';
echo '。。。。。。。。。。。。。。。。。。。。。。。。<br />';
echo 'pull成功';
