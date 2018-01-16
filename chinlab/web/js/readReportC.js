//时间格式
  function getDate(tm){ 
		var tt=new Date(parseInt(tm) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ") 
		return tt; 
	} 


$(document).ready(function(){
	
	var href = window.location.search;    	
	var id = href.replace("?","").split("=")[1];
	$("#case_img").find("a").attr("trid",id)
	$(".imgNum").find("a").attr("trid",id)
	var d = $("#info_state").html();
    //console.log(d)
    var e = $.parseJSON(d);
    //console.log(e)
    //是否生成报告
    if(e.order_state==3){
    	$("#anomalyIndex").attr("disabled","disabled");
    	$("#summary").attr("disabled","disabled");
    	$("#suggest").attr("disabled","disabled");
    }
    $("#examine_place").val(e.check_organization);
    $("#examine_time").val(e.report_check_time);
    $("#orderfile_url_sum").html(e.orderfile_url_sum);
    $("#anomalyIndex").val(e.exception_info);
    $("#summary").val(e.summary_info);
    $("#suggest").val(e.advise_info);
    $("#principal").val(e.report_doctor_name);
    
    //操作日志
    var data_optionLog = e.operation_log;
	//console.log(data_optionLog)
	
	var str1 = "";
	var m = 0;
    for(var i=0;i<data_optionLog.length;i++){
		m++;	
		var create_time = getDate(data_optionLog[i].create_time)
		var operation = "";
		var str = "";
		if(data_optionLog[i].operation_type==5){
			var operation_desc = data_optionLog[i].operation_desc;
			operation_desc = $.parseJSON(operation_desc);
			//console.log(operation_desc)
			for(var j=0;j<operation_desc.length;j++){
				str += "<li class='pull-left col-sm-1'><img class='col-sm-12' layer-pid='1' layer-src='"+ operation_desc[j] +"' src='"+ operation_desc[j] +"'></li>";
			}
			
			operation = "<ul class='layer-photos-demo layer-photos-demo'>"+ str +"</ul>"
		}else{
			operation = data_optionLog[i].operation_desc;
		}
		str1 += "<tr><td>"+ m +"</td><td>"+ data_optionLog[i].manager_name +"</td><td>"+ data_optionLog[i].operation_model +"</td><td class='col-sm-6 error_img'>"+ operation +"</td><td>"+ create_time +"</td></tr>"
	}
    $("#datatable2").find('tbody').html(str1);
    //点击放大
    layer.ready(function(){ 
	  layer.photos({
	    photos: '.layer-photos-demo'
	    ,shift: 5 //0-6的选择，指定弹出图片动画类型，默认随机
	  });
	});
   
    
    
    
    
    
    
    //阅读
    var layerindex = "";
	$(document).on("click", ".goCase,.goCheck",function() {
		var trid = $(this).attr("trid");
		$(document.body).css({
			"overflow-x": "hidden",
			"overflow-y": "hidden"
		});
		powidth = $(window).width() - 50;
		poheight = $(window).height() - 50;
		layerindex = layer.open({
			type: 2,
			title: "报告图片",
			fix: false,
			shadeClose: true,
			maxmin: true,
			area: [powidth + 'px', poheight + 'px'],
			content: "/chunfeng/reportimgc.php?order_id=" + trid,
			cancel: function(index,layero) {  
				var pIndex = index;
				var body = layer.getChildFrame('body', index);
        		var iframeWin = window[layero.find('iframe')[0]['name']]; //得到iframe页的窗口对象，执行iframe页的方法：iframeWin.method();
				//console.log(iframeWin)	
				var isChack = iframeWin.isChack();//调用子页面的方法，得到子页面返回的ids
				//console.log(isChack)
				if(isChack==1){
					layer.confirm('您选择了驳回的报告,是否关闭。', {
						btn: ['确定','取消'] //按钮
					}, function(index){
						layer.close(index);
						layer.close(pIndex);
					}, function(index){
						layer.close(index);
					});
				}else{
					layer.close(index);
				}
				return false
            },  
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
	    	
    	var exception_info = $("#anomalyIndex").val();
    	var summary_info = $("#summary").val();
    	var advise_info = $("#suggest").val();
	
    	$.ajax({
    		type:"post",
    		url:local+"/groupcustomer/userordersave.php",
    		data:{
    			"order_id" : id,
    			"exception_info":exception_info,
    			"summary_info":summary_info,
    			"advise_info":advise_info,
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
    //生成报告
    $(document).on("click", "#g_report",function(){
	    	
    	var exception_info = $("#anomalyIndex").val();
    	var summary_info = $("#summary").val();
    	var advise_info = $("#suggest").val();
		if(exception_info==""){
			layer.msg("异常指标不能为空", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
		}else if(summary_info==""){
			layer.msg("分析总结不能为空", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
		}else if(advise_info==""){
			layer.msg("报告建议不能为空", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
		}else{
			$.ajax({
	    		type:"post",
	    		url:local+"/groupcustomer/userordersave.php",
	    		data:{
	    			"order_id" : id,
	    			"exception_info":exception_info,
	    			"summary_info":summary_info,
	    			"advise_info":advise_info,
	    			"order_state":3,
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
