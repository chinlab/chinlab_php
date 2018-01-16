function is_supply(data){
	return data+"<img src='img/bu.png'/>"
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
	
	var d = $("#info_state").html();
    //console.log(d)
    var e = $.parseJSON(d);
    //console.log(e)
	var orderState = e.orderState;
	var payState = e.payState;
	var str1 = "";
	var str2 = "";
	for(var i=0;i<orderState.length;i++){
		str1 += "<option value='"+ orderState[i].val +"'>"+ orderState[i].name +"</option>"
	}
	$("#order_type").html(str1);
	for(var i=0;i<payState.length;i++){
		str2 +="<option value='"+ payState[i].val +"'>"+ payState[i].name +"</option>"
	}
	$("#serve_state").html(str2);
	var dataTable = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": false,
            "bLengthChange": false, //去掉每页显示多少条数据方法
            "iDisplayLength": 10,
            "stateSave": false,
            "ajax": {
                url: local+"/groupcustomer/getorderlist.php", // json datasource
                type: "post",  // method  , by default get
                error: function () {  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                },
                "data": function (d) {
                    //添加额外的参数传给服务器
                    d.order_type = 17;
                    d.order_id = $("#order_number").val();
					d.order_name = $("#order_name").val();
					d.order_phone = $("#order_phone").val();
					d.order_state = $("#order_type").val();
					d.pay_state = $("#serve_state").val();
					d.start_time = $("#order_start_time").val();
					d.end_time = $("#order_end_time").val();
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
					"data": "items_name"
				},
				{
					"data": "order_name"
				},
				{
					"data": "order_phone"
				}, 
				{
					"data": "process_money0"
				},
				{
					"data": function(data){
						if(data.is_supply==1){
							return is_supply(data.pay_desc);
						}else{
							return data.pay_desc;
						}
					}
				},
				{
					"data": "order_state_desc"
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
						return '<a class="edittrinfo" trid="' + data.order_id + '" href="javascript:void(0)">查看/解读</a>&nbsp;&nbsp;';
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
					title: "解读报告",
					fix: false,
					shadeClose: false,
					maxmin: true,
					area: [powidth + 'px', poheight + 'px'],
					content: "/chunfeng/readreportc.php?order_id=" + trid,
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