(function(){
    document.getElementById("button_list").remove();
    document.getElementById("pageinfo").remove();
    document.getElementById("login").onclick=login;
})();

function login(){
    document.getElementById("login").onclick=null;
    var userid=document.getElementById("userid").value;
    var password=document.getElementById("password").value;
    
    if(userid===""||password===""){alert("想干嘛？");
        document.getElementById("login").onclick=login;
        
    }else{
        var state={'userid':userid,'password':password};
        ajaxrequest("./script/login.php","state",state,reaction);
    }
}

function reaction(array){//对回复进行反应
    var i=array[0];
    if(i){
        user=array[1];
        alert("欢迎");
        jump(2);//跳转至主页
        //alert(user);//对应session代号
    }else{
        alert("wrong !");
        document.getElementById("login").onclick=login;
    }
}