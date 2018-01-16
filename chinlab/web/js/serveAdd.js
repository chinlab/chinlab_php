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

	$(document).ready(function(){
		$('#gohospital_time').datetimepicker({
			language: 'zh-CN',
			format: "yyyy-mm-dd hh:ii",
			startDate: new Date(),
			minuteStep: 30,
		}).on('changeDate', function(ev) {});
		
		
		
		
		var href = window.location.search;    	
		var id = href.replace("?","").split("=")[1];
		
		var d = $("#info_serveType").html();
		//console.log(d)
		var e = $.parseJSON(d);
		console.log(e)
	 	var str1 = "<option value=''>请选择</option>";
		//console.log(data1)
		for(var i=0;i<e.length;i++){
			str1 += "<option value='"+ e[i].customer_id +"'>"+ e[i].customer_name +"</option>"
		}
		$("#customer_company").html(str1);
		
		$("#customer_company").change(function(){
				var customer_id = $("#customer_company").val();
				console.log(customer_id)
				$.ajax({
					type:"post",
					url:local+"/groupcustomer/customeritems.php",
					data:{
						"customer_id":customer_id,
					},
					async:true,
					success:function(data){
						console.log(data)
						var data = data.data;
						var str2 = "<option value=''>请选择</option>";
						for(var i=0;i<data.length;i++){
							str2 += "<option value='"+ data[i].items_id +"'>"+ data[i].items_name +"</option>"
						}
						$("#service_items").html(str2);
						
						$("#service_items").change(function(){
							var items_id = $("#service_items").val();
							console.log(data)
							for(var i=0;i<data.length;i++){
								if(items_id==data[i].items_id){
									$("#items_classify").val(data[i].items_type_desc);
									$("#service_cost").val(data[i].items_price);
								}
							}							
						})
					
					}
				});	
					
		})
		
		$("#gohospital_time").change(function(){
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
		})
		
		
		
		
		
		
		//保存
		$(document).on("click", "#save",function(){
				var customer_company = $("#customer_company").val();
				var service_items = $("#service_items").val();
				var customer_name = $("#customer_name").val();
				var phone_num = $("#phone_num").val();
				var hospital_name = $("#hospital_name").val();
				var section_name = $("#section_name").val();
				var doctor_name = $("#doctor_name").val();
				var MIcard = $("#MIcard").val();
				var gohospital_time = $("#gohospital_time").val();
				var disease_des = $("#disease_des").val();
				var disease_name = $("#disease_name").val();
				
				
				
				$.ajax({
					type:"post",
					url:local+"/groupcustomer/orderoperation.php",
					data:{
						"customer_id" : customer_company,
						"items_id" : service_items,
						"order_name" : customer_name,
						"order_phone" : phone_num,
						"hospital_name" : hospital_name,
						"order_date" : gohospital_time,
						"disease_des" : disease_des,
						"section_name" : section_name,
						"doctor_name" : doctor_name,
						"medicare_card" : MIcard,
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
