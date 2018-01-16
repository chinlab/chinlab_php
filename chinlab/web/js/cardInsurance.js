//时间格式
  function getDate(tm){ 
		var tt=new Date(parseInt(tm) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ") 
		return tt; 
	} 



	$(document).ready(function(){
		var href = window.location.search;    	
		var id = href.replace("?","").split("=")[1];
		
		var d = $("#info_cart").html();
		//console.log(d)
		var e = $.parseJSON(d);
		console.log(e)
		var OrderService = e.OrderService;
		
		$("#insurant").val(OrderService.user_name);
		$("#id_card").val(OrderService.user_card_no);
		$("#order_phone").val(OrderService.user_phone);
		var sex = "";
		if(OrderService.user_sex==1){
			sex = "男";
		}else if(OrderService.user_sex==2){
			sex = "女";
		}
		$("#sex").val(sex);
		$("#area").val(OrderService.user_district_address);
		$("#e_address").val(OrderService.user_detail_address);
		$("#order_type_desc").val(OrderService.goods_service_name);
		
		
		var user_other_info = e.user_other_info;
		$("#order_design").val(user_other_info.current_order_design);
		$("#insurance_company").val(user_other_info.company);
		
		
		//进度条
		var data_statusList = e.statusList;
		//console.log(data_5)
		var str1="";
		for(i in data_statusList){
			str1 += "<li class='order_status left' is_active='"+ data_statusList[i].is_active +"'><p>"+ data_statusList[i].name +"</p></li><div class='left arrows'><img src='img/right.png'/></div>"
		}
		$("#status").html(str1)
		$("#status").find("li").each(function(){
			if($(this).attr("is_active")==1){
				$(this).addClass("change")
			}
		})
		$("#status").children("div:last-child").addClass("hide");
		
		
		//操作日志
		var data_optionLog = e.optionLog;
		//console.log(data_optionLog)
		var str2 = "";
		var m = 0;
	  	for(var i=0;i<data_optionLog.length;i++){
			m++;
			var create_time = getDate(data_optionLog[i].create_time);
			str2 += "<tr><td>"+ m +"</td><td>"+ data_optionLog[i].manager_name +"</td><td>"+ data_optionLog[i].operation_model +"</td><td>"+ create_time +"</td><td>"+ data_optionLog[i].operation_desc +"</td></tr>"
		}
    	$("#datatable2").find('tbody').html(str2); 
		
		
		
		//保存
		$(document).on("click", "#save",function(){
				var order_design = $("#order_design").val();
				var insurance_company = $("#insurance_company").val();
				var order_type = $("#order_type").val();
				
				$.ajax({
					type:"post",
					url:local+"/shop_ServiceSubmit.php",
					data:{
						"card_order_id" : id,
						"company" : insurance_company,
						"service_status" : order_type,
						"current_order_design" : order_design,
					},
					async:true,
					success:function(data){
						console.log(data)
						if(data.state==0){
    					layer.msg(data.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000},function(){
    						var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
				        	parent.layer.close(index);
    					});
    				}else{
    					layer.msg(data.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
    				}
					}
				});
				
				
		})
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	})
