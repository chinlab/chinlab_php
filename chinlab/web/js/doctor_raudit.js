//时间格式
function getDate(input){
	var oDate = new Date(input);
	function p(s) {
        return s < 10 ? '0' + s: s;
    }
	return oDate.getFullYear()+'-'+(oDate.getMonth()+1)+'-'+oDate.getDate()+' '+oDate.getHours()+':'+p(oDate.getMinutes())+':'+p(oDate.getSeconds());
} 
$(document).ready(function(){
	var href = window.location.search;    	
	var id = href.replace("?","").split("=")[1];
	
	//获取页面信息
	var d = $("#doctor_info").html();
    //console.log(d)
    var e = $.parseJSON(d);
    console.log(e)
    if(e.is_authentication!=3){
    	$("#checkbtn").css('display','none');
        $("#auth_desc").val(e.auth_desc);
    	$("#auth_desc").attr('disabled',true);
    }
    $("#doctor_name").val(e.doctor_name);
    $("#hospital_name").val(e.hospital_name);
    $("#section_info").val(e.section_info);
    $("#doctor_position").val(e.doctor_position);
    $("#doctor_mobile").val(e.doctor_mobile);
    var creatTime = getDate(parseInt(e.create_time)*1000);
    $("#create_time").val(creatTime);
    $("#good_at").val(e.good_at);
    $("#honor").val(e.honor);
    $("#doctor_des").val(e.doctor_des);

    var strimg1 = '';
    var strimg2 = '';
    var strimg3 = '';
    if(e.doctor_head.length != 0){
    	$.each(e.doctor_head,function(k,v){
	    	strimg1 += '<li class="col-sm-4 pull-left"><img layer-pid="'+ k +'" layer-src="'+ v +'" src="'+ v +'" alt="" style="width:100%;height:100%;"></li>';
	    })
	    $(".head_img").html(strimg1);
    }
	if(e.doctor_certificate.length != 0){
		$.each(e.doctor_certificate,function(k,v){
	    	strimg2 += '<li class="col-sm-4 pull-left"><img src="'+ v +'" style="width:100%;height:100%;"></li>';
	    })
	    $(".certificateimg").html(strimg2);
	}
    if(e.doctor_card.length != 0){
    	$.each(e.doctor_card,function(k,v){
	    	strimg3 += '<li class="col-sm-4 pull-left"><img src="'+ v +'" style="width:100%;height:100%;"></li>';
	    })
	    $(".titlesimg").html(strimg3);
    }

    //操作日志
    var logd = $("#log_info").html();
    console.log(logd)
    var data_optionLog = $.parseJSON(logd);
    console.log(data_optionLog)
	var str1 = "";
	var m = 0;
    for(var i=0;i<data_optionLog.length;i++){
		m++;
		var create_time = getDate(data_optionLog[i].create_time*1000)
		str1 += "<tr><td>"+ m +"</td><td>"+ data_optionLog[i].manager_name +"</td><td>"+ create_time +"</td><td>"+ data_optionLog[i].operation_desc +"</td></tr>"
	}
    $("#datatable2").find('tbody').html(str1);
 	
    //点击放大
	  layer.photos({
	    photos: '#head_img',
	    shift: 5,
	    closeBtn:2,
        anim: 0 
	  });
	  layer.photos({
	    photos: '#certificateimg',
	    shift: 5,
	    closeBtn:2,
        anim: 0 
	  });
	  layer.photos({
	    photos: '#titlesimg',
	    shift: 5,
	    closeBtn:2,
        anim: 0  
	  });
    //审核通过
    $(document).on("click", "#pass",function(){
    	var auth_desc = $("#auth_desc").val();
    	$.ajax({
    		type:"post",
    		url:"/atdoctor/doctorexamine.php",
    		data:{
    			"doctor_id" : id,
    			"is_auth" : 1,
    			"auth_desc" : auth_desc,
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
    //审核不通过
    $(document).on("click", "#nopass",function(){
    	var auth_desc = $("#auth_desc").val();
    	$.ajax({
    		type:"post",
    		url:"/atdoctor/doctorexamine.php",
    		data:{
    			"doctor_id" : id,
    			"is_auth" : 2,
    			"auth_desc" : auth_desc,
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