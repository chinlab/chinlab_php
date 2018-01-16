
//时间格式
  function getDate(tm){ 
		var tt=new Date(parseInt(tm) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ") 
		return tt; 
	} 


$(document).ready(function(){
	
	var dt = decodeURIComponent($("#info_channel").html());
    //console.log(dt)
    var ev = $.parseJSON(dt);
	console.log(ev)
	var id = ev.id;
	$(".user_name").val(ev.username);
	$(".name").val(ev.nickname);
	$(".phone_num").val(ev.userphone);
	if(ev.roleId==1){
		$(".user_role").val("admin");
	}else if(ev.roleId==2){
		$(".user_role").val("审核人员");
	}else if(ev.roleId==3){
		$(".user_role").val("编辑人员");
	}else{
		$(".user_role").val("暂无角色");
	}
	
	if(ev.status==10){
		$(".user_state").val("开启");
	}else if(ev.roleId==2){
		$(".user_state").val("关闭");
	}
 	var time = getDate(ev.created_at)
	$(".create_time").val(time);
	
	
	
	//保存
 	$("#saveedit").click(function(){
 		var username = $(".user_name").val();
 		var userphone = $(".phone_num").val();
   		$.ajax({
   			type:"post",
   			url:"/cms_Userupdate.php",
   			data:{
   				"id":id,
   				"username":username,
   				"userphone":userphone,
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
 	
 	
 	
 	//重置
 	$("#reset").click(function(){
 		$(".user_name").val("");
 		$(".phone_num").val("");
 	})
 	
 	
 })