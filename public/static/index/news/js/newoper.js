/**
 * Created by Administrator on 2017/4/8.
 * @author:Allen
 * 此文件提供了方法，只需调用方法名称！
 * 
 * */
var size = {
	aloper:function(){
		    $(".num,.num2").hover(function(){
				$(this).css({
					"opacity":0.6
				});
			},function(){
				$(this).css({
					"opacity":1
				});
			});
	        //点击举报
			$(".cancel").on("click",function(){
				$(".shadow").css({"display":"none"});
				$(".report").css({"display":"none"});
			});
			
			$("#radio-complain").focus(function(){
				$(".complain-text").css({"display":"block"});
			});
	},
	//增加评论
	addfun:function(comment){
		var txt = $(".text").val();
		if(txt != "" ){
            var url = comment.data('url');
            var article_id = comment.data('article');
            var data = {'article_id':article_id,'content':txt}
            $.ajax({
                url:url,
                data:data,
                dataType:'json',
                type:'POST',
                success:function(msg){
                	console.log(msg)
                    if(msg.code==1){
                        var monp = {
                            "uid": 1,
                            "create_time":msg.data.create_time,
                            "title":txt,
                            "zan":0
                        };
						list(msg.data)
                    }
                    if(msg.code==0){
                    }
                }
            });
		}else{
			$(".text").attr("placeholder","您的评论信息为空！！！");
			alert("您的评论信息为空！！！");
			$(".text").blur(function(){
				$(this).attr("placeholder","写下您的评论...");
			});
			return false;
		}
		
	},
	//评论数
	lavlen:function(){
		$(function(){
            var num = $(".playout").children().length;
            $(".oper-new").on("click",function(){
				var ht = $(".ofirst i").html();
				if($(".text").val() != ""){
					ht++;
					$(".ofirst i").html(ht);
				};
				if(ht >= 6){
					var height = $(".playout").height();
					$(".playout").css({
						"height":height,
						"overflow":"hidden"
					});
				};
				$(".dis").click(function(){
					$(".playout").css({
						"height":"auto",
						"overflow":"visible"
					});
				});
			});
		});
	},
	juebao:function(){
		$(".report-header>img").click(function(){
			$(".shadow,.report").hide();
			$(".shadow,.cuosir").hide();
		});
		$(".icon>span:nth-child(3)").click(function(){
			$(".shadow,.cuosir").show();
		});
	}
};
size.lavlen();
size.juebao();
function list(list) {
    var tpl = document.getElementById('template').innerHTML;
    laytpl(tpl).render(list, function (html) {
        document.getElementById('list').innerHTML = html+document.getElementById('list').innerHTML;
    });
}
