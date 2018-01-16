//时间格式
function getDate(tm){ 
	var tt=new Date(parseInt(tm) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ") 
	return tt; 
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
	
//	var d = $("#info_state").html();
//  //console.log(d)
//  var e = $.parseJSON(d);
//  //console.log(e)
//	var orderState = e.orderState;
//	var payState = e.payState;
//	var str1 = "";
//	var str2 = "";
//	for(var i=0;i<orderState.length;i++){
//		str1 += "<option value='"+ orderState[i].val +"'>"+ orderState[i].name +"</option>"
//	}
//	$("#order_type").html(str1);
//	for(var i=0;i<payState.length;i++){
//		str2 +="<option value='"+ payState[i].val +"'>"+ payState[i].name +"</option>"
//	}
//	$("#serve_state").html(str2);
	
	var dataTable = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": false,
            "bLengthChange": false, //去掉每页显示多少条数据方法
            "iDisplayLength": 20,
            "stateSave": false,
            "ajax": {
                url: local+"/shop/listclass.php", // json datasource
                type: "post",  // method  , by default get
                error: function () {  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                },
                "data": function (d) {
                    //添加额外的参数传给服务器
                    d.oc_parent_id = 0;
                }
            },
            "columns": [
            	{
					"data": "id"
				},
                {
					"data": "oc_name"
				}, 
				{
					"data": "oc_user_name"
				},			
				{
					"data": function(data){
						return getDate(data.create_time);
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
					"targets": [4],
					"data": function (data) {
                        return data;
                    },
					"render": function(data, type, full) {
						//console.log(data)
						return '<a class="addsubclass" trid="' + data.oc_id + '" href="javascript:void(0)">添加子类</a>&nbsp;&nbsp;<a class="chacksubclass" trid="' + data.oc_id + '" href="javascript:void(0)">查看子类</a><a class="redact" trid="' + data.oc_id + '" href="javascript:void(0)">编辑类目</a>';
					}
				}
            ]
       });
		
		
		//添加类目
        var layer_addsubclass = "";
		$(document).on("click", "#addCategory",function() {
			var trid = $(this).attr("trid");
			$(document.body).css({
				"overflow-x": "hidden",
				"overflow-y": "hidden"
			});
			powidth = $(window).width() - 50;
			poheight = $(window).height() - 50;
			layer_addsubclass = layer.open({
				type: 2,
				title: "添加类目",
				fix: false,
				shadeClose: false,
				maxmin: true,
				area: [powidth + 'px', poheight + 'px'],
				content: "/chunfeng/addcategory.php",
				end: function() {
					dataTable.draw();
					$(document.body).css({
						"overflow-x": "auto",
						"overflow-y": "auto"
					});
				}
			});
		});
		
		
		
		//添加子类
        var layer_addsubclass = "";
		$(document).on("click", ".addsubclass",function() {
			var trid = $(this).attr("trid");
			$(document.body).css({
				"overflow-x": "hidden",
				"overflow-y": "hidden"
			});
			powidth = $(window).width() - 50;
			poheight = $(window).height() - 50;
			layer_addsubclass = layer.open({
				type: 2,
				title: "添加子类",
				fix: false,
				shadeClose: false,
				maxmin: true,
				area: [powidth + 'px', poheight + 'px'],
				content: "/chunfeng/addsubclass.php?oc_id=" + trid,
				end: function() {
					dataTable.draw();
					$(document.body).css({
						"overflow-x": "auto",
						"overflow-y": "auto"
					});
				}
			});
		});
		
		//查看子类
        var layer_chacksubclass = "";
		$(document).on("click", ".chacksubclass",function() {
				var trid = $(this).attr("trid");
				$(document.body).css({
					"overflow-x": "hidden",
					"overflow-y": "hidden"
				});
				powidth = $(window).width() - 50;
				poheight = $(window).height() - 50;
				layer_chacksubclass = layer.open({
					type: 2,
					title: "项目子类",
					fix: false,
					shadeClose: false,
					maxmin: true,
					area: [powidth + 'px', poheight + 'px'],
					content: "/chunfeng/categorysubclass.php?oc_id=" + trid,
					end: function() {
						dataTable.draw();
						$(document.body).css({
							"overflow-x": "auto",
							"overflow-y": "auto"
						});
					}
				});
			});
			
			//编辑类目
	        var layer_redact = "";
			$(document).on("click", ".redact",function() {
				var trid = $(this).attr("trid");
				$(document.body).css({
					"overflow-x": "hidden",
					"overflow-y": "hidden"
				});
				powidth = $(window).width() - 50;
				poheight = $(window).height() - 50;
				layer_redact = layer.open({
					type: 2,
					title: "编辑类目",
					fix: false,
					shadeClose: false,
					maxmin: true,
					area: [powidth + 'px', poheight + 'px'],
					content: "/chunfeng/redactcategory.php?oc_id=" + trid,
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