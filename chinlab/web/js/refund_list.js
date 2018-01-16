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
	
	//tab切换
	var status = $("#myTab").find(".active").attr("data-status");
	//console.log(status)
	$(document).on("click", "#myTab li",function() {
	    $(this).tab('show');
	    status = $(this).attr("data-status");
	    //console.log(status)
	    dataTable.draw();
	})
	var dataTable = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": false,
            "bLengthChange": false, //去掉每页显示多少条数据方法
            "iDisplayLength": 20,
            "stateSave": false,
            "ajax": {
                url: "/shop/refundlist.php", // json datasource
                type: "post",  // method  , by default get
                error: function () {  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                },
                "data": function (d) {
                    //添加额外的参数传给服务器
                    d.order_id = $("#order_number").val();
					d.backend_refund_status = status;
                }
            },
            "columns": [
                {
					"data": "id"
				}, 
				{
					"data": "order_id"
				},
				{
					"data": "goods_name"
				},
				{
					"data": "order_name"
				},
				{
					"data": "goods_number"
				}, 
				{
					"data": "pay_money"
				},
				{
					"data": function(data){
						var pay_way= '';
						if(data.pay_type==1){
							pay_way = '支付宝';
						}else if(data.pay_type==2){
							pay_way='微信';
						}else{
							pay_way='银联';
						}
						return pay_way;
					}
				},
            ],
            "oLanguage": {//对表格国际化
                "sLengthMenu": "每页显示 _MENU_条",
                "sZeroRecords": "没有找到符合条件的数据",
                //  "sProcessing": "&lt;img src=’./loading.gif’ /&gt;",
                "sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
                "sInfoEmpty": "没有记录",
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
						if(data.backend_refund_status==0){
							return '<a class="applyRefund" trid="' + data.order_id + '" href="javascript:void(0)">申请退款</a>&nbsp;&nbsp;';
						}else if(data.backend_refund_status==1){
							var auditing = $("#auth-auditing").text()
							if(auditing=='true'){
								return '<a class="audit" trid="' + data.order_id + '" href="javascript:void(0)">审核</a>&nbsp;&nbsp;';
							}else{
								return '无权操作';
							}
						}else if(data.backend_refund_status==2){
							return '<a class="editor" trid="' + data.order_id + '" href="javascript:void(0)">编辑</a>&nbsp;&nbsp;';
						}else if(data.backend_refund_status==3){
							return '<a class="check" trid="' + data.order_id + '" href="javascript:void(0)">查看</a>&nbsp;&nbsp;';
						}
					}
				}
            ]
        });
        
         //搜索item
        $("#searchbtn").click(function () {
            dataTable.draw();
        });
		
		
		
		//申请退款
        var layer_applyRefund = "";
		$(document).on("click", ".applyRefund",function() {
			var trid = $(this).attr("trid");
			$(document.body).css({
				"overflow-x": "hidden",
				"overflow-y": "hidden"
			});
			powidth = $(window).width() - 50;
			poheight = $(window).height() - 50;
			layer_applyRefund = layer.open({
				type: 2,
				title: "编辑商品",
				fix: false,
				shadeClose: false,
				maxmin: true,
				area: [powidth + 'px', poheight + 'px'],
				content: "/chunfeng/applyrefund.php?order_id=" + trid,
				end: function() {
					dataTable.draw();
					$(document.body).css({
						"overflow-x": "auto",
						"overflow-y": "auto"
					});
				}
			});
		});
			
	
		//审核
        var layer_audit = "";
		$(document).on("click", ".audit",function() {
			var trid = $(this).attr("trid");
			$(document.body).css({
				"overflow-x": "hidden",
				"overflow-y": "hidden"
			});
			powidth = $(window).width() - 50;
			poheight = $(window).height() - 50;
			layer_audit = layer.open({
				type: 2,
				title: "添加商品",
				fix: false,
				shadeClose: false,
				maxmin: true,
				area: [powidth + 'px', poheight + 'px'],
				content:"/chunfeng/pendrefund.php?order_id=" + trid,
				end: function() {
					dataTable.draw();
					$(document.body).css({
						"overflow-x": "auto",
						"overflow-y": "auto"
					});
				}
			});
		});
		//编辑
        var layer_editor = "";
		$(document).on("click", ".editor",function() {
			var trid = $(this).attr("trid");
			$(document.body).css({
				"overflow-x": "hidden",
				"overflow-y": "hidden"
			});
			powidth = $(window).width() - 50;
			poheight = $(window).height() - 50;
			layer_editor = layer.open({
				type: 2,
				title: "添加商品",
				fix: false,
				shadeClose: false,
				maxmin: true,
				area: [powidth + 'px', poheight + 'px'],
				content: "/chunfeng/refundfailed.php?order_id=" + trid,
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
        var layer_check = "";
		$(document).on("click", ".check",function() {
			var trid = $(this).attr("trid");
			$(document.body).css({
				"overflow-x": "hidden",
				"overflow-y": "hidden"
			});
			powidth = $(window).width() - 50;
			poheight = $(window).height() - 50;
			layer_check = layer.open({
				type: 2,
				title: "添加商品",
				fix: false,
				shadeClose: false,
				maxmin: true,
				area: [powidth + 'px', poheight + 'px'],
				content: "/chunfeng/refundpass.php?order_id=" + trid,
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