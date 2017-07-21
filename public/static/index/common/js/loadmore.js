;(function(w,$){
    var loadmore = {
        get : function(callback, config){
            var config = config ? config : {}; /*防止未传参数报错*/
            /*默认通过点击加载更多*/
            $(document).on('click', '.js-load-more', function(){
                console.log(config)
                callback && callback(config, config.offset, config.size);
            });
            /*通过自动监听滚动事件加载更多,可选支持*/
            config.isEnd = false; /*结束标志*/
            config.isAjax = false; /*防止滚动过快，服务端没来得及响应造成多次请求*/
            $(window).scroll(function(){
                /*是否开启滚动加载*/
                if(!config.scroll){
                    return;
                }
                /*滚动加载时如果已经没有更多的数据了、正在发生请求时，不能继续进行*/
                if(config.isEnd == true || config.isAjax == true){
                    return;
                }
                /*当滚动到最底部以上100像素时， 加载新内容*/
                if ($(document).height() - $(this).scrollTop() - $(this).height()<100){
                    callback && callback(config, config.offset, config.size);
                }
            });
            // /*第一次自动加载*/
            // callback && callback(config, pageStart, pageSize);
        },
    }
    $.loadmore = loadmore;
})(window, window.jQuery);