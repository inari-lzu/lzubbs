<?php

$front='<html>
    <head>
        <title>登录</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body style="margin: 0px;background-image:url(\'./frame/source/background.png\');">
        <style id="now" style="display: none">login</style>
        ';
$back= '</body>
</html>';

(function($front,$back){
    $state=(isset($_POST['state'])?$_POST['state']:FALSE);
//空说明还没登录,都有才判断是否正确
    if($state){
        check($state);
    }else{
        echo $front;
        show("./frame/html/headnav.html");
        show("./frame/html/login.html");
        echo $back;
    }
})($front,$back);//入口

function check($state){
    
                    $state= json_decode($state);
                    $userid=(isset($state->userid)?$state->userid:NULL);
                    $password=(isset($state->password)?$state->password:NULL);
                    
                    if($userid&&$password){//如果有一个值是空的，就不连接数据库

                                //开始连接数据库
                                //准备SQL语句 servername,用户名，密码，数据库名
                                $conn = mysqli_connect('localhost', 'fx', '123fangxiao456=1','fx');
                                $select = "SELECT userid,password FROM logger_info WHERE userid = '$userid' AND password = '$password'"; //执行SQL语句
                                $ret = mysqli_query($conn, $select);
                                $row = mysqli_fetch_array($ret); //判断用户名或密码是否正确
                                mysqli_close($conn);
                                //关闭数据库
                                
                                        if ($userid == $row['userid'] && $password == $row['password']){    
                                                $reply=sucset($userid);//设置密钥cookie和way_cookie
                                        }else{
                                                $reply=[0];//对不上
                                        }

                     }else{
                                $reply=[0] ;//两个之中至少缺一个
                     }
                     $reply= json_encode($reply);
                     echo $reply;
}

function sucset($userid){
    //密钥cookie
    $hashtime=MD5(time());
    setcookie("key", json_encode([$hashtime,$userid]),time()+3600,'/');//注意，这是个列表，time是首项,用的是
    //设置session
    session_start();
      $_SESSION[$userid]=$hashtime;//这个只有时间，是数字
    session_write_close();//不关的话会有服务器读写占用
    //way_cookie控制向主页跳转
    //setcookie("way",2,time()+3600,'/');//2代表homepage 
    //返回给reply变量回传给前端ajax
    return [1,$hashtime];
}