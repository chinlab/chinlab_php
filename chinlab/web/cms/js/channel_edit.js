$(document).ready(function () {

    var detailInfo = $("#channel_detail").html();
    detailInfo = JSON.parse(detailInfo);
    if ( typeof(detailInfo.channel_no) != "undefined") {
        $("#channel_no").val(detailInfo.channel_no);
        $("#channel_name").val(detailInfo.channel_name);
    }

    $(document).on('click', '#saveedit', function () {

        content = $("#channel_name").val();
        id = $("#channel_no").val();
        if (content == "") {
            layer.msg('名称不能为空', {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
            return;
        }
        $.post("/cms_channelcommit.php", {"channel_no": id, "channel_name": content}, function (data) {
            if (data.state == 0) {
                layer.msg('添加成功', {icon: 1, closeBtn: 1, shadeClose: true,time:1000},function(){
	    						var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
					        	parent.layer.close(index);
	    					});
            } else {
                layer.msg(data.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
            }
        }, "json")
    });
});