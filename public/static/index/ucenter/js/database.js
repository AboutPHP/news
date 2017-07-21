/**
 * Created by shenshulong on 2017/7/18.
 */

/**
 * @param {Object} _options对象
 * 封装了画布式数据统计
 * time:2017/6/17
 * author:allen
 */
function drawCircle(_options) {
    var options = _options || {}; //获取或定义options对象;
    //百分比
    //options.angle = options.angle || 1;
    //线宽
    options.lineWidth = options.lineWidth || 10;
    //线结尾处用什么表示
    options.lineCap = options.lineCap || 'square';
    //开启画布
    var oBoxOne = document.getElementById(options.id);
    var sCenter = oBoxOne.width / 2;
    var ctx = oBoxOne.getContext('2d');
    var nBegin = Math.PI / 2;
    var nEnd = Math.PI * 2;
    //是否含有渐变
    if(options.id == "one"){
        var grd = ctx.createLinearGradient(0, 0, oBoxOne.width, 0);
        grd.addColorStop(0, '#df4771');
        grd.addColorStop(0.2, '#b4508f');
        grd.addColorStop(0.4, '#a95195');
        grd.addColorStop(0.6, '#a25098');
        grd.addColorStop(0.8, '#a65096');
        grd.addColorStop(1, '#cf4b7e');
        //颜色
        options.color = grd;
    }else if(options.id == "one1"){
        var grd = ctx.createLinearGradient(0, 0, oBoxOne.width, 0);
        grd.addColorStop(0, '#f0910d');
        grd.addColorStop(0.2, '#ef8812');
        grd.addColorStop(0.4, '#ee7f16');
        grd.addColorStop(0.6, '#eb6224');
        grd.addColorStop(0.8, '#ef8812');
        grd.addColorStop(1, '#f0910d');
        //颜色
        options.color = grd;
    }else if(options.id == "one2"){
        var grd = ctx.createLinearGradient(0, 0, oBoxOne.width, 0);
        grd.addColorStop(0, '#f2ca00');
        grd.addColorStop(0.2, '#e5c800');
        grd.addColorStop(0.4, '#c0c105');
        grd.addColorStop(0.6, '#9aba1a');
        grd.addColorStop(0.8, '#e5c800');
        grd.addColorStop(1, '#f2ca00');
        //颜色
        options.color = grd;
    }else if(options.id == "one3"){
        var grd = ctx.createLinearGradient(0, 0, oBoxOne.width, 0);
        grd.addColorStop(0, '#89cbde');
        grd.addColorStop(0.2, '#7ec5dc');
        grd.addColorStop(0.4, '#51abd2');
        grd.addColorStop(0.6, '#1d95c8');
        grd.addColorStop(0.8, '#7ec5dc');
        grd.addColorStop(1, '#89cbde');
        //颜色
        options.color = grd;
    }else{
        options.color = options.color || '#fff';
    };
    ctx.textAlign = 'center';
    ctx.font = 'normal normal bold 20px Arial';
    //中间字体的颜色
    ctx.fillStyle = "#be260d";
    ctx.fillText((options.angle*100)+'%', sCenter, sCenter+10);
    /*ctx.strokeStyle = grd;
     ctx.strokeText((options.angle * 100) + '%', sCenter, sCenter);*/
    ctx.lineCap = options.lineCap;
    //ctx.strokeStyle = options.color == 'grd' ? grd : options.color;
    ctx.lineWidth = options.lineWidth;
    ctx.beginPath();
    ctx.strokeStyle = '#ededee';
    ctx.arc(sCenter, sCenter, (sCenter - options.lineWidth), -nBegin, nEnd, true);
    ctx.stroke();
    var imd = ctx.getImageData(0, 0, 260, 260);
    function draw(current) {
        ctx.putImageData(imd, 0, 0);
        ctx.beginPath();
        ctx.strokeStyle = options.color == 'grd' ? grd : options.color;
        ctx.arc(sCenter, sCenter, (sCenter - options.lineWidth)+10, -nBegin, (nEnd * current) - nBegin, true);
        ctx.stroke();
    };
    var t = 0;
    var timer = null;
    function loadCanvas(angle) {
        timer = setInterval(function() {
            if(t > angle) {
                draw(options.angle);
                clearInterval(timer);
            } else {
                draw(t);
                t += 0.02;
            };
        }, 20);
    };
    loadCanvas(options.angle);
    timer = null;
};
/**
 * id:代表每一个数据统计图
 * color:代表覆盖的颜色
 * angle:代表百分比的数据统计
 * lineWidth：代表线宽
 */
drawCircle({
    id: 'one',
    color: "#f22a03",
    angle: 1,
    lineWidth: 24
});
drawCircle({
    id: 'one1',
    color: "#fda533",
    angle: 0.5,
    lineWidth: 24
});
drawCircle({
    id: 'one2',
    color: "#fda533",
    angle: 0.3,
    lineWidth: 24
});
drawCircle({
    id: 'one3',
    color: "#fda533",
    angle: 0.2,
    lineWidth: 24
});
















