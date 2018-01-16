jQuery(document).ready(function($){
	//分页
		$.ajax({
			type:"post",
			url:local+"/order_indexJson.php",
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
						type:"post",
						url:local+"/order_indexJson.php",
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
									if(data[i].order_price==""){
										order_price = "0"
									}else{
										order_price = data[i].order_price
									}
									str += "<tr><td>"+ data[i].order_number +"</td><td>"+ data[i].order_time +"</td><td>"+ data[i].type_name +"</td><td>"+ data[i].advise_price +"</td><td>"+ data[i].order_price +"</td><td>"+ data[i].order_name +"</td><td>"+ data[i].order_phone +"</td><td>"+ data[i].state_name +"</td><td><a class='edittrinfo' id='" + data[i].order_id + "' trid='" + data[i].order_id + "' href='javascript:void(0)'>查看/编辑</a>&nbsp;&nbsp;<a class='delrecord isShow' id='" + data[i].user_id + "del' trid='" + data[i].user_id + "' href='javascript:void(0)'>删除</a></td></tr>" ;
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
})