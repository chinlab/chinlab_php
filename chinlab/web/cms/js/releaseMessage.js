	//时间格式
    function getDate(tm){ 
		var tt=new Date(parseInt(tm) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ") 
		return tt; 
	} 


$(document).ready(function(){
	
	//下拉菜单
	var d = $("#info_channel").html();
    //console.log(d)
    var e = $.parseJSON(d);
    //console.log(e)
    var length = e.length ;
    var str1 = "<option value=''>请选择</option>";
    var str2 = "<option value=''>请选择</option>";
    for(var i=0;i<length;i++){
    	str1 += "<option value='"+ e[i].channel_no +"'>"+ e[i].channel_name +"</option>";
    }
	$("#channel").html(str1);
	
	var dt = decodeURIComponent($("#info_type").html());
    //console.log(dt)
    var ev = $.parseJSON(dt);
	//console.log(ev)
	$.each(ev, function (k, v) {
		str2 += "<option value='"+ k +"'>"+ v +"</option>";
	})
	$("#message_type").html(str2);
	
	
	
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
	$('#redact_start_time').datetimepicker({
		language: 'zh-CN',
		format: "yyyy-mm-dd hh:ii"
	}).on('changeDate', function(ev) {});
	
	
	$('#redact_end_time').datetimepicker({
		language: 'zh-CN',
		format: "yyyy-mm-dd hh:ii"
	}).on('changeDate', function(ev) {});
	
	
	
	
	//tab切换
	var status = $("#myTab").find(".active").attr("data-status");
	if(status==1){
    	$("#batch_sub").prop("disabled","disabled");
    }else if(status==2){
    	$("#batch_sub").removeAttr("disabled");
    }
 	$('#myTab li').click(function (e) {
	    e.preventDefault();
	    $(this).find("a").tab('show');
	    status = $(this).attr("data-status");
	    //console.log(status)
	    if(status==1){
	    	dataTable1.draw();
	    	$("#batch_sub").attr("disabled","disabled");
	    }else if(status==2){
	    	dataTable2.draw();
	    	$("#batch_sub").removeAttr("disabled");
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
                url:"/cms_publishlist.php", // json datasource
                type: "post",  // method  , by default get
                error: function () {  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                },
                "data": function (d) {
                    //添加额外的参数传给服务器
                    d.title = $("#articleTitle").val();
                    d.channel_no = $("#channel").val();
                    d.show_type = $("#message_type").val();
                    d.user_name = $("#editor").val();
//                  d.publish_time_start = $("#redact_start_time").val();
//                  d.publish_end_time  = $("#redact_end_time").val();  
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
                {"data": "channel_name"},
                {"data": "user_name"},
                {
                    "data": function (data) {
                        if (data.show_type == 0) {
                            return 'banner资讯';
                        }else{
                        	return '列表资讯';
                        }
                    }
                },
                {
                	"data": function (data) {
//                      var now_time = new Date(parseInt(data.create_time) * 1000);
						return getDate(data.publish_time);
                    }
                },
                {
                	"data": function (data) {
                		if(data.is_push==0){
                			return "";
                		}else{
                			return getDate(data.push_time);
                		}
						
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
                    "targets": [7],
                    "data": function (data) {
                        return data;
                    },
                    "render": function (data, type, full) {
                    	return '<a class="preview" data-id="' + data.material_id + '" data-url="'+ data.news_url +'" href="javascript:void(0)">预览</a>';
//                  	if(data.status==0){
//                  		return '<a class="release" data-id="' + data.material_id + '" href="javascript:void(0)">发布</a>&nbsp;&nbsp;<a class="preview" data-id="' + data.material_id + '" data-url="'+ data.news_url +'" href="javascript:void(0)">预览</a>';
//                  	}else if(data.status==1){
//                  		
//                  	}
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
                    d.status = 3;
                    d.title = $("#articleTitle").val();
                    d.channel_no = $("#channel").val();
                    d.show_type = $("#message_type").val();
                    d.user_name = $("#editor").val();
//                  d.publish_time_start = $("#redact_start_time").val();
//                  d.publish_end_time  = $("#redact_end_time").val();  
                }
            },
            "columns": [
            	{
            		"data":function(data){
            			if(data.show_type==0){
            				return "";
            			}else{
            				return "<input type='checkbox' name='id' data-id="+ data.material_id +" value='' />";
            			}
            			
            		}
            	},
                {"data": "material_id"},
                {"data": "title"},
                {"data": "channel_name"},
                {"data": "user_name"},
                {
                    "data": function (data) {
                        if (data.show_type == 0) {
                            return 'banner资讯';
                        }else{
                        	return '列表资讯';
                        }
                    }
                },
                {
                	"data": function (data) {
//                      var now_time = new Date(parseInt(data.create_time) * 1000);
						return getDate(data.update_time);
                    }
                },
//              {
//              	"data": function (data) {
//                      var push_time = new Date(parseInt(data.push_time) * 1000);
//						return formatDate(push_time);
//                  }
//              },
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
 	
 	//全部勾选
   	$(document).on("click", "#allcheck",function() {
   		
   		if(this.checked){
   			$("input[name=id]").prop('checked',true);  
   		}else{
   			$("input[name=id]").prop('checked',false);  
   		}
   	})
 	
 	//批量发布
 	$(document).on("click", "#batch_sub",function() {
   		var length = $("#datatable2").find("tbody").find("input[type='checkbox']:checked").length
   		var obj = $("#datatable2").find("tbody").find("input[type='checkbox']:checked")
   		console.log(obj)
   		var arr = [];
   		if(length>10){
   			layer.msg("选中的文章超过10条", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
   		}else if(length<=0){
   			layer.msg("没有选中文章", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
   		}else{
   			for(var i=0;i<obj.length;i++){
   				//console.log(obj[i].dataset.id)
   				arr.push(obj[i].dataset.id)
   			}
   			//console.log(arr)
   			var arrjson = JSON.stringify(arr)
   			//console.log(arrjson)
   			//编辑
			var layerredact = "";
			$(document.body).css({
				"overflow-x": "hidden",
				"overflow-y": "hidden"
			});
			powidth = $(window).width() - 50;
			poheight = $(window).height() - 50;
			layerredact = layer.open({
				type: 2,
				title: "编辑资讯",
				width:"500",
				fix: false,
				shadeClose: false,
				maxmin: true,
				area: [powidth + 'px', poheight + 'px'],
				content: "/cms/releasemmess.php?ids=" + encodeURIComponent(arrjson),
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
   		}
   	})
 	
 	
 	//发布
	var layerrelease = "";
 	$(document).on("click", ".release",function() {
 		var trid = $(this).attr("data-id");
		var type = $(this).attr("data-type");
		var release_url="";
		if(type==0){
			release_url="/cms/releasembanner.php?material_id=" + trid;
		}else if(type==2){
			var arr_id = [];
			arr_id.push(trid);
			var arr_idjson = JSON.stringify(arr_id);
			release_url="/cms/releasemmess.php?ids=" + encodeURIComponent(arr_idjson);
		}
		$(document.body).css({
			"overflow-x": "hidden",
			"overflow-y": "hidden"
		});
		powidth = $(window).width() - 50;
		poheight = $(window).height() - 50;
		layerredact = layer.open({
			type: 2,
			title: "发布资讯",
			width:"500",
			fix: false,
			shadeClose: false,
			maxmin: true,
			area: [powidth + 'px', poheight + 'px'],
			content: release_url,
			end: function() {
				console.log(status)
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
		/*
		$(document.body).css({
			"overflow-x": "hidden",
			"overflow-y": "hidden"
		});
		powidth = $(window).width() - 50;
		poheight = $(window).height() - 50;
		layerpreview = layer.open({
			type: 2,
			title: "预览",
			area: ['375px', '667px'],
			fix: false,
			shadeClose: true,
			maxmin: true,
			content: news_url,
			end: function() {
				$(document.body).css({
					"overflow-x": "auto",
					"overflow-y": "auto"
				});
			}
		});
		*/
	});
 	
 })