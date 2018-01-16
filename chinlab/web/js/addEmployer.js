



$(document).ready(function(){
	
	
	//保存
	
	
	$(document).on("click", "#save",function(){
		var card_no = $("#card_no").val();
		var goods_service_type = $("#goods_service_type").val();
		var user_name = $("#user_name").val();
		var user_phone = $("#user_phone").val();
		var user_card_no = $("#user_card_no").val();
		var user_sex = $("#user_sex").val();
		
		$.ajax({
			type:"post",
			url:local+"/shop_AddServiceUser.php",
			data:{
				"card_no":card_no,
				"goods_service_type":goods_service_type,
				"user_name":user_name,
				"user_phone":user_phone,
				"user_card_no":user_card_no,
				"user_sex":user_sex,
			},
			async:true,
			success:function(data){
				console.log(data)
			}
		});
		
		
		
	})
	
	
	
	
	
	
})