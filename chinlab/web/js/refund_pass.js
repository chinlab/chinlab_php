//时间格式
function getDate(input){
	var oDate = new Date(input);
	function p(s) {
        return s < 10 ? '0' + s: s;
    }
	return oDate.getFullYear()+'-'+(oDate.getMonth()+1)+'-'+oDate.getDate()+' '+oDate.getHours()+':'+p(oDate.getMinutes())+':'+p(oDate.getSeconds());
} 


$(document).ready(function(){
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
    var refund_status= ''; 
    if(order.refund_status==1){
		refund_status = '成功';
	}else if(order.refund_status==2){
		refund_status='失败';
	}
    $("#refund_type").val(refund_status);
    $("#refund_design").val(order.refund_design);
    $("#auditing_design").val(order.auditing_design);
    $("#refund_desc").val(order.refund_status_desc);
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
    
})