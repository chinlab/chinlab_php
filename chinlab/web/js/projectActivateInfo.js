//时间格式
function getDate(input){
	var oDate = new Date(input);
	function p(s) {
        return s < 10 ? '0' + s: s;
    }
	return oDate.getFullYear()+'-'+(oDate.getMonth()+1)+'-'+oDate.getDate()+' '+oDate.getHours()+':'+p(oDate.getMinutes())+':'+p(oDate.getSeconds());
} 


$(document).ready(function(){
	var href = window.location.search;    	
    var id = href.replace("?","").split("=")[1];
	//获取页面信息
	var d = $("#product_info").html();
    //console.log(d)
    var e = $.parseJSON(d);
    console.log(e)
    $("#membershipCard_number").val(e.card_no);
    $("#goods_name").val(e.goods_name);
    $("#customer_name").val(e.apply_user_name);
    $("#Idnumber").val(e.user_card_no);
    $("#phone_number").val(e.phone_no);
    $("#activate_time").val(getDate(parseInt(e.active_time)*1000));
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
        "iDisplayLength": 20,
        "stateSave": false,
        "ajax": {
            url: local+"/shop/orderinfo.php", // json datasource
            type: "post",  // method  , by default get
            error: function () {  // error handling
                $(".employee-grid-error").html("");
                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employee-grid_processing").css("display", "none");
            },
            "data": function (d) {
                //添加额外的参数传给服务器
                d.card_no = id;
            }
        },
        "columns": [
            {
				"data": "id"
			}, 
			{
				"data": "name"
			},
			{
				"data": "count"
			},
			{
				"data": "has"
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
					if(data.is_add==1){
						return '<button type="button" class="edittrinfo btn btn-primary" service-id="' + data.service_type + '" href="javascript:void(0)">创建订单</button>&nbsp;&nbsp;';
					}else{
						return '';
					}
				}
			}
        ]
    });
	//创建订单
    var layerindex = "";
	$(document).on("click", ".edittrinfo",function() {
		var trid = $(this).attr("service-id");
		$(document.body).css({
			"overflow-x": "hidden",
			"overflow-y": "hidden"
		});
		powidth = $(window).width() - 50;
		poheight = $(window).height() - 50;
		layerindex = layer.open({
			type: 2,
			title: "创建服务",
			fix: false,
			shadeClose: false,
			maxmin: true,
			area: [powidth + 'px', poheight + 'px'],
			content: "/chunfeng/createservice.php?card_no=" + id +"&&service_type=" +trid,
			end: function() {
				dataTable.draw();
				$(document.body).css({
					"overflow-x": "auto",
					"overflow-y": "auto"
				});
			}
		});
	});
	
	
	
	
	
    //保存
    $(document).on("click", "#save",function(){
	    	
	    	var order_design = $("#order_design").val();
	    	var logistics = $("#logistics").val();
	    	var waybill = $("#waybill").val();
    	
	    	$.ajax({
	    		type:"post",
	    		url:local+"/shop_OrderAccompanyUpdate.php",
	    		data:{
	    			"order_id" : id,
	    			"order_detail_note":order_design,
	    			"express_no":waybill,
	    			"id":logistics,
	    		},
	    		async:true,
	    		success:function(data){
	    			console.log(data)
	    			if(data.state==0){
    					layer.msg(data.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000},function(){
    						var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
				        	parent.layer.close(index);
    					});
    				}else{
    					layer.msg(data.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
    				}
	    		}
	    	});	    	
	    })
     
    
})