

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
	
	var dataTable = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": false,
            "bLengthChange": false, //去掉每页显示多少条数据方法
            "iDisplayLength": 10,
            "stateSave": false,
            "ajax": {
                url: local+"/shop_goodslist.php", // json datasource
                type: "post",  // method  , by default get
                error: function () {  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                },
                "data": function (d) {
                    //添加额外的参数传给服务器
                    d.goods_id = $("#product_id").val();
					d.goods_name = $("#product_name").val();
					d.goods_type = $("#product_type").val();
					d.is_onsalt = $("#product_state").val();
                }
            },
            "columns": [
                {
					"data": "goods_id"
				}, 
				{
					"data": "goods_name"
				},
				{
					"data": "goods_type_desc"
				},
				{
					"data": "now_price"
				}, 
				{
					"data": "original_price"
				},
				{
					"data": "goods_amount"
				},
				{
					"data": "is_onsalt_desc"
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
						return '<a class="edittrinfo" id="' + data.goods_id + '" trid="' + data.goods_id + '" href="javascript:void(0)">查看/编辑</a>&nbsp;&nbsp;';
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
					content: "/chunfeng/productinfo.php?goods_id=" + trid,
					end: function() {
						reflashrecord(trid);
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
			
			
			
			
			
	
		//添加
        var layeradd = "";
		$(document).on("click", "#addpro",
			function() {
				var trid = $(this).attr("trid");
				$(document.body).css({
					"overflow-x": "hidden",
					"overflow-y": "hidden"
				});
				powidth = $(window).width() - 50;
				poheight = $(window).height() - 50;
				layeradd = layer.open({
					type: 2,
					title: "添加商品",
					fix: false,
					shadeClose: false,
					maxmin: true,
					area: [powidth + 'px', poheight + 'px'],
					content: "/chunfeng/productadd.php",
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