jQuery(document).ready(function($) {
	
		//分页
		$.ajax({
			type:"post",
			url:local+"/order_GetorderlistJson.php?order_type=1",
			async:true,
			success:function(data){
				//console.log(data)
				var total = data.data.pagination.totalCount			
				//console.log(total)
				var numPerpage = 10;
				var totalNum = total
				var pageNum = Math.ceil(totalNum/numPerpage);
				var pageth = 0;
				//console.log(pageth)
				//console.log(pageNum)
				createlist(1);
				
				
				//创建分页按钮
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
//				$("#firstPage").click(function(){
//					createlist(0);
//				})
//				$("#lastPage").click(function(){
//					createlist(pageNum-1);
//				})
				$("#prePage").click(function(){
					if(pageth>1){
						createlist(pageth-1);
					}
				})
				$("#nextPage").click(function(){
					if(pageth<(pageNum)){
						createlist(pageth+1);
					}
				})
				
				//渲染
				function createlist(n){
					pageth=n
					$.ajax({
						type:"post",
						url:local+"/order_GetorderlistJson.php?order_type=1",
						data:{
							"page":n
						},
						async:true,
						success:function(data){
							//console.log(data)
							if(data.state==100){
								$("#datatable").find('tbody').html("没有找到符合要求的信息")
							}else{
								
								//console.log(data)
								var order_type = data.data.order_type.order_type
								//console.log(order_type)
								var str1 = ""
								for(i in order_type){
									//console.log(order_type[i])
									str1 += "<option value='"+ order_type[i].value +"'>"+ order_type[i].option +"</option>"
								}
								$("#order_type").html(str1)
								
								var pay_type = data.data.order_type.pay_type
								//console.log(pay_type)
								var str2 = ""
								for(i in pay_type){
									str2 += "<option value='"+ pay_type[i].value +"'>"+ pay_type[i].option +"</option>"
								}
								$("#pay_state").html(str2)
								
								var page = data.data.pagination.page 
								var n = 1*(page-1)*10;
								//console.log(page)
								
								var data = data.data.list
								//console.log(data)
								var str = "";
								
								for (var i = 0; i <data.length; i++) {
									n++;
//									var type = "";
//									var state ="";
//									var order_price = "";
//									if(data[i].order_type == 1) {
//										type = "手术预约"
//									} else if(data[i].order_type == 2) {
//										type = "精准预约"
//									}else if(data[i].order_type == 3) {
//										type = "海外医疗"
//									}else if(data[i].order_type == 4) {
//										type = "绿色通道"
//									}else if(data[i].order_type == 5) {
//										type = "手术预约"
//									}else if(data[i].order_type == 6) {
//										type = "健康体检"
//									}else if(data[i].order_type == 7) {
//										type = "生育辅助"
//									}else if(data[i].order_type == 8) {
//										type = "膝关节手术"
//									}else if(data[i].order_type == 9) {
//										type = "医疗抗衰"
//									}else if(data[i].order_type == 10) {
//										type = "第二诊疗意见"
//									}else if(data[i].order_type == 11) {
//										type = "重症转诊"
//									}else if(data[i].order_type == 12) {
//										type = "vip服务"
//									}else {
//										type = "慈善公益"
//									}
//									if(data[i].order_type == 12){
//										if(data[i].order_state == 1){
//											state = "vip服务未支付"
//										}else if(data[i].order_state == 2){
//											state = "vip服务已支付"
//										}
//									}else{
//										if(data[i].order_state == 1) {
//												state = "咨询服务费未支付"
//											} else if(data[i].order_state == 2) {
//												state = "咨询服务费已支付"
//											} else if(data[i].order_state == 3) {
//												state = "待安排"
//											} else if(data[i].order_state == 4) {
//												state = "手术费未支付"
//											} else if(data[i].order_state == 5) {
//												state = "手术费已支付"
//											} else if(data[i].order_state == 6) {
//												state = "已完成"
//											} else if(data[i].order_state == 7) {
//												state = "资讯服务取消"
//											} else {
//												state = "手术服务取消"
//											}
//									}
//									if(data[i].order_price==""){
//										order_price = "0"
//									}else{
//										order_price = data[i].order_price
//									}
									str += "<tr><td>"+ n +"</td><td>"+ data[i].order_id +"</td><td>"+ data[i].order_type_desc +"</td><td>"+ data[i].user_name +"</td><td>"+ data[i].order_phone +"</td><td>"+ data[i].pay_money +"</td><td>"+ data[i].pay_desc +"</td><td>"+ data[i].order_state_desc +"</td><td><a class='edittrinfo' id='" + data[i].order_id + "' trid='" + data[i].order_id + "' href='javascript:void(0)'>查看/编辑</a>&nbsp;&nbsp;<a class='delrecord isShow' id='" + data[i].user_id + "del' trid='" + data[i].user_id + "' href='javascript:void(0)'>删除</a></td></tr>" ;
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
			//console.log($("#order_type").val())
			//console.log($("#pay_state").val())
			
			$.ajax({
				type:"post",
				url:local+"/order_GetorderlistJson.php?order_type=1",
				data:{
					"order_id" : $("#order_number").val(),
					"order_name": $("#order_name").val(),
					"order_phone" : $("#order_phone").val(),
					"order_state" : $("#order_type").val(),
					"pay_state" : $("#pay_state").val(),
					"create_time_start" : $("#order_start_time").val(),
					"create_end_start" : $("#order_end_time").val()
				},
				async:true,
				success:function(data){
					if(data.state==100){
						$("#datatable").find('tbody').html("没有找到符合要求的信息")
					}else{
						//console.log(data)
						var total = data.data.pagination.totalCount				
						var numPerpage = 10;
						var totalNum = total;
						var pageNum = Math.ceil(totalNum/numPerpage);
						var pageth = 0;
				
						createlist2(1);
	
						//创建分页按钮
						(function(){
							var str = "";
//							for (var i = 0; i < pageNum; i++) {
//								 str += "<li pageId='"+i+"'><a>0"+(i+1)+"</a></li>";
//							};
							str = "<button type='button' class='btn btn-default' id='prePage2'>上一页</button><button type='button' class='btn btn-default' id='nextPage2'>下一页</button>"
							$("#page").html(str)
						
						})();
						//页码点击事件
//						$("#page li").each(function(){
//							$(this).click(function(){
//								
//								//alert($(this).attr("pageId"))
//								var num = $(this).attr("pageId")
//								createlist2(Number(num))
//							})
//						})
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
								url:local+"/order_GetorderlistJson.php?order_type=1",
								data:{
									"order_id" : $("#order_number").val(),
									"order_name": $("#order_name").val(),
									"order_phone" : $("#order_phone").val(),
									"order_state" : $("#order_type").val(),
									"pay_state" : $("#pay_state").val(),
									"create_time_start" : $("#order_start_time").val(),
									"create_end_start" : $("#order_end_time").val(),
									"page":pageth,
								},
								async:true,
								success:function(data){
									//console.log(data)
									if(data.state==100){
										$("#datatable").find('tbody').html("没有找到符合要求的信息")
									}else{
										
										var order_type = data.data.order_type.order_type
										//console.log(order_type)
//										var str1 = ""
//										for(i in order_type){
//											//console.log(order_type[i])
//											str1 += "<option value='"+ order_type[i].value +"'>"+ order_type[i].option +"</option>"
//										}
//										$("#order_type").html(str1)
//										
//										var pay_type = data.data.order_type.pay_type
//										//console.log(pay_type)
//										var str2 = ""
//										for(i in pay_type){
//											str2 += "<option value='"+ pay_type[i].value +"'>"+ pay_type[i].option +"</option>"
//										}
//										$("#pay_state").html(str2)
										
										
										
										var page = data.data.pagination.page 
										var n = 1*(page-1)*10;
										var data = data.data.list
										//console.log(data)
										var str = "";
										for (var i = 0; i <data.length; i++) {
											n++;
//											var type = "";
//											var state ="";
//											if(data[i].order_type == 1) {
//												type = "手术预约"
//											} else if(data[i].order_type == 2) {
//												type = "精准预约"
//											}else if(data[i].order_type == 3) {
//												type = "海外医疗"
//											}else if(data[i].order_type == 4) {
//												type = "绿色通道"
//											}else if(data[i].order_type == 5) {
//												type = "手术预约"
//											}else if(data[i].order_type == 6) {
//												type = "健康体检"
//											}else if(data[i].order_type == 7) {
//												type = "生育辅助"
//											}else if(data[i].order_type == 8) {
//												type = "膝关节手术"
//											}else if(data[i].order_type == 9) {
//												type = "医疗抗衰"
//											}else if(data[i].order_type == 10) {
//												type = "第二诊疗意见"
//											}else if(data[i].order_type == 11) {
//												type = "重症转诊"
//											}else if(data[i].order_type == 12) {
//												type = "vip服务"
//											}else {
//												type = "慈善公益"
//											}
//											if(data[i].order_type == 12){
//												if(data[i].order_state == 1){
//													state = "vip服务未支付"
//												}else if(data[i].order_state == 2){
//													state = "vip服务已支付"
//												}
//											}else{
//												if(data[i].order_state == 1) {
//														state = "咨询服务费未支付"
//													} else if(data[i].order_state == 2) {
//														state = "咨询服务费已支付"
//													} else if(data[i].order_state == 3) {
//														state = "待安排"
//													} else if(data[i].order_state == 4) {
//														state = "手术费未支付"
//													} else if(data[i].order_state == 5) {
//														state = "手术费已支付"
//													} else if(data[i].order_state == 6) {
//														state = "已完成"
//													} else if(data[i].order_state == 7) {
//														state = "资讯服务取消"
//													} else {
//														state = "手术服务取消"
//													}
//											}
//											if(data[i].order_price==""){
//												order_price = "0"
//											}else{
//												order_price = data[i].order_price
//											}
											str += "<tr><td>"+ n +"</td><td>"+ data[i].order_id +"</td><td>"+ data[i].order_type_desc +"</td><td>"+ data[i].user_name +"</td><td>"+ data[i].order_phone +"</td><td>"+ data[i].pay_money +"</td><td>"+ data[i].pay_desc +"</td><td>"+ data[i].order_state_desc +"</td><td><a class='edittrinfo' id='" + data[i].order_id + "' trid='" + data[i].order_id + "' href='javascript:void(0)'>查看/编辑</a>&nbsp;&nbsp;<a class='delrecord isShow' id='" + data[i].user_id + "del' trid='" + data[i].user_id + "' href='javascript:void(0)'>删除</a></td></tr>" ;
									};
									$("#datatable").find('tbody').html(str)
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
					
				}
			});
		})
				
//		$.ajax({
//			type:"post",
//			url:local+"/order_OrderTypeAllJson.php",
//			async:true,
//			success:function(data){
//				var str = "";
//				var str2 = "";
//				var data_1 = data.data.pid
//				var data_2 = data.data
//				for(var i=0;i<data_1.length;i++){
//					str+= "<option value='"+ data_1[i].value +"'>"+ data_1[i].option +"</option>"
//				}
//				$("#order_type").html(str)
//				$("#order_type").change(function(){
//					//console.log(data_2)
//					for(i in data_2){
//						if($("#order_type").val()==i){
//							for(var j=0;j<data_2[i].length;j++){
//								str2 += "<option value='"+ data_2[i][j].value +"'>"+ data_2[i][j].option +"</option>"
//							}
//							$("#order_state").html(str2)
//						}
//						str2 = "";
//					}
//				})				
////				console.log($("#order_state").text())
//				
//			}
//		});
		
		
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
						title: "用户预约详情",
						fix: false,
						shadeClose: true,
						maxmin: true,
						area: [powidth + 'px', poheight + 'px'],
						content: "surgery_returnuserinfo.html?order_id=" + trid,
						cancel: function(){ 
//		                   alert(1)
				            $.ajax({
				            	type:"post",
				            	url:local+"/order_UpdateorderstatusJson.php",
				            	data:{
									"order_id" : trid,
									"order_state" : -1,
								},
				            	async:true,
					          	success:function(data){
					          		//console.log(data)
					          	}
				            });
			            
		                 },
						end: function() {
							reflashrecord(trid);
							$(document.body).css({
								"overflow-x": "auto",
								"overflow-y": "auto"
							});
						}
					});
				});
				
				function reflashrecord(id) {
					$.post(local+"/order_viewJson.php", {
							"order_id": id
						},
						function(result) {
							//console.log(result)
							var result = result.data
							//console.log(result)
							var $tds = $("#" + id).parents("tr").find("td");
							$tds.eq(7).text(result.order_state_desc);
//							$tds.eq(1).text(data.order_time);
//							$tds.eq(2).text(data.ordertype);
//							$tds.eq(3).text(data.order_name);
//							$tds.eq(4).text(result.order_price);
//							$tds.eq(5).text(data.order_state);
							return;
						},
						'json');
				}
				
				
				
				//重置
				$("#reset").click(function(){
					$("#order_number").val("");
					$("#order_name").val("");
					$("#order_phone").val("");
					$("#order_type").val("");
					$("#pay_state").val("");
					$("#order_start_time").val("");
					$("#order_end_time").val("");
				})
				
				
//			$(document).on("click", ".delrecord", function() {
//				$trid = $(this).attr("trid");
//				layer.confirm('确定要删除？', {
//					btn: ['确定', '取消'] //按钮
//				}, function($index) {
//					layer.close($index);
//					var loadingindex = layer.load(1, {
//						shade: [0.5, '#ddd'] //0.1透明度的白色背景
//					});
//					$.post(local+"/order_indexJson.php", {
//						"id": $trid
//					}, function(data) {
//						layer.close(loadingindex);
//						if(data.code == 0) {
//							layer.msg('删除成功', {
//								icon: 1,
//								closeBtn: 1,
//								shadeClose: true
//							});
//							table.fnReloadAjax();
//						} else {
//							layer.msg(data.message, {
//								icon: 5,
//								closeBtn: 1,
//								shadeClose: true
//							});
//						}
//					}, "json")
//				});
//			});
			
			
			
			
		
		
	});
	
	