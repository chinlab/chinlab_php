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
	
	var d = $("#info_categoryList").html();
    //console.log(d)
    var categoryList = $.parseJSON(d);
    //console.log(categoryList)
	var e = $("#info_is_sale").html();
    //console.log(d)
    var is_sale = $.parseJSON(e);
    //console.log(is_sale);
	var str1 = "<option value=''>请选择</option>";
	var str2 = "<option value=''>请选择</option>";
	for(var i=0;i<categoryList.length;i++){
		str1 += "<option value='"+ categoryList[i].val +"'>"+ categoryList[i].name +"</option>"
	}
	$("#product_classify").html(str1);
	for(var i=0;i<is_sale.length;i++){
		str2 +="<option value='"+ is_sale[i].val +"'>"+ is_sale[i].name +"</option>"
	}
	$("#product_type").html(str2);
	
		var dataTable = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": false,
            "bLengthChange": false, //去掉每页显示多少条数据方法
            "iDisplayLength": 10,
            "stateSave": false,
            "ajax": {
                url: local+"/shop/listoverseasgoods.php", // json datasource
                type: "post",  // method  , by default get
                error: function () {// error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                },
                "data": function (d) {
                    //添加额外的参数传给服务器
                    d.goods_name = $("#product_name").val();
					d.oc_id = $("#product_classify").val();
					d.is_sale = $("#product_type").val();
                }
            },
            "columns": [
            	{
					"data": "id"
				},
				{
					"data": "goods_name"
				},
				{
					"data": "oc_parent_name"
				},
                {
					"data": "sale_price"
				}, 
				{
					"data": function(data){
						var is_sale = "";
						if(data.is_sale==1){
							is_sale = "上架";
						}else{
							is_sale = "下架";
						}
						return is_sale;
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
					"targets": [5],
					"data": function (data) {
                        return data;
                    },
					"render": function(data, type, full) {
						//console.log(data)
						return '<a class="redact" trid="' + data.goods_id + '" href="javascript:void(0)">查看/编辑</a>';
					}
				}
            ]
        });
		//搜索item
        $("#searchbtn").click(function () {
            dataTable.draw();
        });
		
		//添加产品
        var layer_addProduct = "";
		$(document).on("click", "#addProduct",function() {
			var trid = $(this).attr("trid");
			$(document.body).css({
				"overflow-x": "hidden",
				"overflow-y": "hidden"
			});
			powidth = $(window).width() - 50;
			poheight = $(window).height() - 50;
			layer_addProduct = layer.open({
				type: 2,
				title: "添加产品",
				fix: false,
				shadeClose: false,
				maxmin: true,
				area: [powidth + 'px', poheight + 'px'],
				content: "/chunfeng/overseasaddproduct.php",
				end: function() {
					dataTable.draw();
					$(document.body).css({
						"overflow-x": "auto",
						"overflow-y": "auto"
					});
				}
			});
		});
		
			
		//编辑产品
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
				title: "编辑产品",
				fix: false,
				shadeClose: false,
				maxmin: true,
				area: [powidth + 'px', poheight + 'px'],
				content: "/chunfeng/overseasredactproduct.php?goods_id=" + trid,
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