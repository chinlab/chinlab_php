/**
 * Created by user on 2017/4/11.
 */
jQuery(document).ready(function($){

    var layerOrderTipIndex;
    var indexTip = 0;
    var leftIndexPx = 0;
    function getordertips() {
        $.getJSON("/order_getordertip.php",function(data){
            var orderFlag = false;
            $.each(data.data.list, function(k, v) {
                if (v.count > 0) {
                    $(".ordertip" + k).html(v.desc + "&nbsp;&nbsp;&nbsp;" + '<span style="color: white; background-color: red; border-radius:100%;">&nbsp;' + v.count + "&nbsp;</span>");
                    orderFlag = true;
                }
            });
            if (orderFlag) {
                //页面层
                layerOrderTipIndex = layer.open({
                    type: 1,
                    //skin: 'layui-layer-rim', //加上边框
                    area: ['250px', '180px'], //宽高
                    title: "",
                    shade: 0,
                    offset: 'rb',
                    id: 'LAY_layuipro',
                    content: data.data.desc
                });
                if (indexTip <= 0) {
                    leftIndexPx = parseInt($('#LAY_layuipro').parent().css("left")) - 40;
                    indexTip += 1;
                }
                $("#LAY_layuipro").parent().css("left", ( leftIndexPx + "px"));
            }
        });
    }

    getordertips();
    setInterval(function () {
        $.getJSON("/order_getordertip.php",function(data){
            layer.close(layerOrderTipIndex);
            var orderFlag = false;
            $.each(data.data.list, function(k, v) {
                if (v.count > 0) {
                    $(".ordertip" + k).html(v.desc + "&nbsp;&nbsp;&nbsp;" + '<span style="color: white; background-color: red; border-radius:100%;">&nbsp;' + v.count + "&nbsp;</span>");
                    orderFlag = true;
                }
            });
            if (orderFlag) {
                //页面层
                layerOrderTipIndex = layer.open({
                    type: 1,
                    //skin: 'layui-layer-rim', //加上边框
                    area: ['250px', '180px'], //宽高
                    title: "",
                    shade: 0,
                    offset: 'rb',
                    id: 'LAY_layuipro',
                    content: data.data.desc
                });
                if (indexTip <= 0) {
                    leftIndexPx = parseInt($('#LAY_layuipro').parent().css("left")) - 40;
                    indexTip += 1;
                }
                $("#LAY_layuipro").parent().css("left", ( leftIndexPx + "px"));
            }
        });
    }, 600000);
});