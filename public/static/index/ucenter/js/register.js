/**
 * Created by allen on 2017/7/1.
 */
//window.onload = function(e){
//    var e = e || event;
//    e.preventDefault();
//};
//用户名
var form = document.forms[0];
form.phone.onfocus = function(){
    var tips = this.parentNode.lastElementChild;
    var txt = this.value;
    if(txt == ""){
        tips.innerHTML = "请输入您的账号";
        tips.className = "prompt";
    }else{
        tips.innerHTML = "";
    }
};
form.phone.onblur = getplone;
function getplone(e){
    var key = false;
    var tips = form.phone.parentNode.lastElementChild;
    var txt = form.phone.value;
    if(txt == ""){
        if(e === 1){
            tips.innerHTML = "您的账号不能为空";
            tips.className = "prompt";
        }else{
            tips.innerHTML = "";
            tips.className = "";
        }
    }else{
        if(/^1[0-9]{10}$/.test(txt) || /^.+@\w+(\.\w+)+$/i.test(txt)){
            tips.innerHTML = "您输入的账号格式正确";
            tips.className = "prompt2";
        }else{
            tips.innerHTML = "您输入的账号格式有误";
            tips.className = "prompt";
        }
    }
};
//密码
form.pwd.onfocus = function(){
    var pwd = this.parentNode.lastElementChild;
    var num = this.value;
    if(num == ""){
        pwd.innerHTML = "请输您的密码至少6位";
        pwd.className = "prompt";
    }else{
        pwd.innerHTML = "";
    }
};
form.pwd.onblur = getpwd;
function getpwd(e){
    var key = false;
    var num = form.pwd.value;
    var pwd = form.pwd.parentNode.lastElementChild;
    if(num == ""){
        if(e === 1){
            pwd.innerHTML = "您的密码不能为空";
            pwd.className = "prompt";
        }else{
            pwd.innerHTML = "";
            pwd.className = "";
        }
    }else{
        if(num.length>=6){
            pwd.innerHTML = "密码格式正确";
            pwd.className = "prompt2";
        }else{
            pwd.innerHTML = "输入的密码格式不正确";
            pwd.className = "prompt";
        }
    }
};
//确认密码
form.newpwd.onfocus = function(){
    var num = this.parentNode.lastElementChild;
    var txt = this.value;
    if(txt == ""){
        num.innerHTML = "请再次输入您的密码";
        num.className = "prompt";
    }else{
        num.innerHTML = "";
    }
};
form.newpwd.onblur = getnewpwd;
function getnewpwd(e){
    var key = false;
    var txt = form.newpwd.value;
    var num = form.newpwd.parentNode.lastElementChild;
    if(txt == ""){
        if(e === 1){
            num.innerHTML = "您输入的密码为空";
            num.className = "prompt";
        }else{
            num.innerHTML = "";
            num.className = "";
        }
    }else{
        if(txt == form.pwd.value){
            num.innerHTML = "密码输入正确";
            num.className = "prompt2";
        }else{
            num.innerHTML = "两次输入的密码不一致";
            num.className = "prompt";
        }
    }
};
/**
 * 发送验证码
 */
$(document).ready(function(){
    var options = {
        type:"POST",//请求方式：get或post
        dataType:"json",//数据返回类型：xml、json、script
        beforeSerialize:function(){
            //$("#txt2").val("java");//如：改变元素的值
        },
        beforeSubmit:function(){

        },
        success:function(result){//表单提交成功回调函数
            debugger;
            if(result['status']==200){
                window.location.href="../manager/login";
            }else{
                alert(result['message']);
            }
        },
        error:function(err){
            alert("表单提交异常！");
        }
    };
    $("#register_form").ajaxForm(options);
});
$("#code").click(function(){
    var userflag = $("input[name='phone']").val();
    if(userflag){
        $.ajax({
            url:"/user/manager/makeCode",
            data:{'username':userflag},
            dataType:'json',
            type:'get',
            success:function(result){
                if(result['status']==200){
                    $("#code").attr("disabled",true);
                    $secTime = 60 ;
                    var inter = setInterval(function(){//定时器，设置验证码倒计时，防止用户连续请求
                        if($secTime-- > 0){
                            $("#code").val($secTime);
                        }else{
                            $("#code").attr("disabled",false).val("发送验证码");;
                            window.clearInterval(inter);//清除定时器
                        }
                    },1000);
                }else{
                    alert(result['message']);
                }
            }
        });

    }else{
        alert('请先输入手机号或邮箱');
    }
});
var cli = document.getElementsByClassName("cli")[0];
cli.onclick = function(){
    if(this.getAttribute("data") == "0"){
        this.firstElementChild.style.display = "block";
        this.firstElementChild.style.marginTop = "3px";
        this.nextElementSibling.style.color = "#84be43";
        this.setAttribute("data","1");
    }else{
        this.firstElementChild.style.display = "none";
        this.nextElementSibling.style.color = "black";
        this.setAttribute("data","0");
    }
};

form.onsubmit = function(){

    //var e = e || event;
    //e.preventDefault();
    var key = true;
    if(!getplone(1)){
        key=false;
    };
    if(!getpwd(1)){
        key = false;
    };
    if(!getnewpwd(1)){
        key = false;
    };
    if(document.getElementsByClassName("cli")[0].getAttribute("data") != "1"){
        alert("您没有同意《网站注册协议》");
    }
};























































