



$(document).ready(function(){
 	var d = $("#info_channel").html();
    //console.log(d)
    var e = $.parseJSON(d);
    //console.log(e)
    $("#ad_titel").val(e.title);
    var material_id = e.material_id
    
    
    var date1 = new Date();
//	var d = new Date();
//	d.setMonth(d.getMonth()+3);
    $('#release_time').datetimepicker({
    	language: 'zh-CN',
	    todayBtn : "linked",  
	    autoclose : true,  
	    todayHighlight : true,  
	    startDate : date1,
	}).on('changeDate',function(e){  
	    var startTime = e.date;  
	    $('#end_time').datetimepicker("setStartDate",startTime);
	});  
	//结束时间：  
	
	$('#end_time').datetimepicker({
		language: 'zh-CN',
	    todayBtn : "linked",  
	    autoclose : true,  
	    todayHighlight : false,
	    startDate : date1,
	}).on('changeDate',function(e){  
	    var endTime = e.date;  
	    $('#release_time').datetimepicker('setEndDate',endTime);  
	});
    
    
    $("#savebtn").click(function(){
    	var title = $('#ad_titel').val();
    	var keep_time = $('#time_duration').val();
    	var publish_time = $('#release_time').val();
    	var end_time = $('#end_time').val();
    	
    	if(end_time==""){
    		layer.msg("结束时间不能为空", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
    	}else{
    		$.ajax({
	    		type:"post",
	    		url:"/cms_publishad.php",
	    		data:{
	    			"material_id": material_id,
	    			"title": title,
	    			"keep_time": keep_time,
	    			"publish_time": publish_time,
	    			"end_time": end_time,
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
    	}
    	
    })
    
    
    
    
    
    
    
    
 })