//时间格式
  function getDate(tm){ 
		var tt=new Date(parseInt(tm) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ") 
		return tt; 
	} 
	
	
	function timechange(str){
		if(str<10){
			str = "0"+str;
		}else{
			str = str;
		};
		return str;
	}
	
	function   formatDate(now)   {     
      var   year=now.getFullYear();
      var   month=now.getMonth()+1;
      month = timechange(month)
      var   date=now.getDate();
      date = timechange(date)
      var   hour=now.getHours();
      hour = timechange(hour)
      var   minute=now.getMinutes();
      minute = timechange(minute)
      return   year+"-"+month+"-"+date+" "+hour+":"+minute;     
  }    
  
  function endTime(){
  	var go_timer = $("#go_time").val();
  	if(go_timer==""){
  		$("#go_time_end").val("");
  	}else{
  		var time = new Date(go_timer.replace("-","/"));
	    var timestamp = time.getTime();
			timestamp = timestamp + 3600000;
			var end_time=new Date(timestamp);     
			end_time = formatDate(end_time); 
			$("#go_time_end").val(end_time);
  	}
  }
	
	
$(document).ready(function(){
	
			var href = window.location.search;    	
    	var id = href.replace("?","").split("=")[1];
    	$("#case_img").find("a").attr("id",id);
    	$("#case_img").find("a").attr("trid",id);
	
			//时间控件
			$('#go_time').datetimepicker({
				minView: "month",
				language: 'zh-CN',
				format: "yyyy-mm-dd",
				startDate: new Date(),
				todayBtn:  1,
    		autoclose: 1,
//				minuteStep: 30,
			}).on('changeDate', function(ev) {});
			
			
			var d = $("#info_cart").html();
			//console.log(d)
			var e = $.parseJSON(d);
			var sex = "";
			console.log(e)
			var OrderService = e.OrderService;
			var pay_way= '';
			if(OrderService.pay_type==1){
				pay_way = '支付宝';
			}else if(OrderService.pay_type==2){
				pay_way='微信';
			}else if(OrderService.pay_type==3){
				pay_way='银联';
			}else{
				pay_way='去医院支付';
			}
			$("#goods_service_name").val(OrderService.goods_service_name);
			$("#user_name").val(OrderService.user_name);
			if(OrderService.user_sex==1){
				sex = "男"
			}else if(OrderService.user_sex==2){
				sex = "女"
			}
			$("#user_sex").val(sex);
			$("#id_card").val(OrderService.user_card_no);
			$("#order_phone").val(OrderService.user_phone);
			$("#medical_insurance_number").val(OrderService.medical_insurance_number);
			$("#order_city_name").val(OrderService.order_city_name);
			$("#doctor_level_id").val(OrderService.doctor_level_desc);
			$("#current_order_fee").val(OrderService.pay_money);
			$("#current_order_fee_type").val(pay_way);
			$("#disease_name").val(OrderService.disease_name);
			$("#disease_des").val(OrderService.disease_des);
			$("#order_design").val(OrderService.current_order_design);
			var user_other_info = e.user_other_info;
			$("#hospital_name").val(user_other_info.current_hospital_name);
			$("#section_name").val(user_other_info.current_section_name);
			$("#go_doctor").val(user_other_info.current_doctor_name);
			$("#current_outpatient_type").val(user_other_info.current_outpatient_type);
			$("#go_time").val(user_other_info.current_order_date);
			//endTime();
			
			$("#qh_place").val(user_other_info.current_order_area);
			
			//照片是否上传
			if(OrderService.order_file.length==0){
				$("#case_img").css("display","none");
				$(".upload").html("未上传")
			}else{
				$("#case_img").css("display","block");
				$(".upload").html("已上传")
			}
			
			
			
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
				var create_time = getDate(data_optionLog[i].create_time)
				str2 += "<tr><td>"+ m +"</td><td>"+ data_optionLog[i].manager_name +"</td><td>"+ data_optionLog[i].operation_model +"</td><td>"+ create_time +"</td><td>"+ data_optionLog[i].operation_desc +"</td></tr>"
			}
	    $("#datatable2").find('tbody').html(str2); 
			
			
//			$("#go_time").change(function(){
//				endTime()
//			})		
			
			//预览
			var layerindex = "";
			var ifchange = false;
			$(document).on("click", ".goCase",
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
						title: "资料详情",
						fix: false,
						shadeClose: false,
						maxmin: true,
						area: [powidth + 'px', poheight + 'px'],
						content: "/chunfeng/shopimg.php?card_order_id=" + trid,
						end: function() {
							$(document.body).css({
								"overflow-x": "auto",
								"overflow-y": "auto"
							});
						}
					});
				});
			
			
			
			
			
			//保存
			$(document).on("click", "#save",function(){
					var order_design = $("#order_design").val();
					var go_hospital = $("#hospital_name").val();
					var go_section = $("#section_name").val();
					var go_doctor = $("#go_doctor").val();
					var current_outpatient_type = $("#current_outpatient_type").val();
					var go_time = $("#go_time").val();
					var qh_place = $("#qh_place").val();
					var service_status = $("#order_type").val();
					var current_order_design = $("#order_design").val();
					$.ajax({
						type:"post",
						url:local+"/shop_ServiceSubmit.php",
						data:{
							"card_order_id" : id,
							"current_hospital_name" : go_hospital,
							"current_section_name" : go_section,
							"current_doctor_name" : go_doctor,
							"current_outpatient_type" : current_outpatient_type,
							"current_order_date" : go_time,
							"current_order_area" : qh_place,
							"service_status" : service_status,
							"current_order_design" : current_order_design,
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

