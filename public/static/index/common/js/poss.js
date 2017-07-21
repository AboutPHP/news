/**
 * Created by shenshulong on 2017/7/11.
 */
//aside
var newsLie = document.getElementsByClassName("news-lie")[0].children;
var $time1,size,key = false;
function sibling(ele){
    var parent = ele.parentNode.children;
    var arr = [];
    var i=0;
    for(var i=0;i<parent.length;i++){
        if(ele != parent[i]){
            arr.push(parent[i]);
        };
    };
    return arr;
};
for(var i=1;i<newsLie.length;i++){
    newsLie[i].lastElementChild.style.height = 0;
};
for(var i=0;i<newsLie.length;i++){
    if(newsLie[i].firstElementChild.lastElementChild.innerHTML == "+"){
        key = true;
    }else{
        key = false;
    };
    newsLie[i].firstElementChild.lastElementChild.onclick = function(){
        clearTimeout($time1);
        var _this = this;
        _this.parentNode.nextElementSibling.style.marginTop = "25px";
        if(key == true){
            if(_this.innerHTML == "+"){
                _this.innerHTML = "-";
            };
            var ent = _this.parentNode.parentNode;
            var sib = sibling(ent);
            for(var i=0;i<sib.length;i++){
                var oper = sib[i].lastElementChild.clientHeight;
                if(oper>0){
                    sib[i].lastElementChild.style.height = 0 + "px";
                    sib[i].firstElementChild.lastElementChild.innerHTML = "+";
                    sib[i].lastElementChild.style.marginTop = "0px";
                };
            };
            aa();
            function aa(){
                var size = parseInt(_this.parentNode.nextElementSibling.style.height);
                size+=5;
                if(size<=153){
                    _this.parentNode.nextElementSibling.style.height = size + "px";
                }else{
                    _this.parentNode.nextElementSibling.style.height = 153 + "px";
                };
                $time1 = setTimeout(aa,30);
            };
        };
    };
};
//滚动条
var fixed = document.getElementsByClassName("fixed")[0];
var ovize = fixed.offsetTop;
window.onscroll = function(){
    var _size = document.body.scrollTop || document.documentElement.scrollTop;
    if(_size >= ovize){
        fixed.style.position = "fixed";
        fixed.style.top = "0px";
        fixed.style.width = "18%";
    }else{
        fixed.style.position = "static";
        fixed.style.width = "100%";
    };
};

