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
					$('#order_start_time').datetimepicker({
						language: 'zh-CN',
						format: "yyyy-mm-dd hh:ii"
					}).on('changeDate', function(ev) {});
					
					
					$('#order_end_time').datetimepicker({
						language: 'zh-CN',
						format: "yyyy-mm-dd hh:ii"
					}).on('changeDate', function(ev) {});
					
					
					//表格
					var dataTable = $('#datatable').DataTable({
		            "processing": true,
		            "serverSide": true,
		            "searching": false,
		            "ordering": false,
		            "bLengthChange": false, //去掉每页显示多少条数据方法
		            "iDisplayLength": 10,
		            "stateSave": false,
		            "ajax": {
		                url: local+"/shop_HealthCardlist.php", // json datasource
		                type: "post",  // method  , by default get
		                error: function () {  // error handling
		                    $(".employee-grid-error").html("");
		                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
		                    $("#employee-grid_processing").css("display", "none");
		                },
		                "data": function (d) {
						                    //添加额外的参数传给服务器
						                    d.card_no = $("#card_id").val();
						                    d.card_order_id = $("#card_order_id").val();
																d.user_name = $("#serveperson").val();
																d.user_phone = $("#phone_number").val();
																d.goods_service_type = $("#serveitems").val();
							                }
							      },
							      "columns": [
							      					{
																"data": "id"
															},
															{
																"data": "card_order_id"
															},
							                {
																"data": "card_no"
															}, 
															{
																"data": "goods_service_name"
															},
															{
																"data": "user_name"
															},
															{
																"data": "user_phone"
															},
															{
																"data": "service_status"
															}, 
															{
																"data": function(data){
																	
																	return getDate(parseInt(data.create_time)*1000);
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
																"targets": [8],
																"data": function (data) {
											                        return data;
											                    },
																"render": function(data, type, full) {
																	//console.log(data)
																	return '<a class="edittrinfo" id="' + data.card_order_id + '" data-type="' + data.goods_service_type + '" href="javascript:void(0)">查看/编辑</a>&nbsp;&nbsp;';
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
										var type = $(this).attr("data-type");
										var id = $(this).attr("id");
										$(document.body).css({
											"overflow-x": "hidden",
											"overflow-y": "hidden"
										});
										powidth = $(window).width() - 50;
										poheight = $(window).height() - 50;
										layerindex = layer.open({
											type: 2,
											title: "会员卡服务编辑",
											fix: false,
											shadeClose: false,
											maxmin: true,
											area: [powidth + 'px', poheight + 'px'],
											content: "/chunfeng/healthcardinfo.php?card_order_id=" + id,
											end: function() {
												dataTable.draw();
												$(document.body).css({
													"overflow-x": "auto",
													"overflow-y": "auto"
												});
											}
										});
							});				
									
//									//卡片信息
//									var layeradd = "";
//									$(document).on("click", ".card_info",
//										function() {
//											var trid = $(this).attr("id");
//											$(document.body).css({
//												"overflow-x": "hidden",
//												"overflow-y": "hidden"
//											});
//											powidth = $(window).width() - 50;
//											poheight = $(window).height() - 50;
//											layeradd = layer.open({
//												type: 2,
//												title: "健康卡详情",
//												fix: false,
//												shadeClose: false,
//												maxmin: true,
//												area: [powidth + 'px', poheight + 'px'],
//												content: "/chunfeng/cardmassage.php?card_no=" + trid,
//												end: function() {
//													$(document.body).css({
//														"overflow-x": "auto",
//														"overflow-y": "auto"
//													});
//												}
//											});
//										});
	
	
		})