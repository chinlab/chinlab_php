$(document).ready(function(){
	
	var dt = decodeURIComponent($("#info_channel").html());
    //console.log(dt)
    var ev = $.parseJSON(dt);
	console.log(ev)
	var id = ev.id;
	
	
	//保存
 	$("#saveedit").click(function(){
 		var oldPw = $(".oldPw").val();
 		var newPw = $(".newPw").val();
 		var verifyPw = $(".verifyPw").val();
 		console.log(newPw)
 		console.log(verifyPw)
 		console.log(newPw==verifyPw)
 		if(newPw==verifyPw){
 			$.ajax({
 				type:"post",
 				url:"/cms_updatepassword.php",
 				data:{
 					"id":id,
 					"oldpassword":oldPw,
 					"password":newPw,
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
 		}else{
 			layer.msg("输入的密码不一致", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
 		}
 		
 		
 		
 	})
 	
 	//重置
 	$("#reset").click(function(){
 		$(".oldPw").val("");
 		$(".newPw").val("");
 		$(".verifyPw").val("");
 	})
 	
 	
 	
 	
 	
 	
 	
 	
 	
 	
 	
 	
 	
 	
 	
 	
 })