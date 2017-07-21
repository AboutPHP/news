/**
 * Created by shenshulong on 2017/7/11.
 */
//opacity
var cancel = document.getElementsByClassName("cancel")[0];
var img = cancel.getElementsByTagName("img")[0];
cancel.parentNode.style.opacity = 1;
var $time,num;
img.onclick = function(){
    clearTimeout($time);
    var _this = this;
    aa();
    function aa(){
        var num = parseFloat(_this.parentNode.parentNode.style.opacity);
        num -= 0.1;
        if(num>0){
            _this.parentNode.parentNode.style.opacity = num;
        }else{
            _this.parentNode.parentNode.style.opacity = 0;
        }
        if(_this.parentNode.parentNode.style.opacity == 0){
            _this.parentNode.parentNode.style.display = "none";
        }else{
            _this.parentNode.parentNode.style.display = "block";
        };
        setTimeout(aa,30);
    };
};


