<?php
//自定义测试参数，分别是(待解包的json)string,命名列表,生成的文件名称
$pack=isset($_POST['post'])?$_POST['post']:NULL;
$namelist=['title','content'];
$filename= md5(time());//利用时间作为索引

(function($pack,$namelist,$filename,$name){
      if($pack&&storetodb($filename,$name)){//避免空包错误和数据库连接错误
      //storetodb（…）↑先在数据库存下索引↑
        
        //然后把内容存到xml文件里
        //实例化jsonstring后再放进去,怎么进的怎么出，怎么出的怎么进
        $pack= json_decode($pack);
        storetoxml($pack,$namelist,$filename);
        //用jsonstring传回去
       //$reply=loadfromxml($filename,$namelist);//返回jsonstring
        $array=loadfromxml($filename, $namelist);
        echo json_encode([$array,[$name]]);
    }else{
        echo 404;
    }
})($pack,$namelist,$filename,$name);

//存储指针到数据库
function storetodb($filename,$name){//文件名，上传者用户名
    //准备SQL语句 servername,用户名，密码，数据库名
    $conn = mysqli_connect('localhost', 'fx', '123fangxiao456=1','fx'); 
    //准备插入的sql语句，注意字符串外面还要加引号
    $sql = "INSERT INTO post_info (date,file_name,poster) VALUES (".time().",'".$filename."','".$name."')";
    //echo $sql;
    if(!mysqli_query($conn, $sql)){
        echo mysqli_error($conn);
        return FALSE;
    }
    return TRUE;
}

//$pack是对象，无需解包
function storetoxml($pack,$namelist,$filename){
    //创建新的XML文档
    $doc = new DomDocument('1.0');
   // 创建根节点
    $root = $doc->createElement('root');
    $doc->appendChild($root);
   //写入dom对象
    for ($i=0; $i<count($pack); $i++){
        $value=$pack[$i];
        $name=$namelist[$i];
        $newnode = $doc -> createElement($name);//创建子元素
        $child = $doc -> createTextNode($value);//创建值
        $newnode -> appendChild($child);  //把值插入节点
        $root ->appendChild($newnode);//插入根节点
    };
    //写入文件
    $xml_string = $doc -> saveXML();
    file_put_contents('./posts/'.$filename.".xml", $xml_string);
    //echo $xml_string;
}