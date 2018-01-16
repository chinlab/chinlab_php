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
  	var go_time = $("#gohospital_time").val();
  	if(go_time==""){
  		$("#gohospital_time_end").val("");
  	}else{
  		var time = new Date(go_time.replace("-","/"));
	    var timestamp = time.getTime();
			timestamp = timestamp + 3600000;
			var end_time=new Date(timestamp);     
			end_time = formatDate(end_time); 
			$("#gohospital_time_end").val(end_time);
  	}
    
  }

	$(document).ready(function(){
		
		$('#gohospital_time').datetimepicker({
			language: 'zh-CN',
			format: "yyyy-mm-dd hh:ii",
			startDate: new Date(),
			minuteStep: 30,
		}).on('changeDate', function(ev) {});
		
		
		
		var href = window.location.search;    	
		var id = href.replace("?","").split("=")[1];
		
		var d = $("#info_list").html();
		//console.log(d)
		var e = $.parseJSON(d);
		console.log(e)
		$("#customer_company").val(e.customer_name);
		$("#service_items").val(e.items_name);
		$("#items_classify").val(e.items_type_desc);
		$("#service_cost").val(e.items_price);
		$("#customer_name").val(e.order_name);
		$("#phone_num").val(e.order_phone);
		$("#hospital_name").val(e.hospital_name);
		$("#section_name").val(e.section_name);
		$("#doctor_name").val(e.doctor_name);
		$("#MIcard").val(e.medicare_card);
		$("#gohospital_time").val(e.order_date);
		endTime();
		
		
		
		$("#disease_des").val(e.disease_des);
		$("#disease_name").val(e.disease_name);
		
		
		
		
		var data1 = e.canDoStatesList;
		var str1 = "";
		//console.log(data1)
		for(var i=0;i<data1.length;i++){
			str1 += "<option value='"+ data1[i].val +"'>"+ data1[i].name +"</option>"
		}
		$("#serve_type").html(str1);
		
		
		
		
		
		//进度条
		var data_statusList = e.orderStateslist;
		//console.log(data_5)
		var str2="";
		for(i in data_statusList){
			str2 += "<li class='order_status left' is_active='"+ data_statusList[i].is_active +"'><p>"+ data_statusList[i].name +"</p></li><div class='left arrows'><img src='img/right.png'/></div>"
		}
		$("#status").html(str2)
		$("#status").find("li").each(function(){
			if($(this).attr("is_active")==1){
				$(this).addClass("change")
			}
		})
		$("#status").children("div:last-child").addClass("hide");
		
		
		//操作日志
		var data_optionLog = e.operation_log;
		//console.log(data_optionLog)
		var str3 = "";
		var m = 0;
	  	for(var i=0;i<data_optionLog.length;i++){
			m++;
			var create_time = getDate(data_optionLog[i].create_time);
			str3 += "<tr><td>"+ m +"</td><td>"+ data_optionLog[i].manager_name +"</td><td>"+ data_optionLog[i].operation_model +"</td><td>"+ create_time +"</td><td>"+ data_optionLog[i].operation_desc +"</td></tr>"
		}
    	$("#datatable").find('tbody').html(str3); 
		
		
		$("#gohospital_time").change(function(){
			endTime()
		})
		
		
		
		
		
		
		
		
		
		
		//保存
		$(document).on("click", "#save",function(){
				var customer_name = $("#customer_name").val();
				var phone_num = $("#phone_num").val();
				var hospital_name = $("#hospital_name").val();
				var section_name = $("#section_name").val();
				var doctor_name = $("#doctor_name").val();
				var MIcard = $("#MIcard").val();
				var gohospital_time = $("#gohospital_time").val();
				var disease_des = $("#disease_des").val();
				var serve_type = $("#serve_type").val();
				var disease_name = $("#disease_name").val();
				$.ajax({
					type:"post",
					url:local+"/groupcustomer/updateorderstatus.php",
					data:{
						"order_id" : id,
						"order_name" : customer_name,
						"order_phone" : phone_num,
						"order_date" : gohospital_time,
						"disease_des" : disease_des,
						"doctor_name" : doctor_name,
						"section_name" : section_name,
						"hospital_name" : hospital_name,
						"medicare_card" : MIcard,
						"order_state" : serve_type,
						"disease_name" : disease_name,
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
