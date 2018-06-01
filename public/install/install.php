<?php

header("Content-type:text/html;charset=utf-8");

$rootpath = dirname(dirname(dirname(__FILE__)));
$filepath = $rootpath . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "database.php"; //要写入的配置文件。

if(!is_writable($filepath)){    //判断是否有可写的权限，linux操作系统要注意这一点，windows不必注意。
    echo "<font color=red>文件不可写</font>";
    echo fileperms("install.php");
    exit();
}
if(!empty($_POST['host']) && !empty($_POST['user']) && !empty($_POST['password']) && !empty($_POST['dbname'])){  //获取用户提交的数据。

$host=$_POST['host'];

$user=$_POST['user'];

$password=$_POST['password'];

$dbname=$_POST['dbname'];

$content = file_get_contents($filepath);

$seacharray = array("localhost","dbuser","dbpwd","dbname");


$repalcearray = array($host,$user,$password,$dbname);


$str = str_replace($seacharray,$repalcearray,$content);


file_put_contents($filepath, $str);//写入系统配置文件

$inistr = "<?php \$localhost='".$host."';\$user='".$user."';\$password='".$password."';\$dbname='".$dbname."';?>";

file_put_contents("ini.php", $inistr);//写入当前配置文件

header("Location:exec.php");

}else{
    echo "关键数据不能为空;请返回上一页检查连接参数 <a href='javascript:history.go(-1)''><font color=#ff0000>返回修改</font></a>";
    exit();
}
?>
