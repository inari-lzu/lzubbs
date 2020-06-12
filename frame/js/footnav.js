//返回页顶
function retop(){
    var timer = null;
    var h=parseInt(document.getElementsByTagName('body')[0].style.height);//alert(h);
    cancelAnimationFrame(timer);
    timer = requestAnimationFrame(function fn(){
        var oTop = document.body.scrollTop || document.documentElement.scrollTop;
        if(oTop > 0){
            document.body.scrollTop = document.documentElement.scrollTop = oTop - h/25;
            timer = requestAnimationFrame(fn);
        }else{
            cancelAnimationFrame(timer);
        }
    });
}

document.getElementById('retop').onclick = retop;

//获取复数的帖子信息
ajaxrequest('./',['way','pagenum'],[7,1],postlunch);

function postlunch(pack){
//    console.log(pack);
    stage=document.getElementById("stage");
    for (content of pack){
        newpost=document.createElement('div');
        newpost.className="posts";
        newpost.innerHTML=content[0][0]+content[0][1]+content[1];
        stage.appendChild(newpost);
    }
    return;
}