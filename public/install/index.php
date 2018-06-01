<?php
    $filepath = "ini.php";
    $str = file_get_contents($filepath);
    if(!$str){
        ?>
        <style>
            form{width: 500px;height:300px;display: block;margin: 0 auto;margin-top: 100px;border: 1px solid #c0c0c0;border-radius: 5px;padding:50px 20px 0 20px;}
            .lit{height: 35px;margin-left: 40px;}
            .lit input{border: 1px solid #cccccc;  height: 23px;padding: 5px; background-color: #fff;outline: none;float: right;margin-right: 150px;}
            .lit button{background: #00C1B3;border: 1px solid #2a85a0;padding: 6px;width:90px;text-align: center;margin: 0 auto}
            .copy{font-size: 12px;color: #c0c0c0;text-align: right;margin-right: 20px;}
        </style>

        <form action="install.php" method="post">
            <h2 style="text-align: center">博客安装</h2>
            <div class="lit">主机名：<input type="text" name="host" placeholder="本地主机为localhost"></div>
            <div class="lit">数据库用户名：<input type="text" name="user" placeholder="默认为root"></div>
            <div class="lit">数据库密码：<input type="text" name="password" placeholder="默认为root"></div>
            <div class="lit">数据库名：<input type="text" name="dbname" placeholder="数据库名称"></div>
            <div class="lit"><button type="submit" name="install">安装</button></div>
            <div class="lit copy">BY、冷月</div>
        </form>
<?php
    }else{
        echo "已完成安装，请删除install目录";
    }
?>


