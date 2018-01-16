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
	
	var dataTable = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": false,
            "bLengthChange": false, //去掉每页显示多少条数据方法
            "iDisplayLength": 20,
            "stateSave": false,
            "ajax": {
                url: local+"/shop/orderactivelist.php", // json datasource
                type: "post",  // method  , by default get
                error: function () {  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                },
                "data": function (d) {
                    //添加额外的参数传给服务器
                    d.card_no = $("#membershipCard_number").val();
					d.goods_name = $("#goods_name").val();
					d.active_user_name = $("#customer_name").val();
					d.phone_no = $("#phone_number").val();
                }
            },
            "columns": [
                {
					"data": "id"
				}, 
				{
					"data": "card_no"
				},
				{
					"data": "goods_name"
				},
				{
					"data": "active_user_name"
				}, 
				{
					"data": "phone_no"
				},
				{
					"data": function(data){
						return getDate(parseInt(data.active_time)*1000);
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
					"targets": [6],
					"data": function (data) {
                        return data;
                    },
					"render": function(data, type, full) {
						//console.log(data)
						return '<a class="edittrinfo" trid="' + data.card_no + '" href="javascript:void(0)">查看/编辑</a>&nbsp;&nbsp;';
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
		$(document).on("click", ".edittrinfo",function() {
			var trid = $(this).attr("trid");
			$(document.body).css({
				"overflow-x": "hidden",
				"overflow-y": "hidden"
			});
			powidth = $(window).width() - 50;
			poheight = $(window).height() - 50;
			layerindex = layer.open({
				type: 2,
				title: "商品详情",
				fix: false,
				shadeClose: false,
				maxmin: true,
				area: [powidth + 'px', poheight + 'px'],
				content: "/chunfeng/projectactivateinfo.php?card_no=" + trid,
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