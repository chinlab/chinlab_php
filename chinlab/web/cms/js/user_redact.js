


$(document).ready(function(){
 	//获取频道下拉菜单内容
    var d = $("#info_detail").html();
    //console.log(d)
    var e = $.parseJSON(d);
    //console.log(e)
    var length = e.length ;
    var str1 = "<option value=''>请选择</option>";
    for(var i=0;i<length;i++){
    	str1 += "<option value='"+ e[i].roleId +"'>"+ e[i].name +"</option>";
    }
	$("#role").html(str1);
	
	
	
	var href = window.location.search;    	
	var id = href.replace("?","").split("=")[1];	    	
	//获取编写信息
	var dt = decodeURIComponent($("#info_channel").html());
    //console.log(dt)
    var ev = $.parseJSON(dt);
	//console.log(ev)
	$("#username").val(ev.username);
	$("#nickname").val(ev.nickname);
	$("#userphone").val(ev.userphone);
	$("#role").val(ev.roleId);
	$("#status").val(ev.status);
	
	
	//保存
	$("#saveedit").click(function(){
    		var username = $("#username").val();
    		var nickname = $("#nickname").val();
    		var userphone = $("#userphone").val();
    		var roleId = $("#role").val();
    		var status = $("#status").val();
			$.ajax({
    			type:"post",
    			url:"/cms_userupdate.php",
    			data:{
    				"id" : id,
					"username" : username,
					"nickname": nickname,
					"userphone" : userphone,
					"roleId" : roleId,
					"status" : status,
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