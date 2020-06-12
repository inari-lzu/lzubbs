//init……
(function(){
    lighting();
    document.getElementById("home").onclick=function(){jump(2);};
    document.getElementById("avator").onclick=function(){jump(3);};
    document.getElementById("news").onclick=function(){jump(4);};
    document.getElementById("logout").onclick=logout
})();

function logout(){
    if(confirm("确定要退出吗？")){
        var exp = new Date();
        var name="key";
        exp.setTime(exp.getTime() - 1);
        document.cookie = name + "='' ; expires=" + exp.toGMTString();
        alert("已登出！");
        jump(0);
    }
}

function lighting(){
    var i=document.getElementById("now").innerHTML;
    //alert(i);
    document.getElementById("pageinfo").innerHTML=i;
    document.getElementById(i).style.backgroundColor='rgba(120, 76, 77, 0.5)';
}