//时间格式
    function getDate(tm){ 
		var tt=new Date(parseInt(tm) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ") 
		return tt; 
	} 


$(document).ready(function(){
 	
 	//表格提示卡
	var tdtipindex1 = "";
	$(document).on("mouseover", "#datatable1 td,#datatable1 th", function() {
		var tdcontent = $(this).text();
		tdtipindex1 = layer.tips(tdcontent, $(this), {
			tips: [1, '#333333'],
			time: 0
		});
	}).on("mouseout", "#datatable1 td,#datatable1 th", function() {
		layer.close(tdtipindex1);
	});
	var tdtipindex2 = "";
	$(document).on("mouseover", "#datatable2 td,#datatable2 th", function() {
		var tdcontent = $(this).text();
		tdtipindex2 = layer.tips(tdcontent, $(this), {
			tips: [1, '#333333'],
			time: 0
		});
	}).on("mouseout", "#datatable2 td,#datatable2 th", function() {
		layer.close(tdtipindex2);
	});
 	
 	//时间控件
	$('#start_time').datetimepicker({
		language: 'zh-CN',
		format: "yyyy-mm-dd hh:ii"
	}).on('changeDate', function(ev) {});
	
	
	$('#end_time').datetimepicker({
		language: 'zh-CN',
		format: "yyyy-mm-dd hh:ii"
	}).on('changeDate', function(ev) {});
 	
 	//tab切换
	var status = $("#myTab").find(".active").attr("data-status");
 	$('#myTab li').click(function (e) {
	    e.preventDefault();
	    $(this).find("a").tab('show');
	    status = $(this).attr("data-status");
	    //console.log(status)
	    if(status==1){
	    	dataTable1.draw();
	    }else if(status==2){
	    	dataTable2.draw();
	    }
	})
 	
 	
 	
 	
 	//加载表格数据
   	var dataTable1 = $('#datatable1').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": false,
            "bLengthChange": false, //去掉每页显示多少条数据方法
            "iDisplayLength": 20,
            "stateSave": false,
            "ajax": {
                url:"/cms_publishadlist.php", // json datasource
                type: "post",  // method  , by default get
                error: function () {  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                },
                "data": function (d) {
                    //添加额外的参数传给服务器
                    d.title = $("#ad_titel").val();
                    d.user_name = $("#editor").val();
                    d.start_time = $("#start_time").val();
                    d.end_time = $("#end_time").val();
                }
            },
            "columns": [
//          	{
//          		"data":function(data){
//          			return "<input type='checkbox' name='' id='' value='' />"
//          		}
//          	},
                {"data": "material_id"},
                {"data": "title"},
                {"data": "user_name"},
                {
                	"data": function (data) {
//                      var start_time = new Date(parseInt(data.start_time) * 1000);
						return getDate(data.start_time);
                    }
                },
                {
                	"data": function (data) {
//                      var end_time = new Date(parseInt(data.end_time) * 1000);
						return getDate(data.end_time);
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
                    "render": function (data, type, full) {
                    	return '<a class="preview" data-id="' + data.material_id + '" data-url="'+ data.news_url +'" href="javascript:void(0)">预览</a>';              
                    }
                }
            ]
        });
        
        
        var dataTable2 = $('#datatable2').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": false,
            "bLengthChange": false, //去掉每页显示多少条数据方法
            "iDisplayLength": 20,
            "stateSave": false,
            "ajax": {
                url:"/cms_nopulishlist.php", // json datasource
                type: "post",  // method  , by default get
                error: function () {  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                },
                "data": function (d) {
                    //添加额外的参数传给服务器
                    d.channel_no = 3;
                    d.title = $("#ad_titel").val();
                    d.user_name = $("#editor").val();
                    d.start_time = $("#start_time").val();
                    d.end_time = $("#end_time").val();
                }
            },
            "columns": [
            	{"data": "material_id"},
                {"data": "title"},
                {"data": "user_name"},
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
                    "targets": [3],
                    "data": function (data) {
                        return data;
                    },
                    "render": function (data, type, full) {
                        var test_object = $("#info_role");
                        if (typeof(test_object) == 'undefined') {
                            return "";
                        }
                        var info_role = parseInt(test_object.html());
                        if (info_role == 0) {
                            return "";
                        }
                        var test_text = '<a class="preview" data-id="' + data.material_id + '" data-url="'+ data.news_url +'" href="javascript:void(0)">预览</a>';
                        if (info_role != 2) {
                            test_text = '<a class="release" data-id="' + data.material_id + '" data-type="'+ data.show_type +'" href="javascript:void(0)">发布</a>&nbsp;&nbsp;' + test_text;
                        }
                        return test_text;
                    }
                }
            ]
        });
 	
 		//搜索
	 	$("#searchbtn").on('click',function(){
	 		//console.log(status)
	 		if(status==1){
		    	dataTable1.draw();
		    }else if(status==2){
		    	dataTable2.draw();
		    }
	 	})
	 	
	 	//发布
	 	var layerrelease = "";
	   	$(document).on("click", ".release",function() {
	   		var trid = $(this).attr("data-id");
			//console.log(news_url)
			$(document.body).css({
				"overflow-x": "hidden",
				"overflow-y": "hidden"
			});
			powidth = $(window).width() - 50;
			poheight = $(window).height() - 50;
			layerrelease = layer.open({
				type: 2,
				title: "预览",
				area: [powidth + 'px', poheight + 'px'],
				fix: false,
				shadeClose: false,
				maxmin: true,
				content: "/cms/releaseadstart.php?material_id=" + trid,
				end: function() {
					if(status==1){
				    	dataTable1.draw();
				    }else if(status==2){
				    	dataTable2.draw();
				    }
					$(document.body).css({
						"overflow-x": "auto",
						"overflow-y": "auto"
					});
				}
			});
		});
	 	
	 	
	 	//预览
	 	var layerpreview = "";
	   	$(document).on("click", ".preview",function() {
			var news_url = 'http://qr.liantu.com/api.php?text='+encodeURIComponent($(this).attr("data-url"));
		
			window.open(news_url,'newwindow', 'toolbar=no,scrollbars=yes,resizable=no,top=300,left=450,width=400,height=400');
			//console.log(news_url)
//			$(document.body).css({
//				"overflow-x": "hidden",
//				"overflow-y": "hidden"
//			});
//			powidth = $(window).width() - 50;
//			poheight = $(window).height() - 50;
//			layerpreview = layer.open({
//				type: 2,
//				title: "预览",
//				area: ['375px', '667px'],
//				fix: false,
//				shadeClose: true,
//				maxmin: true,
//				content: news_url,
//				end: function() {
//					$(document.body).css({
//						"overflow-x": "auto",
//						"overflow-y": "auto"
//					});
//				}
//			});
		});
	 	
 	
 	
 })