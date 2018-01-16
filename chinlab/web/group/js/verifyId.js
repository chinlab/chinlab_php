 function jump(count) { 
    window.setTimeout(function(){ 
        count--; 
        if(count > 0) { 
            $('#num').html(count); 
            jump(count); 
        } else {
            location.href="/groupclient/logout.php"; 
        } 
    }, 1000); 
} 

$(document).ready(function(){
	var old_pass;
	$(document).on("click", "#nextbtn1",function() {
		var username = $("#username").val();
		var passwd = $("#password").val();
		if(username==""){
			$(".name_tip").addClass("isShow");
			$(".name_tip").find('span').html("用户名不能为空");
		}else if(passwd==""){
			$(".passwd_tip").addClass("isShow");
			$(".name_tip").removeClass("isShow");
			$(".passwd_tip").find('span').html("密码不能为空");
		}else{
			
			$.ajax({
				type:"post",
				url:local+"/groupclient/checkpassword.php",
				data:{
					"username": username,
					"old_pass": passwd,
				},
				async:true,
				success:function(data){
					$(".name_tip").removeClass("isShow");
					$(".passwd_tip").removeClass("isShow");
					console.log(data)
					console.log(data.state)
					if(data.state==100){
						layer.msg(data.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
						$("#username").val("");
						$("#password").val("");
					}else{
						old_pass = $("#password").val();
						$("#content1").addClass("isHide");
						$("#content2").addClass("isShow");
					}
					
				}
			});
		}
		
	})
	
	$(document).on("blur","#new_password",function(){
		var new_password1 = $("#new_password").val();
		console.log(new_password1.length)
		if(new_password1.length<6){
			$(".newpasswd_tip").addClass("isShow");
			$(".newpasswd_tip").find('span').html("密码输入少于6位");
			$("#new_password").val("");
		}else if(new_password1.length>16){
			$(".newpasswd_tip").addClass("isShow");
			$(".newpasswd_tip").find('span').html("密码输入多于16位");
			$("#new_password").val("");
		}else{
			$(".newpasswd_tip").removeClass("isShow");
		}
	})
	$(document).on("blur", "#reconfirm_password",function() {
		var new_password2 = $("#new_password").val();
		var reconfirm_password = $("#reconfirm_password").val();
		if(new_password2 != reconfirm_password){
			$(".reconfirm_pw_tip").addClass("isShow");
			$(".reconfirm_pw_tip").find('span').html("两次密码输入不一致,请重新输入");
			$("#new_password").val("");
			$("#reconfirm_password").val("");
		}else{
			$(".reconfirm_pw_tip").removeClass("isShow");
		}
	})
	
	$(document).on("click", "#nextbtn2",function() {
		var new_password3 = $("#new_password").val();
		var reconfirm_password = $("#reconfirm_password").val();
		console.log(old_pass)
		if(new_password3==""){
			layer.msg("新密码不能为空", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
		}else if(reconfirm_password==""){
			layer.msg("确认新密码不能为空", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
		}else{
		
			$.ajax({
				type:"post",
				url:local+"/groupclient/resetpassword.php",
				data:{
						"old_pass": old_pass,
						"password": reconfirm_password,
					},
				async:true,
				success:function(data){
					console.log(data)
					$(".newpasswd_tip").removeClass("isShow");
					$(".reconfirm_pw_tip").removeClass("isShow");
					console.log(data.state)
					if(data.state==100){
						layer.msg(data.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
						$("#new_password").val("");
						$("#reconfirm_password").val("");
					}else{
						$("#content2").addClass("isHide");
						$("#content3").addClass("isShow");
						jump(3)
					}
				}
			});
		}
	})
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
})