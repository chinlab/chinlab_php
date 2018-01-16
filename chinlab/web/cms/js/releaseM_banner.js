var postData = [];
function setSelectUserNo(radioObj) {

    var radioCheck = $(radioObj).val();
    if ("1" == radioCheck) {
        $(radioObj).attr("checked", false);
        $(radioObj).val("0");
    } else {
        $(radioObj).val("1");
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
//js格式化时间
function formatDate(tm) {
        var tt=new Date(parseInt(tm) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ")
        return tt;
}

$(document).ready(function () {

    //初始化数据
    var dt = decodeURIComponent($("#info_detail").html());
    var ev = $.parseJSON(dt);
    $("#news_channel").html(ev.finished.channel_name);
    $("#show_type").html("Banner");
    var html_text = "";
    var checkId = ev.finished.material_id;
    var index = 0;
    var itemsIndex = [];
    var itemsMaterialId = [];
    $.each(ev.cacheInfo, function (k, v) {
        postData[v.material_id] = {
            material_id: v.material_id,
            publish_time: v.publish_time,
            is_push: 0,
            push_time: 0,
            start_time: v.start_time,
            end_time: v.end_time
        };
        html_text += '<li class="form-group" data-id="' + v.material_id + '">';
        html_text += '<label class="col-sm-2 control-label" for="user_name"';
        html_text += 'style="text-align: left;">资讯内容标题' + (parseInt(k) + 1) + ':</label>';
        html_text += '<div class="col-sm-10" style="margin-top: 6px">';
        html_text += '<span>' + v.title + '&nbsp;&nbsp;&nbsp;</span>';
        html_text += '<span class="validTime" style="color:red;">';
        if (v.start_time != "0") {
            html_text += '有效时间：' + formatDate(v.start_time)+ '~' + formatDate(v.end_time);
        }
        html_text += '</span></div></li>';
        if (v.news_type == 0) {
            itemsIndex[index] = 0;
        }
        itemsMaterialId[index] = v.material_id;
        index = index + 1;
    });
    itemsMaterialId[index] = ev.finished.material_id;
    postData[ev.finished.material_id] = {
        material_id: ev.finished.material_id,
        publish_time: 0,
        is_push: 0,
        push_time: 0,
        start_time: 0,
        end_time: 9999999999
    };
    console.log(postData);
    html_text += '<li class="form-group" data-id="' + ev.finished.material_id + '">';
    html_text += '<label class="col-sm-2 control-label" for="user_name"';
    html_text += 'style="text-align: left;">发布新内容:</label>';
    html_text += '<div class="col-sm-3" style="margin-top: 6px;">' + ev.finished.title + '</div> <div class="col-sm-1"> <input type="radio" id="radio" data-id="' + ev.finished.material_id + '"/>&nbsp;&nbsp;是否推送 </div>';
    html_text += '<div class="col-sm-4 push_time">';
    html_text += '<input type="text" class="form-control" id="push_time" data-id="' + ev.finished.material_id + '">';
    html_text += '<span class="warn">时间已被设置，不可重复！</span></div></li>';
    //广告的可以设置开始时间和结束时间
    if (ev.finished.news_type == 0) {
        $("#item_publish_time").show();
        $("#item_end_time").show();
    }
    //可以选择的广告位置
    var selectLoction = '<option value="default">请选择</option>';
    for(var i = 0; i < ev.limit; i++) {
        selectLoction += '<option value="' + (i) + '">第'+(i+1)+'位置</option>';
        /*
        if (typeof(itemsIndex[i]) != 'undefined' && itemsIndex[i] == 0) {
        } else {
            selectLoction += '<option value="' + (i) + '">第'+(i+1)+'位置</option>';
        }
        */
    }
    $('#sorttable').html(html_text);
    $("#message_type").html(selectLoction);
    $('#qBeginTime').attr("data-id", ev.finished.material_id);
    $('#qEndTime').attr("data-id", ev.finished.material_id);

    //选择框
    $("#radio").click(function () {
        setSelectUserNo($(this))
    });
    //设置时间 开始时间
    var date1 = new Date();
    var d = new Date();
    d.setMonth(d.getMonth() + 3);
    $('#qBeginTime').datetimepicker({
        language: 'zh-CN',
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true,
        startDate: date1,
        endDate: d
    }).on('changeDate', function (e) {
        var startTime = e.date.getTime();
        postData[$(this).attr("data-id")].publish_time = parseInt(startTime / 1000);
        postData[$(this).attr("data-id")].start_time = parseInt(startTime / 1000);
    });
    //结束时间：
    $('#qEndTime').datetimepicker({
        language: 'zh-CN',
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true,
        endDate: d
    }).on('changeDate', function (e) {
        var endTime = e.date.getTime();
        postData[$(this).attr("data-id")].end_time = parseInt(endTime / 1000);
    });
    //推送时间
    $('#push_time').datetimepicker({
        language: 'zh-CN',
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true,
        startDate: date1,
        endDate: d
    }).on('changeDate', function (e) {
        var material_id = $(this).attr('data-id');
        var startTime = e.date.getTime();
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

    //发布
    $("#addnews").click(function () {

        var nowtime = parseInt((new Date()).getTime()/1000);
        var checkData = postData[checkId];
        if (checkData.is_push == 1 && checkData.push_time == 0) {
            layer.msg("请你设置推送时间", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
            return;
        }

        var selectLo = $("#message_type").val();
        if (selectLo == "default") {
            layer.msg("请你选择资讯位置", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
            return;
        }
        if (ev.finished.news_type == 0 && (checkData.start_time == 0 || checkData.end_time == 9999999999)) {
            layer.msg("请您设置发布时间和结束时间", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
            return;
        }
        if (ev.finished.news_type == 0 && (checkData.start_time - nowtime) > 86400 * 10) {
            layer.msg("发布时间不能大于当前时间十天", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
            return;
        }
        if (ev.finished.news_type == 0 && (checkData.end_time < checkData.start_time)) {
            layer.msg("结束时间不能小于发布时间", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
            return;
        }
        if (checkData.is_push == 1 && parseInt(checkData.push_time) < parseInt(checkData.start_time)) {
            layer.msg("推送时间不能小于发布时间", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
            return;
        }
        if (checkData.is_push == 1 && (checkData.push_time - nowtime) > 86400 * 10) {
            layer.msg("推送时间不能大于当前时间十天", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
            errors = '1';
        }
        itemsMaterialId.splice(itemsMaterialId.length - 1, 1);
        if (typeof(itemsMaterialId[parseInt(selectLo)]) != 'undefined') {
            itemsMaterialId[parseInt(selectLo)] = checkId;
        } else {
            itemsMaterialId[itemsMaterialId.length] = checkId;
        }
        var f_data = [];
        var index = 0;
        $.each(itemsMaterialId, function(k, v) {
            f_data[index] = postData[v];
            index = index + 1;
        });

        $.post("/cms_publishbanner.php", {
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