$(document).ready(function(){
	var totalCount ;
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
	
	
	$('#order_start_time').datetimepicker({
		language: 'zh-CN',
		format: "yyyy-mm-dd",
	}).on('changeDate', function(ev) {});
	
	
	
	
	
	$('#order_end_time').datetimepicker({
		language: 'zh-CN',
		format: "yyyy-mm-dd"
	}).on('changeDate', function(ev) {});
	
	
	
	
		
	var dataTable = $('#datatable').DataTable({
			 
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": false,
            "bLengthChange": false, //去掉每页显示多少条数据方法
            "iDisplayLength": 2,
            "stateSave": false,
            "ajax": {
                url: local+"/groupclient/customeritems.php", // json datasource
                type: "post",  // method  , by default get
                error: function () {  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                },
                "data": function (d) {
                    //添加额外的参数传给服务器
                    d.items_type = $("#service_type").val();
					d.items_id = $("#service_items").val();
					d.start_time = $("#order_start_time").val();
					d.end_time = $("#order_end_time").val();
                }
            },
            "columns": [
                {
					"data": "id"
				}, 
				{
					"data": "items_type_desc"
				},
				{
					"data": "items_name"
				},
				{
					"data": "items_price"
				}, 
				{
					"data": "items_time_sum"
				},
				{
					"data": "items_price_sum"
				},
            ],
            "sPaginationType": "full_numbers",
            "oLanguage": {//对表格国际化
                "sLengthMenu": "每页显示 _MENU_条",
                "sZeroRecords": "没有找到符合条件的数据",
                //  "sProcessing": "&lt;img src=’./loading.gif’ /&gt;",
                "sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
                "sInfoEmpty": "木有记录",
                "sInfoFiltered": "(从 _MAX_ 条记录中过滤)",
                "sSearch": "搜索：",
                "oPaginate": {
                    "sFirst": '<i class="icon-step-backward"></i>',
                    "sPrevious": '<i class="icon-caret-left"></i>',
                    "sNext": '<i class="icon-caret-right"></i>',
                    "sLast": '<i class="icon-step-forward"></i>'
                }
            },
            "drawCallback": function( settings ) {
            	
            	
            	
//          	console.log(totalCount)
//              console.log($("#datatable_info").html());
				$("#datatable_info").parent().removeClass("col-sm-6").addClass("col-sm-5");
				$("#datatable_paginate").parent().removeClass("col-sm-6").addClass("col-sm-7");
                $("#datatable_info").addClass('datatable_infoleft');
                $("#datatable_paginate").addClass('datatable_inforight');
//          	var info = $("#datatable_paginate").html();
            	//var total ="";
            	var total = "<span style='float:right;height:43px;line-height:43px;margin-right:50px;'>合计金额："+totalCount+"元</span>"
            	$("#datatable_paginate").append(total);
//              console.log($("#datatable_paginate").html());
            },
            "columnDefs": [
                {
					"targets": [6],
					"data": function (data) {
						//console.log(data.create_time)
						totalCount = data.totalCount;
                        return data;
                    },
					"render": function(data, type, full) {
						//console.log(data)
						return '<a class="edittrinfo" trid="' + data.items_id + '" href="javascript:void(0)">查看详情</a>&nbsp;&nbsp;';
					}
				}
            ]
            
        });
        
         //搜索item
        $("#searchbtn").click(function () {
            dataTable.draw();
        });
		
		var d = $("#info_service").html();
		//console.log(d)
		var e = $.parseJSON(d);
		console.log(e)
		var e1 = e.serveType;
		var e2 = e.items;
		var str1 = "<option value=''>请选择</option>";
		var str2 = "<option value=''>请选择</option>";
		//console.log(data1)
		for(var i=0;i<e1.length;i++){
			str1 += "<option value='"+ e1[i].val +"'>"+ e1[i].name +"</option>"
		}
		$("#service_type").html(str1);
		
		for(var i=0;i<e2.length;i++){
			str2 += "<option value='"+ e2[i].items_id +"'>"+ e2[i].items_name +"</option>"
		}
		$("#service_items").html(str2);
		
		
		//导出
		$(document).on("click", "#derive",function() {
			var items_type = $("#service_type").val();
			var items_id = $("#service_items").val();
			var start_time = $("#order_start_time").val();
			var end_time = $("#order_end_time").val();
			window.location = "/groupclient/customeritemsexcel.php?items_type="+items_type+"&items_id="+items_id+"&start_time="+start_time+"&end_time="+end_time;
		})
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		//编辑
        var layerindex = "";
		$(document).on("click", ".edittrinfo",
			function() {
				var trid = $(this).attr("trid");
				$(document.body).css({
					"overflow-x": "hidden",
					"overflow-y": "hidden"
				});
				powidth = $(window).width() - 50;
				poheight = $(window).height() - 50;
				layerindex = layer.open({
					type: 2,
					title: "编辑商品",
					fix: false,
					shadeClose: false,
					maxmin: true,
					area: [powidth + 'px', poheight + 'px'],
					content: "/groupclient/serviceinfo.php?items_id=" + trid,
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