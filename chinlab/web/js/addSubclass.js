function keywordList(arr){
	var str = "";
	 for(var i=0;i<arr.length;i++){
	 	str += "<li><div class='tags'><span>"+ arr[i] +"</span><span class='removetag icon-remove' data-id='"+ i +"'></span></div></li>"
	 }
	$("#keywordList").html(str)
}

$(document).ready(function(){

	var tagarr = [];	
	 
	$(document).on("click","#addTag",function(e){
	 	var taginput = $("#subclass_name").val();
	 	console.log(taginput.length)
	 	if(taginput.length>5){
	 		layer.msg("字数超出5个", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
	 		$("#subclass_name").val("");
	 	}else{
	 		tagarr.push(taginput);
	 		console.log(tagarr)
	 		keywordList(tagarr); 
	 		$("#subclass_name").val("");
	 	}
	})	 
	keywordList(tagarr); 
	 
	$(document).on("click",".removetag",function(e){
	 	var id = $(this).attr("data-id");
	 	console.log(id)
	 	tagarr.splice(id,1)
	 	console.log(tagarr)
	 	keywordList(tagarr);
	});
	 
	 
	 //保存
	$(document).on("click", "#save",function(){
		var customer_company = $("#customer_company").val();
		
		
		
		
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
			