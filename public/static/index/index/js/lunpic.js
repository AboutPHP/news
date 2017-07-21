/**
 * Created by shenshulong on 2017/7/11.
 */

window.onload=function(){
    //封装公用函数
    function getClass(oparent,osclass){
        var size = oparent.getElementsByTagName("*");
        var arr = [];
        var i=0;
        for(var i=0;i<size.length;i++){
            if(size[i].className == osclass){
                arr.push(size[i]);
            }
        }
        return arr;
    };
    //点击左右按钮
    document.getElementById("left").onclick=function(){
        index--;
        if(index<0){
            index = arrBtn.length-1;
        }
        index--;
        if(index<0){
            index = arrBtn.length-1;
        }
        arrBtn[index].click();
    };

    document.getElementById("right").onclick = function () {
        arrBtn[index].click();
    };

    var parent = document.getElementsByClassName("Carousel")[0];
    var arrLi=getClass(parent,"Carousel-mi")[0].children;
    var btns=document.getElementsByClassName("btnop")[0];
    var index=0,oldimg,newImg;
    var $timeout;
    var key=true;
    //动态生成按钮
    var $txt="";
    for(var i=1;i<=arrLi.length;i++){
        $txt+="<li>"+i+"</li>";
    }
    btns.innerHTML=$txt;
    //形成集合形式
    var arrBtn=btns.children;
    arrBtn[0].className="one";
    for(var i=0;i<arrBtn.length;i++){
        arrBtn[i].onclick=function(){
            clearTimeout($timeout);
            key=false;
            for(var i=0;i<arrLi.length;i++){
                with(arrLi[i].style){
                    left="0px";
                    zIndex="1";
                }
            };
            index=this.innerHTML-1;
            for(var i=0;i<arrBtn.length;i++){
                if(i==index){
                    arrBtn[i].className="one";
                }else{
                    arrBtn[i].className="";
                }
            };
            key=true;
            aa();
        }
    }
    aa();
    function aa(){
        oldimg=arrLi[index];
        if(++index>=arrLi.length){
            index=0;
        };
        newimg=arrLi[index];
        oldimg.style.zIndex="2";
        oldimg.style.left="0px";
        newimg.style.zIndex="2";
        newimg.style.left="835px";
        if(key){
            $timeout=setTimeout(function(){
                bb();
                for(var i=0;i<arrBtn.length;i++){
                    if(i==index){
                        arrBtn[i].className="one";
                    }else{
                        arrBtn[i].className="";
                    };
                };
            },3000);
        };
    };

    function bb(){
        $num=parseFloat(oldimg.style.left);
        $num-=30;
        oldimg.style.left=$num+"px";
        newimg.style.left=$num+835+"px";
        if($num<=-835){
            oldimg.style.zIndex="1";
            oldimg.style.left="0";
            aa();
        }else{
            if(key){
                setTimeout(bb,13);
            };
        };
    };
};

