jQuery(document).ready(function($){
	function time(nS){
                	return new Date(parseInt(nS) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
                }
	
	//分页
		$.ajax({
			type:"get",
			url:local+"/admin_IndexJson.php",
			async:true,
			success:function(data){
				
				//console.log(data)
				var total = data.data.pagination			
				//console.log(data)
				var numPerpage = 10;
				var totalNum = total.totalCount;
				var pageNum = Math.ceil(totalNum/numPerpage);
				var pageth = 0;
				//console.log(pageth)
				//console.log(pageNum)
				createlist(0);
				
				
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
					if(pageth>0){
						createlist(pageth-1);
					}
				})
				$("#nextPage").click(function(){
					if(pageth<(pageNum-1)){
						createlist(pageth+1);
					}
				})
				
				//渲染
				function createlist(n){
					pageth=n
					$.ajax({
						type:"get",
						url:local+"/admin_IndexJson.php",
						data:{
							"page":n
						},
						async:true,
						success:function(data){
							if(data.state==100){
								$("#datatable").find('tbody').html("没有找到符合要求的信息")
							}else{
								//console.log(data)
								var data = data.data.data
								var str = "";
								var status = ""
								//console.log(data)
								for (var i = 0; i <data.length; i++) {
									if(data[i].status == 10) {
										status = "已启用"
									}else{
										status = "已禁用"
									}
									str += "<tr><td>"+ time(data[i].created_at)+"</td><td>"+ data[i].username +"</td><td>"+ data[i].nickname +"</td><td>"+ data[i].userphone +"</td><td>"+ status +"</td><td>"+ time(data[i].updated_at) +"</td><td><a class='edittrinfo' id='" + data[i].id + "' trid='" + data[i].id + "' href='javascript:void(0)'>查看/编辑</a></td></tr>" ;
								};
								$("#datatable").find('tbody').html(str)
							}
							
						}
					});
					
				}	
		        
			}
		});
		
		
		
		//搜索
		$("#searchbtn").click(function(){
			//console.log($("#search_username").val())
			$.ajax({
				type:"post",
				url:local+"/admin_IndexJson.php",
				data:{
					"username" : $("#search_username").val(),
					"nickname": $("#search_name").val(),
					"userphone" : $("#search_phone").val(),
					"status" : $("#search_status").val(),
				},
				async:true,
				success:function(data){
				
					if(data.state==100){
						$("#datatable").find('tbody').html("没有找到符合要求的信息")
					}else{
						//console.log(data)
						var total = data.data.pagination				
						var numPerpage = 10;
						var totalNum = total.totalCount;
						var pageNum = Math.ceil(totalNum/numPerpage);
						var pageth = 0;
				
						createlist2(0);
	
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
							console.log($("#search_status").val())
							pageth=n
							$.ajax({
								type:"post",
								url:local+"/admin_IndexJson.php",
								data:{
									"username" : $("#search_username").val(),
									"nickname": $("#search_name").val(),
									"userphone" : $("#search_phone").val(),
									"status" : $("#search_status").val(),
									"page":pageth
								},
								async:true,
								success:function(data){
									if(data.state==100){
										$("#datatable").find('tbody').html("没有找到符合要求的信息")
									}else{
										var data = data.data.data
										//console.log(data)
										var str = "";
										var status = "";
										for (var i = 0; i <data.length; i++) {
											//console.log(data[i].status)
											if(data[i].status == 10) {
												status = "已启用"
											}else{
												status = "已禁用"
											}
											str += "<tr><td>"+ time(data[i].created_at)+"</td><td>"+ data[i].username +"</td><td>"+ data[i].nickname +"</td><td>"+ data[i].userphone +"</td><td>"+ status +"</td><td>"+ time(data[i].updated_at) +"</td><td><a class='edittrinfo' id='" + data[i].id + "' trid='" + data[i].id + "' href='javascript:void(0)'>查看/编辑</a></td></tr>" ;
										};
									$("#datatable").find('tbody').html(str)
									}
										
								}
							});
							
						}	
				        
					}
					
				}
			});
		})
		
		
		
		var layerindex = "";
	    var ifchange = false;
	    $(document).on("click", ".edittrinfo", function () {
	        var trid = $(this).attr("trid");
	        $(document.body).css({
	            "overflow-x": "hidden",
	            "overflow-y": "hidden"
	        });
	        powidth = $(window).width() - 50;
	        poheight = $(window).height() - 50;
	        layerindex = layer.open({
	            type: 2,
	            title: "管理员详情",
	            fix: false,
	            shadeClose: true,
	            maxmin: true,
	            area: [powidth + 'px', poheight + 'px'],
	            content: "sysuser_returnuserinfo.html?id=" + trid,
	            end: function () {
	                reflashrecord(trid);
	                $(document.body).css({
	                    "overflow-x": "auto",
	                    "overflow-y": "auto"
	                });
	            }
	        });
	    });
	
	    function reflashrecord(id) {
	        $.post(local+"/admin_ViewJson.php", {"id": id}
	        , function (result) {
	        	//console.log(result)
	        	var statu = ""
	            var result = result.data.role[0];
	           // console.log(result)
	            var $tds = $("#" + id).parents("tr").find("td");
	            $tds.eq(1).text(result.username);
	            $tds.eq(2).text(result.nickname);
	            $tds.eq(3).text(result.userphone);
	            if(result.status=="10"){
	            	statu = "已启用"
	            }else{
	            	statu = "已禁用"
	            }
	            $tds.eq(4).text(statu);
	            return;
	        }, 'json');
	    }
			
		
})