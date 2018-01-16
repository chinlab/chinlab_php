
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
	
	
	//加载表格数据
   	var dataTable = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ordering": false,
            "bLengthChange": false, //去掉每页显示多少条数据方法
            "iDisplayLength": 10,
            "stateSave": false,
            "ajax": {
                url:"/cms_userlist.php", // json datasource
                type: "post",  // method  , by default get
                error: function () {  // error handling
                    $(".employee-grid-error").html("");
                    $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employee-grid_processing").css("display", "none");
                },
                "data": function (d) {
                    //添加额外的参数传给服务器
                    d.username = $("#username").val();
                    d.nickname = $("#nickname").val();
                    d.userphone = $("#userphone").val();
                    d.status = $("#status").val();
                }
            },
            "columns": [
                {"data": "id"},
                {"data": "username"},
                {"data": "nickname"},
                {"data": "userphone"},
                {
                	"data": function(data){
	                			if(data.roleId==1){
	                				return "admin";
	                			}else if(data.roleId==2){
	                				return "审核人员";
	                			}else if(data.roleId==3){
	                				return "编辑人员";
	                			}else{
	                				return "暂无角色";
	                			}
                		 }
                },
                {
                    "data": function (data) {
                        if (data.status == 0) {
                            return '关闭';
                        }else if(data.status == 10){
                        		return '开启';
                        }                       
                    }
                },
                {
                	"data": function (data) {
//                      var create_time = new Date(parseInt(data.created_at) * 1000);
												return getDate(data.created_at);
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
                    	return '<a class="redact" data-id="' + data.id + '" href="javascript:void(0)">编辑/查看</a>&nbsp;&nbsp;<a class="delete" data-id="' + data.id + '" href="javascript:void(0)">删除</a>';
                        
                    }
                }
            ]
        });
 		
 	
 	
 	//搜索
 	$("#searchbtn").on('click',function(){
 		dataTable.draw();
 	})
	
	
	
	
	//创建用户
 	var layeradd = "";
 	$(document).on("click", "#adduser",function() {
//			var trid = $(this).attr("trid");
		$(document.body).css({
			"overflow-x": "hidden",
			"overflow-y": "hidden"
		});
		powidth = $(window).width() - 50;
		poheight = $(window).height() - 50;
		layeradd = layer.open({
			type: 2,
			title: "创建用户",
			fix: false,
			shadeClose: false,
			maxmin: true,
			area: [powidth + 'px', poheight + 'px'],
			content: "/cms/useradd.php",
			end: function() {
				dataTable.draw();
				$(document.body).css({
					"overflow-x": "auto",
					"overflow-y": "auto"
				});
			}
		});
	});
	
	
	
	//编辑用户
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
			title: "编辑用户",
			fix: false,
			shadeClose: false,
			maxmin: true,
			area: [powidth + 'px', poheight + 'px'],
			content: "/cms/userredact.php?id=" + trid,
			end: function() {
				dataTable.draw();
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
			url:"/cms_userdelete.php",
			data:{
				"id":did
			},
			async:true,
			success:function(data){
				//console.log(data)
				if(data.state==0){
					layer.msg(data.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
					dataTable.draw();
				}else{
					layer.msg(data.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
				}
			}
		});
	});
	
	
	
	
 })