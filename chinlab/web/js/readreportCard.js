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
	$("#case_img").find("a").attr("trid",id)
	$(".imgNum").find("a").attr("trid",id)
	//获取页面信息
	var d = $("#info_state").html();
    //console.log(d)
    var e = $.parseJSON(d);
    console.log(e)

    $("#examine_time").val(e.report_check_time);
    $("#examine_place").val(e.check_organization);
    //操作日志
    var data_optionLog = e.operation_log;
	//console.log(data_optionLog)
	
	var str1 = "";
	var m = 0;
    for(var i=0;i<data_optionLog.length;i++){
		m++;	
		var create_time = getDate(parseInt(data_optionLog[i].create_time)*1000);
		var operation = "";
		var str = "";
		if(data_optionLog[i].operation_type==99 || data_optionLog[i].operation_type==5){
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
			title: "报告图片",
			fix: false,
			shadeClose: true,
			maxmin: true,
			area: [powidth + 'px', poheight + 'px'],
			content: "/chunfeng/reportimgvip.php?card_order_id=" + trid,
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
    	
    	$.ajax({
    		type:"post",
    		url:local+"/shop/userordersave.php",
    		data:{
    			"order_id" : id,
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