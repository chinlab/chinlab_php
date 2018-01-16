//时间格式
 function getDate(input){
	var oDate = new Date(input);
	function p(s) {
        return s < 10 ? '0' + s: s;
    }
	return oDate.getFullYear()+'-'+(oDate.getMonth()+1)+'-'+oDate.getDate();
} 

$(document).ready(function(){
	var href = window.location.search;    	
	var id = href.replace("?","").split("=")[1];
	$("#case_img").find("a").attr("trid",id);
	//获取页面信息
	var d = $("#info_cart").html();
    //console.log(d)
    var e = $.parseJSON(d);
    console.log(e);
    var data_order = e.user_other_info;
    $("#hospital_name").val(data_order.hospital_name);
    if(data_order.report_check_time==""){
    	var report_check_time = "";
    }else{
    	var report_check_time = getDate(parseInt(data_order.report_check_time)*1000);
    }
    
    $("#visit_time").val(report_check_time);
    $("#disease_des").val(data_order.disease_des);
    //照片是否上传
	if(data_order.is_show_img==0){
		$("#case_img").css("display","none");
		$(".upload").html("未上传")
	}else{
		$("#case_img").css("display","block");
		$(".upload").html("已上传")
	}
    //操作日志
    var data_optionLog = e.optionLog;
	//console.log(data_optionLog)
	var str = "";
	var m = 0;
    for(var i=0;i<data_optionLog.length;i++){
		m++;
		var create_time = getDate(parseInt(data_optionLog[i].create_time));
		str += "<tr><td>"+ m +"</td><td>"+ data_optionLog[i].manager_name +"</td><td>"+ data_optionLog[i].operation_model +"</td><td>"+ create_time +"</td><td>"+ data_optionLog[i].operation_desc +"</td></tr>"
	}
    $("#datatable").find('tbody').html(str);
    //查看健康档案
    var layerindex = "";
	$(document).on("click", ".goCase",function() {
		var trid = $(this).attr("trid");
		$(document.body).css({
			"overflow-x": "hidden",
			"overflow-y": "hidden"
		});
		powidth = $(window).width() - 50;
		poheight = $(window).height() - 50;
		layerindex = layer.open({
			type: 2,
			title: "健康档案资料",
			fix: false,
			shadeClose: true,
			maxmin: true,
			area: [powidth + 'px', poheight + 'px'],
			content: "/chunfeng/shopimg.php?card_order_id=" + trid,
			end: function() {
				$(document.body).css({
					"overflow-x": "auto",
					"overflow-y": "auto"
				});
			}
		});
	});
    
    //保存
    $(document).on("click", "#save",function(){
	    	
	    	var hospital_name = $("#hospital_name").val();
	    	var check_time = data_order.report_check_time;
	    	var disease_desc = $("#disease_des").val();
    	
	    	$.ajax({
	    		type:"post",
	    		url:local+"/shop_ServiceSubmit.php",
	    		data:{
	    			"hospital_name" : hospital_name,
	    			"report_check_time" : check_time,
	    			"disease_desc":disease_desc,
	    			"card_order_id":id,
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