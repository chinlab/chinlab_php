
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
		//console.log(e)
		var cardInfo = e.cardInfo;
		var active_status = "";
		$("#card_number").val(cardInfo.card_no);
		$("#card_name").val(cardInfo.goods_name);
		$("#order_id").val(cardInfo.order_id);
		$("#card_user").val(cardInfo.apply_user_name);
		$("#phone_num").val(cardInfo.phone_no);
		if(cardInfo.active_status==1){
			active_status = "已激活";
		}else{
			active_status = "未激活";
		}
		
		$("#card_status").val(active_status);
		$("#old_usernum").val(cardInfo.service_user_limit);
		
		$("#addperson").click(function(){
				$.ajax({
					type:"post",
					url:local+"/shop_ServicesNumadd.php",
					data:{
						"card_no":id,
					},
					async:true,
					success:function(data){
						//console.log(data)
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