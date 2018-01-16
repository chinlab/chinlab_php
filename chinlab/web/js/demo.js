//jQuery(document).ready(function($) {
//	
//		//分页
//		$.ajax({
//			type:"post",
//			url:local+"/order_GetorderlistJson.php?order_type=1",
//			async:true,
//			success:function(data){
//				console.log(data)
//				var total = data.data.pagination.totalCount			
//				//console.log(total)
//				var numPerpage = 10;
//				var totalNum = total
//				var pageNum = Math.ceil(totalNum/numPerpage);
//				var pageth = 0;
//				//console.log(pageth)
//				//console.log(pageNum)
//				createlist(1);
//				
//				
//				//创建分页按钮
//				(function(){
//					var str = "";
////					for (var i = 0; i < pageNum; i++) {
////						 str += "<li pageId='"+i+"'><a>0"+(i+1)+"</a></li>";
////					};
//					str = "<button type='button' class='btn btn-default' id='prePage'>上一页</button><button type='button' class='btn btn-default' id='nextPage'>下一页</button>"
//					$("#page").html(str)
//				
//				})();
////				//页码点击事件
////				$("#page li").each(function(){
////					$(this).click(function(){
////						
////						//alert($(this).attr("pageId"))
////						var num = $(this).attr("pageId")
////						createlist(Number(num))
////					})
////				})
////				console.log(pageNum)
////				$("#firstPage").click(function(){
////					createlist(0);
////				})
////				$("#lastPage").click(function(){
////					createlist(pageNum-1);
////				})
//				$("#prePage").click(function(){
//					if(pageth>1){
//						createlist(pageth-1);
//					}
//				})
//				$("#nextPage").click(function(){
//					if(pageth<(pageNum)){
//						createlist(pageth+1);
//					}
//				})
//				
//				//渲染
//				function createlist(n){
//					pageth=n
//					$.ajax({
//						type:"post",
//						url:local+"/order_GetorderlistJson.php?order_type=1",
//						data:{
//							"page":n
//						},
//						async:true,
//						success:function(data){
//							//console.log(data)
//							if(data.state==100){
//								$("#datatable").find('tbody').html("没有找到符合要求的信息")
//							}else{
//								
//								//console.log(data)
//								var order_type = data.data.order_type.order_type
//								//console.log(order_type)
//								var str1 = ""
//								for(i in order_type){
//									//console.log(order_type[i])
//									str1 += "<option value='"+ order_type[i].value +"'>"+ order_type[i].option +"</option>"
//								}
//								$("#order_type").html(str1)
//								
//								var pay_type = data.data.order_type.pay_type
//								//console.log(pay_type)
//								var str2 = ""
//								for(i in pay_type){
//									str2 += "<option value='"+ pay_type[i].value +"'>"+ pay_type[i].option +"</option>"
//								}
//								$("#pay_state").html(str2)
//								
//								var page = data.data.pagination.page 
//								var n = 1*(page-1)*10;
//								//console.log(page)
//								
//								var data = data.data.list
//								//console.log(data)
//								var str = "";
//								
//								for (var i = 0; i <data.length; i++) {
//									n++;
////									
//									str += "<tr><td>"+ n +"</td><td>"+ data[i].order_id +"</td><td>"+ data[i].order_type_desc +"</td><td>"+ data[i].user_name +"</td><td>"+ data[i].order_phone +"</td><td>"+ data[i].pay_money +"</td><td>"+ data[i].pay_desc +"</td><td>"+ data[i].order_state_desc +"</td><td><a class='edittrinfo' id='" + data[i].order_id + "' trid='" + data[i].order_id + "' href='javascript:void(0)'>查看/编辑</a>&nbsp;&nbsp;<a class='delrecord isShow' id='" + data[i].user_id + "del' trid='" + data[i].user_id + "' href='javascript:void(0)'>删除</a></td></tr>" ;
//								};
//								$("#datatable").find('tbody').html(str)
//								//是否有删除
//								var type = $.cookie("type")
//								//console.log(type)
//					    		if(type==1){
//					    			$(".isShow").addClass("isHide");
//					    		}
//							}
//							
//						}
//					});
//					
//				}	
//		        
//			}
//		});
//		
		
	$(function () {
			 
			 $.ajax({
			 url: local+"/order_GetorderlistJson.php?order_type=1",
			 datatype: 'json',
			 type: "Post",
			 success: function (data) {
			 if (data != null) {
			 	var total = data.data.pagination.totalCount	;
			 	var nowpage = data.data.pagination.page;
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
//									
					str += "<tr><td>"+ n +"</td><td>"+ data[i].order_id +"</td><td>"+ data[i].order_type_desc +"</td><td>"+ data[i].user_name +"</td><td>"+ data[i].order_phone +"</td><td>"+ data[i].pay_money +"</td><td>"+ data[i].pay_desc +"</td><td>"+ data[i].order_state_desc +"</td><td><a class='edittrinfo' id='" + data[i].order_id + "' trid='" + data[i].order_id + "' href='javascript:void(0)'>查看/编辑</a>&nbsp;&nbsp;<a class='delrecord isShow' id='" + data[i].user_id + "del' trid='" + data[i].user_id + "' href='javascript:void(0)'>删除</a></td></tr>" ;
				};
				$("#datatable").find('tbody').html(str)
			    		
				//console.log(total)
				var numPerpage = 10;
				var totalNum = total
				var pageNum = Math.ceil(totalNum/numPerpage);
							
			    var options = {
					  bootstrapMajorVersion: 2, //版本
					  currentPage: nowpage, //当前页数
					  totalPages: pageNum, //总页数
					  itemTexts: function (type, page, current) {
						  switch (type) {
						  case "first":
						   return "首页";
						  case "prev":
						   return "上一页";
						  case "next":
						   return "下一页";
						  case "last":
						   return "末页";
						  case "page":
						   return page;
						  }
					  },//点击事件，用于通过Ajax来刷新整个list列表
					  onPageClicked: function (event, originalEvent, type, page) {
						  $.ajax({
							  url: local+"/order_GetorderlistJson.php?order_type=1",
							  type: "Post",
							  data: {
							  	"page":page,
							  },
							success: function (data) {
						        if (data != null) {
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
										str += "<tr><td>"+ n +"</td><td>"+ data[i].order_id +"</td><td>"+ data[i].order_type_desc +"</td><td>"+ data[i].user_name +"</td><td>"+ data[i].order_phone +"</td><td>"+ data[i].pay_money +"</td><td>"+ data[i].pay_desc +"</td><td>"+ data[i].order_state_desc +"</td><td><a class='edittrinfo' id='" + data[i].order_id + "' trid='" + data[i].order_id + "' href='javascript:void(0)'>查看/编辑</a>&nbsp;&nbsp;<a class='delrecord isShow' id='" + data[i].user_id + "del' trid='" + data[i].user_id + "' href='javascript:void(0)'>删除</a></td></tr>" ;
									};
									$("#datatable").find('tbody').html(str)
						        }
							}
						  });
					  }
			    };
			    $('#page').bootstrapPaginator(options);
			  
			  
			  
			  
			  
			   }
			 
			 
			 
			}
			 
			 
			 
			 
			 
		}); 
			 
			 
			 
	})
				

			
			
			
		
		
//	});
	
	