//时间格式
function getDate(input){
	var oDate = new Date(input);
	function p(s) {
        return s < 10 ? '0' + s: s;
    }
	return oDate.getFullYear()+'-'+(oDate.getMonth()+1)+'-'+oDate.getDate()+' '+oDate.getHours()+':'+p(oDate.getMinutes())+':'+p(oDate.getSeconds());
} 

$(document).ready(function(){
	//表格提示卡
	var tdtipindex = "";
	$(document).on("mouseover", "#datatable td,#datatable th", function() {
		var tdcontent = $(this).text();
		tdtipindex = layer.tips(tdcontent, $(this), {
			tips: [1, '#333333'],
			time: 0
		});
	}).on("mouseout", "#datatable td,#datatable th", function() {
		layer.close(tdtipindex);
	});
	
	//时间控件
	$('#redact_start_time').datetimepicker({
		language: 'zh-CN',
		format: "yyyy-mm-dd hh:ii"
	}).on('changeDate', function(ev) {});
	
	
	$('#redact_end_time').datetimepicker({
		language: 'zh-CN',
		format: "yyyy-mm-dd hh:ii"
	}).on('changeDate', function(ev) {});
	
	//tab切换
	var status = $("#myTab").find(".active").attr("data-status");
	//console.log(status)
 	$('#myTab li').click(function (e) {
	    $(this).tab('show');
	    status = $(this).attr("data-status");
	    console.log(status)
	    dataTable.draw();
	})
 	
 	//加载表格数据
   	var dataTable = $('#datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "ordering": false,
        "bLengthChange": false, //去掉每页显示多少条数据方法
        "iDisplayLength": 20,
        "stateSave": false,
        "ajax": {
            url:"/atdoctor/doctormanagementlist.php", // json datasource
            type: "post",  // method  , by default get
            error: function () {  // error handling
                $(".employee-grid-error").html("");
                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employee-grid_processing").css("display", "none");
            },
            "data": function (d) {
                //添加额外的参数传给服务器
                d.is_authentication = status;
                d.doctor_name = $("#doctor_name").val();
                d.hospital_name = $("#hospital_name").val();
                d.doctor_mobile = $("#doctor_mobile").val();
            }
        },
        "columns": [
        	{"data":null,title:"id","bSortable": false},
            {"data": "doctor_name"},
            {"data": "hospital_name"},
            {"data": "section_name"},
            {"data": "doctor_position_desc"},
            {"data": "doctor_mobile"},
            {
            	"data": function (data) {
					var creatTime = getDate(parseInt(data.create_time)*1000);
					return creatTime;
                }
            },
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
                "targets": [7],
                "data": function (data) {
                    return data;
                },
                "render": function (data, type, full) {
					if (data.is_authentication == 3) {	
						return '<a class="check" data-id="' + data.doctor_id + '" href="javascript:void(0)">审核<a>';
					} else{
						return '<a class="view" data-id="' + data.doctor_id + '" href="javascript:void(0)">查看<a>';
					}
                }
            }
        ]
    });
 	dataTable.on('draw',function() {
	   dataTable.column(0, {
	       search: 'applied',
	       order: 'applied'
	   }).nodes().each(function(cell, i) {
	       //i 从0开始，所以这里先加1
	       i = i+1;
	       //服务器模式下获取分页信息，使用 DT 提供的 API 直接获取分页信息
	       var page = dataTable.page.info();
	       //当前第几页，从0开始
	       var pageno = page.page;
	       //每页数据
	       var length = page.length;
	       //行号等于 页数*每页数据长度+行号
	       var columnIndex = (i+pageno*length);
	       cell.innerHTML = columnIndex;
	   });
	});  
 	//搜索
 	$("#searchbtn").on('click',function(){
 		dataTable.draw();
 	})
 	//审核	
 	var layerindex = "";
 	$(document).on("click", ".check",function() {
		var trid = $(this).attr("data-id");
		$(document.body).css({
			"overflow-x": "hidden",
			"overflow-y": "hidden"
		});
		powidth = $(window).width() - 50;
		poheight = $(window).height() - 50;
		layerindex = layer.open({
			type: 2,
			title: "审核",
			fix: false,
			shadeClose: false,
			maxmin: true,
			area: [powidth + 'px', poheight + 'px'],
			content: "/chunfeng/doctorraudit.php?doctor_id="+trid,
			end: function() {
				dataTable.draw();
				$(document.body).css({
					"overflow-x": "auto",
					"overflow-y": "auto"
				});
			}
		});
	});
	//查看
	var layerredact = "";
 	$(document).on("click", ".view",function() {
		var trid = $(this).attr("data-id");
		$(document.body).css({
			"overflow-x": "hidden",
			"overflow-y": "hidden"
		});
		powidth = $(window).width() - 50;
		poheight = $(window).height() - 50;
		layerredact = layer.open({
			type: 2,
			title: "查看",
			width:"500",
			fix: false,
			shadeClose: false,
			maxmin: true,
			area: [powidth + 'px', poheight + 'px'],
			content: "/chunfeng/doctorraudit.php?doctor_id="+trid,
			end: function() {
				dataTable.draw();
				$(document.body).css({
					"overflow-x": "auto",
					"overflow-y": "auto"
				});
			}
		});
	});
 })