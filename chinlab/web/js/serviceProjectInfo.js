//时间格式
  function getDate(tm){ 
		var tt=new Date(parseInt(tm) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ") 
		return tt; 
	} 



	$(document).ready(function(){
		var href = window.location.search;    	
		var id = href.replace("?","").split("=")[1];
		
		var d = $("#info_list").html();
		//console.log(d)
		var e = $.parseJSON(d);
		console.log(e)
		$("#customer_company").val(e.customer_name);
		$("#service_items").val(e.items_name);
		$("#service_cost").val(e.items_price);

		
		
		
		
		//保存
		$(document).on("click", "#save",function(){
				var customer_name = $("#customer_company").val();
				var items_name = $("#service_items").val();
				var items_price = $("#service_cost").val();
				
				$.ajax({
					type:"post",
					url:local+"/groupcustomer/editserviceprojectinfo.php",
					data:{
						"items_id" : id,	
						"customer_name" : customer_name,
						"items_name" : items_name,
						"items_price" : items_price,
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
