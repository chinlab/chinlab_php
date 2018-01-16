  
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
	
	//时间控件
	$('#redact_start_time').datetimepicker({
		language: 'zh-CN',
		format: "yyyy-mm-dd hh:ii"
	}).on('changeDate', function(ev) {});
	
	
	$('#redact_end_time').datetimepicker({
		language: 'zh-CN',
		format: "yyyy-mm-dd hh:ii"
	}).on('changeDate', function(ev) {});
	
	//获取频道下拉菜单内容
    var d = $("#info_channel").html();
    //console.log(d)
    var e = $.parseJSON(d);
    //console.log(e)
    var length = e.length ;
    var str1 = "<option value=''>请选择</option>";
    for(var i=0;i<length;i++){
    	str1 += "<option value='"+ e[i].channel_no +"'>"+ e[i].channel_name +"</option>";
    }
	  $("#channel").html(str1);
	
	
	//tab切换
	var status = $("#myTab").find(".active").attr("data-status");
	//console.log(status)
 	$('#myTab li').click(function (e) {
	    $(this).tab('show');
	    status = $(this).attr("data-status");
	    //console.log(status)
	    dataTable1.draw();
	  
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
                url:"/cms_materiallist.php", // json datasource
                type: "post",  // method  , by default get
                error: function () {  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                },
                "data": function (d) {
                    //添加额外的参数传给服务器
                    d.news_type = 1;
                    d.status = status;
                    d.title = $("#articleTitle").val();
                    d.channel_no = $("#channel").val();
                    d.user_name = $("#user_name").val();
                    d.update_min_time = $("#redact_start_time").val();
                    d.update_max_time  = $("#redact_end_time").val();
                    
                }
            },
            "columns": [
                {"data": "material_id"},
                {"data": "title"},
                {"data": "channel_name"},
                {"data": "user_name"},
                {
                	"data": function (data) {
//                      var now_time = new Date(parseInt(data.create_time) * 1000);
												return getDate(data.create_time);
                    }
                },
                {
                    "data": function (data) {
                        if (data.status == 0) {
                            return '待提交';
                        }else if(data.status == 1){
                        	return '待审核';
                        }else if(data.status == 2){
                        	return '审核通过';
                        }else if(data.status == 3){
                        	return '审核不通过';
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
                    "targets": [6],
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

						if (info_role == 1) {

							if (data.status == 0) {
								return '<a class="redact" data-id="' + data.material_id + '" href="javascript:void(0)">编辑</a>&nbsp;&nbsp;<a class="preview" data-id="' + data.material_id + '" data-url="' + data.news_url + '" href="javascript:void(0)">预览</a>&nbsp;&nbsp;<a class="submit_check" data-id="' + data.material_id + '" href="javascript:void(0)">提交审核</a>&nbsp;&nbsp;<a class="delete" data-id="' + data.material_id + '" href="javascript:void(0)">删除</a>';
							} else if (data.status == 1) {
								return '<a class="redact" data-id="' + data.material_id + '" href="javascript:void(0)">编辑</a>&nbsp;&nbsp;<a class="preview" data-id="' + data.material_id + '" data-url="' + data.news_url + '" href="javascript:void(0)">预览</a>&nbsp;&nbsp;<a class="check" data-id="' + data.material_id + '" href="javascript:void(0)">审核</a>&nbsp;&nbsp;<a class="delete" data-id="' + data.material_id + '" href="javascript:void(0)">删除</a>';
							} else if (data.status == 2) {
								return '<a class="preview" data-id="' + data.material_id + '" data-url="' + data.news_url + '" href="javascript:void(0)">预览</a>&nbsp;&nbsp;<a class="check" data-id="' + data.material_id + '"  href="javascript:void(0)">审核</a>&nbsp;&nbsp;<a class="delete" data-id="' + data.material_id + '" href="javascript:void(0)">删除</a>';
							} else if (data.status == 3) {
								return '<a class="redact" data-id="' + data.material_id + '" href="javascript:void(0)">编辑</a>&nbsp;&nbsp;<a class="preview" data-id="' + data.material_id + '" data-url="' + data.news_url + '" href="javascript:void(0)">预览</a>&nbsp;&nbsp;<a class="submit_check" data-id="' + data.material_id + '" href="javascript:void(0)">提交审核</a>&nbsp;&nbsp;<a class="check" data-id="' + data.material_id + '" href="javascript:void(0)">审核</a>&nbsp;&nbsp;<a class="delete" data-id="' + data.material_id + '" href="javascript:void(0)">删除</a>';
							}
						} else if (info_role == 2) {
							if (data.status == 0) {
								return '<a class="preview" data-id="' + data.material_id + '" data-url="' + data.news_url + '" href="javascript:void(0)">预览</a>';
							} else if (data.status == 1) {
								return '<a class="preview" data-id="' + data.material_id + '" data-url="' + data.news_url + '" href="javascript:void(0)">预览</a>&nbsp;&nbsp;<a class="check" data-id="' + data.material_id + '" href="javascript:void(0)">审核</a>';
							} else if (data.status == 2) {
								return '<a class="preview" data-id="' + data.material_id + '" data-url="' + data.news_url + '" href="javascript:void(0)">预览</a>&nbsp;&nbsp;<a class="check" data-id="' + data.material_id + '"  href="javascript:void(0)">审核</a>';
							} else if (data.status == 3) {
								return '<a class="preview" data-id="' + data.material_id + '" data-url="' + data.news_url + '" href="javascript:void(0)">预览</a>';
							}
						} else if (info_role == 3) {
							if (data.status == 0) {
								return '<a class="redact" data-id="' + data.material_id + '" href="javascript:void(0)">编辑</a>&nbsp;&nbsp;<a class="preview" data-id="' + data.material_id + '" data-url="' + data.news_url + '" href="javascript:void(0)">预览</a>&nbsp;&nbsp;<a class="submit_check" data-id="' + data.material_id + '" href="javascript:void(0)">提交审核</a>&nbsp;&nbsp;<a class="delete" data-id="' + data.material_id + '" href="javascript:void(0)">删除</a>';
							} else if (data.status == 1) {
								return '<a class="redact" data-id="' + data.material_id + '" href="javascript:void(0)">编辑</a>&nbsp;&nbsp;<a class="preview" data-id="' + data.material_id + '" data-url="' + data.news_url + '" href="javascript:void(0)">预览</a>&nbsp;&nbsp;<a class="delete" data-id="' + data.material_id + '" href="javascript:void(0)">删除</a>';
							} else if (data.status == 2) {
								return '<a class="preview" data-id="' + data.material_id + '" data-url="' + data.news_url + '" href="javascript:void(0)">预览</a>&nbsp;&nbsp;<a class="delete" data-id="' + data.material_id + '" href="javascript:void(0)">删除</a>';
							} else if (data.status == 3) {
								return '<a class="redact" data-id="' + data.material_id + '" href="javascript:void(0)">编辑</a>&nbsp;&nbsp;<a class="preview" data-id="' + data.material_id + '" data-url="' + data.news_url + '" href="javascript:void(0)">预览</a>&nbsp;&nbsp;<a class="submit_check" data-id="' + data.material_id + '" href="javascript:void(0)">提交审核</a>&nbsp;&nbsp;<a class="delete" data-id="' + data.material_id + '" href="javascript:void(0)">删除</a>';
							}
						}
                        
                    }
                }
            ]
        });
 		
 	
 	
 	//搜索
 	$("#searchbtn").on('click',function(){
 		dataTable1.draw();
 	})
 	
 	
 	
 	
 	//添加资讯	
 	var layerindex = "";
 	$(document).on("click", "#addnews",function() {
//			var trid = $(this).attr("trid");
		$(document.body).css({
			"overflow-x": "hidden",
			"overflow-y": "hidden"
		});
		powidth = $(window).width() - 50;
		poheight = $(window).height() - 50;
		layerindex = layer.open({
			type: 2,
			title: "添加资讯",
			fix: false,
			shadeClose: false,
			maxmin: true,
			area: [powidth + 'px', poheight + 'px'],
			content: "/cms/informationadd.php",
			end: function() {
				dataTable1.draw();
				$(document.body).css({
					"overflow-x": "auto",
					"overflow-y": "auto"
				});
			}
		});
	});
	//编辑
	var layerredact = "";
 	$(document).on("click", ".redact",function() {
		var trid = $(this).attr("data-id");
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
			content: "/cms/informationredact.php?material_id=" + trid,
			end: function() {
				dataTable1.draw();
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

//		console.log(news_url)
//		$(document.body).css({
//			"overflow-x": "hidden",
//			"overflow-y": "hidden"
//		});
//		powidth = $(window).width() - 50;
//		poheight = $(window).height() - 50;
//		layerpreview = layer.open({
//			type: 2,
//			title: "预览",
//			area: ['375px', '667px'],
//			fix: false,
//			shadeClose: true,
//			maxmin: true,
//			content: news_url,
//			end: function() {
//				$(document.body).css({
//					"overflow-x": "auto",
//					"overflow-y": "auto"
//				});
//			}
//		});
	
	});
	
	//提交审核
	$(document).on("click", ".submit_check",function() {
		var mid = $(this).attr("data-id");
		console.log(mid)
		$.ajax({
			type:"post",
			url:"/cms_commitmaterial.php",
			data:{
				"material_id":mid
			},
			async:true,
			success:function(data){
				console.log(data)
				if(data.state==0){
					layer.msg(data.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
					dataTable1.draw();
				}else{
					layer.msg(data.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
				}
			}
		});
	});
	
	//审核
	var layerredact = "";
 	$(document).on("click", ".check",function() {
		var trid = $(this).attr("data-id");
		$(document.body).css({
			"overflow-x": "hidden",
			"overflow-y": "hidden"
		});
		powidth = $(window).width() - 50;
		poheight = $(window).height() - 50;
		layerredact = layer.open({
			type: 2,
			title: "审核",
			fix: false,
			shadeClose: false,
			maxmin: true,
			area: [powidth + 'px', poheight + 'px'],
			content: "/cms/informationcheck.php?material_id=" + trid,
			end: function() {
				dataTable1.draw();
				$(document.body).css({
					"overflow-x": "auto",
					"overflow-y": "auto"
				});
			}
		});
	});
	
	//删除
 	$(document).on("click", ".delete",function() {
		var did = $(this).attr("data-id");
		//console.log(did)
		$.ajax({
			type:"post",
			url:"/cms_deletematerial.php",
			data:{
				"material_id":did
			},
			async:true,
			success:function(data){
				//console.log(data)
				if(data.state==0){
					layer.msg(data.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
					dataTable1.draw();
				}else{
					layer.msg(data.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
				}
			}
		});
	});
	
	
	
 })