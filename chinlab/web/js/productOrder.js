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
			
			//时间控件
			$('#order_start_time').datetimepicker({
				language: 'zh-CN',
				format: "yyyy-mm-dd hh:ii"
			}).on('changeDate', function(ev) {});
			
			
			$('#order_end_time').datetimepicker({
				language: 'zh-CN',
				format: "yyyy-mm-dd hh:ii"
			}).on('changeDate', function(ev) {});
			
			
			var dataTable = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": false,
            "bLengthChange": false, //去掉每页显示多少条数据方法
            "iDisplayLength": 10,
            "stateSave": false,
            "ajax": {
                url: local+"/shop_orderaccompanylist.php", // json datasource
                type: "post",  // method  , by default get
                error: function () {  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                },
                "data": function (d) {
				                    //添加额外的参数传给服务器
				                    d.order_id = $("#order_id").val();
														d.user_name = $("#consignee").val();
														d.user_phone = $("#contact_way").val();
														d.is_invoice = $("#is_invoice").val();
														d.start_time = $("#order_start_time").val();
														d.end_time = $("#order_end_time").val();
					                }
					      },
					      "columns": [
					                {
														"data": "order_id"
													}, 
													{
														"data": "goods_name"
													},
													{
														"data": "user_name"
													},
													{
														"data": "user_phone"
													}, 
													{
														"data": "is_invoice_desc"
													},
													{
														"data": function(data){
																return getDate(data.create_time)
														}
													},
													{
														"data": "now_price"
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
														"render": function(data, type, full) {
															//console.log(data)
															return '<a class="edittrinfo" id="' + data.order_id + '" trid="' + data.order_id + '" href="javascript:void(0)">查看/编辑</a>&nbsp;&nbsp;';
														}
													}
					            ]
			});
					        
	        //搜索item
	        $("#searchbtn").click(function () {
	            dataTable.draw();
	        });
			
					//编辑
	        var layerindex = "";
					$(document).on("click", ".edittrinfo",
						function() {
							var trid = $(this).attr("trid");
							console.log(trid)
							$(document.body).css({
								"overflow-x": "hidden",
								"overflow-y": "hidden"
							});
							powidth = $(window).width() - 50;
							poheight = $(window).height() - 50;
							layerindex = layer.open({
								type: 2,
								title: "商品订单详情",
								fix: false,
								shadeClose: false,
								maxmin: true,
								area: [powidth + 'px', poheight + 'px'],
								content: "/chunfeng/productorderinfo.php?order_id=" + trid,
								end: function() {
									$(document.body).css({
										"overflow-x": "auto",
										"overflow-y": "auto"
									});
								}
							});
						});
			
			
		})