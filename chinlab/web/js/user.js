jQuery(document).ready(function($) {
	
		//分页
		$.ajax({
			type:"post",
			url:local+"/user_indexJson.php",
			async:true,
			success:function(data){
				//console.log(data)
				var total = data.data.pagination
				
				//console.log(data)
				var numPerpage = 10;
				var totalNum = total.totalCount;
				var pageNum = Math.ceil(totalNum/numPerpage);
				var pageth = 0;
				
				createlist(0);
				
				
//				//创建分页按钮
				(function(){
					var str = "";
//					for (var i = 0; i < pageNum; i++) {
//						 str += "<li pageId='"+i+"'><a>0"+(i+1)+"</a></li>";
//					};
					str = "<button type='button' class='btn btn-default' id='prePage'>上一页</button><button type='button' class='btn btn-default' id='nextPage'>下一页</button>"

					$("#page").html(str)
				
				})();
//				//页码点击事件
//				$("#page li").each(function(){
//					$(this).click(function(){
//						
//						//alert($(this).attr("pageId"))
//						var num = $(this).attr("pageId")
//						createlist(Number(num))
//					})
//				})
//				console.log(pageNum)
				$("#prePage").click(function(){
					if(pageth>0){
						createlist(pageth-1);
					}
				})
				$("#nextPage").click(function(){
					if(pageth<(pageNum-1)){
						createlist(pageth+1);
					}
				})
				
				
				function createlist(n){
					pageth=n
					$.ajax({
						type:"post",
						url:local+"/user_indexJson.php",
						data:{
							"page":n
						},
						async:true,
						success:function(data){
							if(data.state==100){
								$("#datatable").find('tbody').html("没有找到符合要求的信息")
							}else{
								var data = data.data.data
								var str = "";
								for (var i = 0; i <data.length; i++) {
									str += "<tr><td>"+ data[i].user_name +"</td><td>"+ data[i].user_mobile +"</td><td>"+ data[i].user_regtime +"</td><td><a href='order.html?user_id=" + data[i].user_id + "'>预约(<span style='color:red;'>"+ data[i].ordersum +"</span>)个</a>&nbsp;&nbsp;<a href='inquiry.html?user_id=" + data[i].user_id + "'>问诊(<span style='color:red;'>"+ data[i].inquirysum +"</span>)个</a>&nbsp;&nbsp;<a class='edittrinfo' id='" + data[i].user_id + "' trid='" + data[i].user_id + "' href='javascript:void(0)'>查看/编辑</a>&nbsp;&nbsp;<a class='delrecord isShow' id='" + data[i].user_id +"del' trid='" + data[i].user_id + "' href='javascript:void(0)'>删除</a></td></tr>" ;
								};
								$("#datatable").find('tbody').html(str)
								//是否有删除
								var type = $.cookie("type")
								//console.log(type)
					    		if(type==1){
					    			$(".isShow").addClass("isHide");
					    		}
							}
							
						}
					});
					
				}	
		        
			}
		});
		
		
		//搜索
		$("#searchbtn").click(function(){
			$.ajax({
				type:"post",
				url:local+"/user_indexJson.php",
				data:{
					"user_regtime" : $("#reg_establish_start").val(),
					"user_name" : $("#user_name").val(),
					"user_mobile" : $("#user_mobile").val(),
					"user_role" : $("#user_role").val()
				},
				async:true,
				success:function(data){
					//console.log(data)
					var total = data.data.pagination
					
					//console.log(data)
					var numPerpage = 10;
					var totalNum = total.totalCount;
					var pageNum = Math.ceil(totalNum/numPerpage);
					//console.log(pageNum)
					var pageth = 0;
					createlist2(0);
					
					//创建分页按钮
					(function(){
						var str = "";
//						for (var i = 0; i < pageNum; i++) {
//							 str += "<li pageId='"+i+"'><a>0"+(i+1)+"</a></li>";
//						};
					str = "<button type='button' class='btn btn-default' id='prePage2'>上一页</button><button type='button' class='btn btn-default' id='nextPage2'>下一页</button>"
					$("#page").html(str)
					
					})();
					//页码点击事件
//					$("#page li").each(function(){
//						$(this).click(function(){
//							
//							//alert($(this).attr("pageId"))
//							var num = $(this).attr("pageId")
//							createlist2(Number(num))
//						})
//					})
					//console.log(pageNum)
					$("#prePage2").click(function(){
						if(pageth>0){
							createlist2(pageth-1);
						}
					})
					$("#nextPage2").click(function(){
						if(pageth<(pageNum-1)){
							createlist2(pageth+1);
						}
					})
					
					
					function createlist2(n){
						pageth=n
						$.ajax({
							type:"post",
							url:local+"/user_indexJson.php",
							data:{
								"user_regtime" : $("#reg_establish_start").val(),
								"user_name" : $("#user_name").val(),
								"user_mobile" : $("#user_mobile").val(),
								"user_role" : $("#user_role").val(),
								"page":pageth
							},
							async:true,
							success:function(data){
								
								var data = data.data.data
								//console.log(data)
								var str = "";
								for (var i = 0; i <data.length; i++) {
									str += "<tr><td>"+ data[i].user_name +"</td><td>"+ data[i].user_mobile +"</td><td>"+ data[i].user_regtime +"</td><td><a href='order.html?user_id=" + data[i].user_id + "'>预约(<span style='color:red;'>"+ data[i].ordersum +"</span>)个</a>&nbsp;&nbsp;<a href='inquiry.html?user_id=" + data[i].user_id + "'>问诊(<span style='color:red;'>"+ data[i].inquirysum +"</span>)个</a>&nbsp;&nbsp;<a class='edittrinfo' id='" + data[i].user_id + "' trid='" + data[i].user_id + "' href='javascript:void(0)'>查看/编辑</a>&nbsp;&nbsp;<a class='delrecord' id='" + data[i].user_id +"del' trid='" + data[i].user_id + "' href='javascript:void(0)'>删除</a></td></tr>" ;
								};
								$("#datatable").find('tbody').html(str)
							}
						});
						
					}	
			        
				}
			});
		})
				
		$(document).on("click", ".delrecord", function() {
				$trid = $(this).attr("trid");
				layer.confirm('确定要删除？', {
					btn: ['确定', '取消'] //按钮
				}, function($index) {
					layer.close($index);
					var loadingindex = layer.load(1, {
						shade: [0.5, '#ddd'] //0.1透明度的白色背景
					});
					$.post(local+"/user_indexJson.php", {
						"id": $trid
					}, function(data) {
						layer.close(loadingindex);
						if(data.code == 0) {
							layer.msg('删除成功', {
								icon: 1,
								closeBtn: 1,
								shadeClose: true
							});
							table.fnReloadAjax();
						} else {
							layer.msg(data.message, {
								icon: 5,
								closeBtn: 1,
								shadeClose: true
							});
						}
					}, "json")
				});
			});

			var layerindex = "";
			var ifchange = false;
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
						title: "用户详情",
						fix: false,
						shadeClose: true,
						maxmin: true,
						area: [powidth + 'px', poheight + 'px'],
						content: "/chunfeng_userreturnuserinfo.php?id=" + trid,
						end: function() {
							reflashrecord(trid)
							$(document.body).css({
								"overflow-x": "auto",
								"overflow-y": "auto"
							});
						}
					});
				});

			function reflashrecord(id) {
				$.post(local+"/user_indexJson.php", {
						"user_id": id
					},
					function(result) {
						//console.log(result)
						var result = result.data.data[0];
						//console.log(result)
						var $tds = $("#" + id).parents("tr").find("td");
						//console.log($tds)
						$tds.eq(0).text(result.user_name);
//						$tds.eq(1).text(data.user_mobile);
//						$tds.eq(2).text(data.user_regtime);
						return;
					},
					'json');
			}
		
		
		
	});
	
	