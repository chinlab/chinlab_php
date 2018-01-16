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
	var d = $("#info_refund").html();
    //console.log(d)
    var e = $.parseJSON(d);
    console.log(e)
    
    var order = e.orderInfo;
    var pay_way= '';
	if(order.pay_type==1){
		pay_way = '支付宝';
	}else if(order.pay_type==2){
		pay_way='微信';
	}else{
		pay_way='银联';
	} 
    $("#order_number").val(order.order_id);
    $("#goods_name").val(order.goods_name);
    $("#number").val(order.goods_number);
    $("#pay_money").val(order.pay_money);
    $("#payments").val(pay_way);
    $("#refund_amount").val(order.refund_money);
    $("#refund_design").val(order.refund_design);
    //操作日志
    var data_optionLog = e.operation_log;
		//console.log(data_optionLog)
		var str = "";
		var m = 0;
	    for(var i=0;i<data_optionLog.length;i++){
			m++;
			var create_time = getDate(parseInt(data_optionLog[i].create_time)*1000);
			str += "<tr><td>"+ m +"</td><td>"+ data_optionLog[i].manager_name +"</td><td>"+ data_optionLog[i].operation_model +"</td><td>"+ create_time +"</td><td>"+ data_optionLog[i].operation_desc +"</td></tr>"
		}
    $("#datatable").find('tbody').html(str);

    //通过
    $(document).on("click", "#pass",function(){
	    	
    	var auditing_design =  $("#auditing_design").val();
    	var refund_design =  $("#refund_design").val();
    	$.ajax({
    		type:"post",
    		url:local+"/shop/refundpass.php",
    		data:{
    			"order_id" : id,
    			"auditing_design" : auditing_design,
    			"refund_design" : refund_design,
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
    
    //不通过
    $(document).on("click", "#no_pass",function(){
	    	
    	var auditing_design =  $("#auditing_design").val();
    	var refund_design =  $("#refund_design").val();
    	$.ajax({
    		type:"post",
    		url:local+"/shop/refundfail.php",
    		data:{
    			"order_id" : id,
    			"auditing_design" : auditing_design,
    			"refund_design" : refund_design,
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