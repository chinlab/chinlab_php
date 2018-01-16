$(document).ready(function () {


    $(document).on('click', '.channel_delete', function () {
        $trid = $(this).attr("data-id");
        layer.confirm('确定要删除？', {
            btn: ['确定', '取消'] //按钮
        }, function ($index) {
            layer.close($index);
            var loadingindex = layer.load(1, {
                shade: [0.5, '#ddd'] //0.1透明度的白色背景
            });
            $.post("/cms_channeldelete.php", {"channel_no": $trid}, function (data) {
                layer.close(loadingindex);
                if (data.state == 0) {
                    dataTable.draw();
                    layer.msg('删除成功', {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
                } else {
                    layer.msg(data.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
                }
            }, "json")
        });
    });




    $(document).on('click', '.channel_edit', function () {
        $(document.body).css({
            "overflow-x": "hidden",
            "overflow-y": "hidden"
        });
        powidth = $(window).width() - 50;
        poheight = $(window).height() - 50;
        layer.open({
            type: 2,
            title: "频道编辑",
            fix: false,
            shadeClose: true,
            maxmin: true,
            area: [powidth + 'px', poheight + 'px'],
            content: "/cms_channeledit.php?channel_no=" + $(this).attr("data-id"),
            end: function() {
                $(document.body).css({
                    "overflow-x": "auto",
                    "overflow-y": "auto"
                });
                dataTable.draw();
            }
        });
    });

    //js格式化时间
    function getDate(tm){ 
		var tt=new Date(parseInt(tm) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ") 
		return tt; 
	} 
    var dataTable = $('#datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "ordering": false,
        "bLengthChange": false, //去掉每页显示多少条数据方法
        "iDisplayLength": 10,
        "stateSave": false,
        "ajax": {
            url: "/cms_channellist.php", // json datasource
            type: "post",  // method  , by default get
            error: function () {  // error handling
                $(".employee-grid-error").html("");
                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employee-grid_processing").css("display", "none");
            },
            "data": function (d) {
            }
        },
        "columns": [
            {"data": "channel_no"},
            {"data": "channel_name"},
            {
                "data": function (data) {
                    var user_name = "未知";
                    if (data.user_name != 0 && data.user_name != "") {
                        user_name = data.user_name;
                    }
                    return user_name;
                }
            },
            {
                "data": function (data) {
//                  var now_time = new Date(parseInt(data.create_time) * 1000);
                    return getDate(data.create_time);
                }
            }
        ],
        "oLanguage": {//对表格国际化
            "sLengthMenu": "每页显示 _MENU_条",
            "sZeroRecords": "没有找到符合条件的数据",
            //  "sProcessing": "&lt;img src=’./loading.gif’ /&gt;",
            "sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
            "sInfoEmpty": "木有记录",
            "sInfoFiltered": "(从 _MAX_ 条记录中过滤)",
            "sSearch": "搜索：",
            "oPaginate": {
                "sFirst": "首页",
                "sPrevious": "前一页",
                "sNext": "后一页",
                "sLast": "尾页"
            }
        },
        "columnDefs": [
            {
                "targets": [4],
                "data": function (data) {
                    return data.channel_no;
                },
                "render": function (data, type, full) {
                    return '<a class="channel_edit btn btn-info" data-id="' + data + '" href="javascript:void(0)">编辑</a>&nbsp;&nbsp;&nbsp;<a class="channel_delete btn btn-info" data-id="' + data + '" href="javascript:void(0)">删除</a>';
                }
            }
        ]
    });

});