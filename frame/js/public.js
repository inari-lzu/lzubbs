
//这个form提交不好用，换ajax
/* function jump(to){
    //初始化表单
    var temp = document.createElement("form");
    temp.action = "";
    temp.method = "post";
    temp.style.display = "none";
    //填充参数
    var opt = document.createElement("textarea");
    opt.name = "way";
    opt.value = to;
    temp.appendChild(opt);
    //发射！
    document.body.appendChild(temp);
    temp.submit();

    return temp;
} */

function jump(to){
    ajaxrequest("./","way",to);//入口隐藏
    /*
     * 关于ajaxrequest的栈式调用与async的闭包特性
     * 1.如果将页面刷新的语句写在回调函数中时,返回的cookie不会被挂载到刷新的请求中
     * 2.线性写法,由于ajaxrequest本身是异步调用,进行页面刷新的请求时,有一定几率还没有传回新的cookie
     * 3.解决方案1: 在ajaxrequest结尾追加return……实验后发现没有用
     * 4.解决方案2: 关闭async异步----》》》成功！！！！
     */
    window.location.reload();//自动刷新后实现跳转
}

//ajaxrequest拓展函数
function ajaxstringify(name,value){    

    if(typeof(name)==="object"){//如果name是数组,遍历,进行拼接
        var passarray=[];
        for(let key of name){//alert(key);
            cargo=value[name.indexOf(key)];
            
            //如果对应的value(cargo)传入类型不是字符串，先要进行类型转换
            if(typeof(cargo)!=="string"){
                cargo=JSON.stringify(cargo);
            }

            passarray.push(key+"="+cargo);
        }
        //alert(passarray.join(","));
        return passarray.join("&");//注意是&不是逗号
    }else if(typeof(value)!=="string"){
                value=JSON.stringify(value);
    }    
    return name+"="+value;
}

//ajaxrequest函数传入实参为string或实体对象类型,
//返回reply为实体对象(json对象),这里指的是传给回调函数的参
function ajaxrequest(url,name,value,callback){
    //将传入的参数规整为可以被ajax传递的字符串
    passtr=ajaxstringify(name,value);
    
    if (window.XMLHttpRequest){
        ajaxobj=new XMLHttpRequest();
     }else{
        ajaxobj=new ActiveXObject("Microsoft.XMLHTTP");}

        ajaxobj.onreadystatechange=function(){
            if(ajaxobj.readyState===4 && ajaxobj.status===200){
                
                //响应和回调函数必须同时存在才执行回调函数
                if(ajaxobj.responseText&&callback){
                    reply=JSON.parse(ajaxobj.responseText);
                    callback(reply);//回调函数做出反应
                }
            }
        };

    ajaxobj.open("POST",url,false);
    ajaxobj.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajaxobj.send(passtr);//栈式调用,所以回调函数请求不会挂载新的cookie;
}