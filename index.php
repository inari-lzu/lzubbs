<?php

(function(){
    /*判断cookie登录状态*/
    $key=(isset($_COOKIE["key"])?$_COOKIE["key"]:NULL);
//    如果有的登录状态记录
    if($key){
        $key= json_decode($key);//展开数组
        $time=$key[0];//user其实是时间
        $name=$key[1];//这才是用户名

        session_start();
            $lasttime= (isset($_SESSION[$name])?$_SESSION[$name]:NULL);//拿到校验值
        session_write_close();
//        如果验证通过
            if($time==$lasttime){
                $new_key=MD5(time());

                setcookie("key", json_encode([$new_key,$name]),time()+3600,'/');

                session_start();
                    $_SESSION[$name]=$new_key;
                session_write_close();

                init($time,$name);
    } else {
//        echo $time,"本地端值--服务端值",$lasttime;
        jump(0);//校验出错
    }
//  如果没有登录记录
        } else {
//            echo "拒绝访问";
            jump(0);
    }
})();



//show,从html框架中读取元素
function show($file_name){//从html框架中读取元素
    $file_handle = fopen($file_name, "r");
    while (!feof($file_handle)){
        $line = fgets($file_handle);
        echo $line;
    }
fclose($file_handle);
}

//从xml文件中加载，返回json对象
//关于返回值：怎么进的怎么出，怎么出的怎么进
function loadfromxml($filename,$namelist){
    //初始化对象
    $doc=new DOMDocument('1.0');
    $doc->load('./posts/'.$filename.".xml");
    //按名字取出值到数组
    $valuelist=[];
    foreach ($namelist as $name){//echo $name;
        $node=$doc->getElementsByTagName($name);
        $value=$node->item(0)->nodeValue;
        array_push($valuelist,$value);
    }
    return $valuelist;//json对象
}

/*判断way指向*/
function init($time,$name){
    if(isset($_POST['way'])){//如果是新post请求，优先执行跳转
        jump($_POST['way'],$time,$name);
    }else{//没有新请求，可能是刷新页面
        if(!isset($_COOKIE["way"])){//第一次访问未携带cookie,跳转至登录页
            jump(0);
        }else{
            jump($_COOKIE['way']);
        }
    }
}

function jump($way,$time=NULL,$name=NULL){
    //保证刷新页面后还指向原页面
    $webframes=[0,1,2,3,4,5];
    if (in_array($way,$webframes)){
        setcookie("way",$way,time()+3600,'/');
    }
    //加载对应脚本并输出html
    switch ($way){
        case 0://login界面
            include './script/login.php' ;
            break;
        case 1://注册页面
            include './script/signup.php' ;
            break;
        case 2://主页
            include './script/home.php' ;
            break;
        case 3://个人设置页
            include './script/setting.php' ;
            break;
        case 4://个人新消息页
            include './script/news.php' ;
            break;
        case 5://帖子内容页
            include './script/post.php' ;
            break;
//        这之后是非页面框架模块
        case 6://上传帖子
            include './script/editor.php' ;
            break;
        case 7://主页加载复数的帖子数据
            include './script/loader.php' ;
            break;
        
    }
}