<?php
/**
 * author by OCY, 2018/07/01 13:12.
 */
echo system('cd /srv/www/blog && git pull');
echo `cd /srv/www/blog && git pull`;
$msg = `git pull`;
echo '<br />';
//echo `git pull`;
echo '<br />';
echo '。。。。。。。。。。。。。。。。。。。。。。。。<br />';
echo 'pull成功';
