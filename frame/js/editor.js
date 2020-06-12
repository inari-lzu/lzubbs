//全局变量定义
var button=document.getElementById('start_post');
var container=document.getElementById("container");
//入口
(function(){
    butinit(create);
})();

function butinit(fun){
    button.innerHTML="post!"
    button.onclick=fun;
}

function create(){
   // var button=document.getElementById('start_post');
   // var container=document.getElementById("container");
    var title=document.createElement('textarea');
    var content=document.createElement('textarea');
    var editor=document.createElement('div');
    var exist=document.createElement('div');
    exist.id="exist";
    title.id="title";
    content.id="content";
    editor.id="editor";
    exist.innerHTML="cancel";
    title.placeholder="请输入标题";
    content.placehoder="请输入正文";
    editor.appendChild(exist);
    editor.appendChild(title);
    editor.appendChild(content);
    container.appendChild(editor);
    button.innerHTML="OK";
    button.onclick=function(){store(title,content);};
    exist.onclick=function(){butinit(create);remeditor();};
};

function store(title,content){
    pack=[title.value,content.value];//闭包
//    remeditor();
    ajaxrequest('./',['way','post'],[6,pack],reaction);
};

function remeditor(){
    var editor=document.getElementById("editor");
    container.removeChild(editor);
    butinit(create);
}

function reaction(reply){//reply返回的是json对象,一个数组
    if(reply!==404){
        remeditor();
        postlunch([reply]);        
    }else{
        alert("上传失败");
    }
}