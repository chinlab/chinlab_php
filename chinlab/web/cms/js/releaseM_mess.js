var postData = [];
function setSelectUserNo(radioObj) {

    var radioCheck = $(radioObj).val();


    if ("1" == radioCheck) {
        $(radioObj).attr("checked", false);
        $(radioObj).val("0");
    } else {
        $(radioObj).val("1");
    }

    var maxSelect = 0;
    $("input:radio").each(function () {
        if (this.checked) {
            maxSelect += 1;
        }
    });
    if (maxSelect > 3) {
        $(radioObj).attr("checked", false);
        $(radioObj).val(0);
        layer.msg("最多可设置三个推送时间", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
        return;
    }
    if ("1" == $(radioObj).val()) {
        postData[$(radioObj).attr("data-id")].is_push = 1;
        $(radioObj).parent().siblings(".push_time").addClass("isShow")
    } else {
        postData[$(radioObj).attr("data-id")].is_push = 0;
        postData[$(radioObj).attr("data-id")].push_time = 0;
        $(radioObj).parent().siblings(".push_time").removeClass("isShow")
    }
}

$(document).ready(function () {

    //初始化数据
    var dt = decodeURIComponent($("#info_detail").html());
    //console.log(dt)
    var ev = $.parseJSON(dt);
    var html_text = "";
    var post_ids = [];
    var index = 0;
    $.each(ev, function (k, v) {
        postData[v.material_id] = {
            material_id: v.material_id,
            publish_time: 0,
            is_push: 0,
            push_time: 0,
            start_time: 0,
            end_time: 9999999999
        };
        html_text += '<li class="col-sm-12">'
        html_text += '<span class="col-sm-3 col-sm-offset-1">' + v.title + '</span>';
        html_text += '<div class="col-sm-1">';
        html_text += '<input type="radio" class="radio_select" data-id="' + v.material_id + '"/>&nbsp;&nbsp;是否推送';
        html_text += '</div>';
        html_text += '<div class="col-sm-3 push_time">';
        html_text += '<input type="text" class="form-control start_time" data-id="' + v.material_id + '">';
        html_text += '<span class="warn">时间已被设置，不可重复！</span>';
        html_text += '</div>';
        html_text += '</li>';
        post_ids[index] = v.material_id;
        index = index + 1;
    });
    //console.log(postData);
    $(".newslist").html(html_text);
    //时间
    $(".radio_select").click(function () {
        setSelectUserNo($(this))
    });
    //推送日期
    var date1 = new Date();
    var d = new Date();
    d.setMonth(d.getMonth() + 3);
    $('.start_time').datetimepicker({
        language: 'zh-CN',
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true,
        startDate: date1,
        endDate: d
    }).on('changeDate', function (e) {
        var material_id = $(this).attr('data-id');
        var startTime = e.date.getTime();
        var errors = "";
        $.each(post_ids, function (k, v) {
            var tmpData = postData[v];
            if (tmpData.material_id != material_id && tmpData.is_push == 1 && tmpData.push_time == parseInt(startTime/1000)) {
                errors = "1";
            }
        });
        if (errors == "1") {
            $(this).parent().find("span").show();
            return;
        }
        var changeDataObj = $(this);
        $.post("/cms_checktime.php", {
            "material_id": material_id,
            "push_time": parseInt(startTime / 1000)
        }, function (data) {
            if (data.state == 0) {
                postData[material_id].push_time = parseInt(startTime / 1000);
                changeDataObj.parent().find("span").hide();
            } else {
                changeDataObj.parent().find("span").show();
            }
        }, "json");
    });
    //发布时间
    $('.publish_time').datetimepicker({
        language: 'zh-CN',
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true,
        startDate: date1,
        endDate: d
    }).on('changeDate', function (e) {
        var startTime = e.date.getTime();
        $.each(post_ids, function (k, v) {
            postData[v].publish_time = parseInt(startTime / 1000);
        });
    });

    //发布
    $("#addnews").click(function () {
        var nowtime = parseInt((new Date()).getTime()/1000);
        var errors = "";
        var f_data = [];
        var index  = 0;
        $.each(post_ids, function (k, v) {
            if (errors != '1') {
                var tmpData = postData[v];
                if (tmpData.is_push == 1 && tmpData.push_time == 0) {
                    layer.msg("请你设置推送时间", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
                    errors = '1';
                }
                if (tmpData.is_push == 1 && (tmpData.push_time - nowtime) > 86400 * 10) {
                    layer.msg("推送时间不能大于当前时间十天", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
                    errors = '1';
                }
                if (tmpData.is_push == 1 && tmpData.push_time !=0 && tmpData.publish_time != 0 && parseInt(tmpData.push_time) < parseInt(tmpData.publish_time)) {
                    layer.msg("推送时间不能小于发布时间", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
                    errors = '1';
                }
                if (tmpData.publish_time == 0) {
                    layer.msg("请你设置发布时间", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
                    errors = '1';
                }
                if (tmpData.publish_time > 0 && ((tmpData.publish_time - nowtime) > 86400 * 10)) {
                    layer.msg("发布时间不能大于当前时间十天", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
                    errors = '1';
                }
                f_data[index] = tmpData;
                index = index + 1;
            }
        });
        if (errors == '1') {
            return "";
        }
        $.post("/cms_publish.php", {
            "data": f_data
        }, function (data) {
            if (data.state == 0) {
                layer.msg('添加成功', {icon: 1, closeBtn: 1, shadeClose: true,time:1000},function(){
					var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
		        	parent.layer.close(index);
				});
            } else {
                layer.msg(data.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
            }
        }, "json");
    });
});