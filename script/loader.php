<?php

function pointersrequest($page){
    $numperpage=20;
    $conn= mysqli_connect('localhost','fx','123fangxiao456=1','fx');
    $sql='SELECT file_name , poster FROM post_info LIMIT  '.strval($page-1).' , '.$numperpage;
    if(!mysqli_query($conn, $sql)){
            echo mysqli_error($conn);
            return FALSE;
    }
    return mysqli_fetch_all(mysqli_query($conn, $sql));
}

//从xml文件中加载复数的内容
function multiload($points){
    $namelist=['title','content'];
    $result=[];
    foreach($points as $point){
        $point[0]= loadfromxml($point[0], $namelist);
        array_push($result,$point);
    }//echo json_encode($result);
    return $result;
}

(function(){
    $page=(isset($_POST['pagenum'])?$_POST['pagenum']:FALSE);
    if($page){
        $points=pointersrequest($page);
        $result=multiload($points);
        echo json_encode($result);
    }
})();