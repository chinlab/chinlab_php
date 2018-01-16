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
	var d = $("#info_cart").html();
    //console.log(d)
    var e = $.parseJSON(d);
    console.log(e);
    var data_order = e.OrderService;
    $("#service_Project").val(data_order.goods_service_name);
    $("#membershipCard_number").val(data_order.card_no);
    $("#customer_name").val(data_order.user_name);
    $("#Idnumber").val(data_order.user_card_no);
    $("#phone_number").val(data_order.user_phone);
    var orderTime = getDate(parseInt(data_order.create_time)*1000);
    $("#order_time").val(orderTime);
    $("#current_order_design").val(data_order.current_order_design);
    //保存
//  $(document).on("click", "#save",function(){
//	    	
//	    	var order_design = $("#order_design").val();
//	    	var logistics = $("#logistics").val();
//	    	var waybill = $("#waybill").val();
//  	
//	    	$.ajax({
//	    		type:"post",
//	    		url:local+"/shop_OrderAccompanyUpdate.php",
//	    		data:{
//	    			"order_id" : id,
//	    			"order_detail_note":order_design,
//	    			"express_no":waybill,
//	    			"id":logistics,
//	    		},
//	    		async:true,
//	    		success:function(data){
//	    			console.log(data)
//	    			if(data.state==0){
//  					layer.msg(data.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000},function(){
//  						var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
//				        	parent.layer.close(index);
//  					});
//  				}else{
//  					layer.msg(data.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
//  				}
//	    		}
//	    	});
//	    	
//	    	
//	    	
//	    	
//	    	
//	    	
//	    })
//  
    
    
    
    
    
    
    
})