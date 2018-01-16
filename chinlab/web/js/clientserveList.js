
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
	var href = window.location.search;    	
	var id = href.replace("?","").split("=")[1];
	
	
	
	
	var dataTable = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": false,
            "bLengthChange": false, //去掉每页显示多少条数据方法
            "iDisplayLength": 10,
            "stateSave": false,
            "ajax": {
                url: local+"/groupcustomer/customeritems.php", // json datasource
                type: "post",  // method  , by default get
                error: function () {  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                },
                "data": function (d) {
                    //添加额外的参数传给服务器
                    d.customer_id = id;
                }
            },
            "columns": [
            			{
            				"data" : "id"
            			},
	                {
										"data": "customer_name"
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
					"targets": [6],
					"data": function (data) {
                        return data;
                    },
					"render": function(data, type, full) {
						//console.log(data)
						return '<a class="edittrinfo" data-id="' + data.items_id + '" trid="' + data.customer_id + '" href="javascript:void(0)">查看/编辑</a>&nbsp;&nbsp;';
					}
				}
            ]
        });
        
		
		
		
		//编辑
        var layerindex = "";
		$(document).on("click", ".edittrinfo",
			function() {
				var trid = $(this).attr("data-id");
				$(document.body).css({
					"overflow-x": "hidden",
					"overflow-y": "hidden"
				});
				powidth = $(window).width() - 50;
				poheight = $(window).height() - 50;
				layerindex = layer.open({
					type: 2,
					title: "编辑",
					fix: false,
					shadeClose: false,
					maxmin: true,
					area: [powidth + 'px', poheight + 'px'],
					content: "/chunfeng/serviceprojectinfo.php?items_id=" + trid,
					end: function() {
						dataTable.draw();
//						reflashrecord(trid);
						$(document.body).css({
							"overflow-x": "auto",
							"overflow-y": "auto"
						});
					}
				});
			});
			
			function reflashrecord(id) {
				$.post(local+"/shop_goodslist.php", {
						"goods_id": id
					},
					function(result) {
						console.log(result)
						var result = result.data[0];
						//console.log(result)
						var $tds = $("#" + id).parents("tr").find("td");
						$tds.eq(1).text(result.goods_name);
						$tds.eq(2).text(result.goods_type_desc);
						$tds.eq(3).text(result.now_price);
						$tds.eq(4).text(result.original_price);
						$tds.eq(5).text(result.goods_amount);
						$tds.eq(6).text(result.is_onsalt_desc);
						return;
					},
					'json');
			} 
			
			
			
			
})