$(document).ready(function(){
	
	 
	 //保存
	$(document).on("click", "#save",function(){
		var categoryName = $("#categoryName").val();
		
		$.ajax({
			type:"post",
			url:local+"/groupcustomer/orderoperation.php",
			data:{
				"customer_id" : customer_company,
				"items_id" : service_items,
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