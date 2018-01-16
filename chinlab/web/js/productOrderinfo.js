//时间格式
  function getDate(tm){ 
		var tt=new Date(parseInt(tm) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ") 
		return tt; 
	} 


$(document).ready(function(){
	//获取页面信息
	var d = $("#info_product").html();
    //console.log(d)
    var e = $.parseJSON(d);
    console.log(e)
    var data_order = e.order;
	var order_state = "";
	var is_invoice = "";
	var invoice_type = "";
	var id = data_order.order_id;
    $("#order_id").val(data_order.order_id);
    $("#order_time").val(data_order.create_time);
    if(data_order.order_state==1){
    	order_state = "未支付";
    }else if(data_order.order_state==2){
    	order_state = "已支付";
    }else{
    	order_state = "已取消";
    }
    $("#order_states").val(order_state);
    $("#purchaser").val(data_order.user_name);
    if(data_order.is_invoice==0){
    	is_invoice = "不需要";
    }else if(data_order.order_state==1){
    	is_invoice = "需要";
    }
    $("#is_invoice").val(is_invoice)
    if(data_order.invoice_type==1){
    	invoice_type = "个人";
    }else if(data_order.invoice_type==2){
    	invoice_type = "公司";
    }
    $("#invoice_type").val(invoice_type);
    $("#invoice_title").val(data_order.invoice_header_name);
    $("#invoice_name").val(data_order.invoice_title);
    $("#invoice_cont").val(data_order.invoice_content);
    $("#order_design").val(data_order.order_detail_note);

    $("#user_card_no").val(data_order.user_card_no);
    $("#taxpayer_ident_no").val(data_order.taxpayer_ident_no);

    var data_express = e.express;
    var express_no = data_express.express_id;
    $("#waybill").val(data_express.express_no);
    console.log(data_express)
	if(data_express.express_no != ""){
		$("#waybill").attr('disabled','disabled');
		$("#logistics").attr('disabled','disabled');
	}
    //收货人信息
    var data_address = e.address;
    $("#consignee").val(data_address.user_name)
    $("#consignee_phone").val(data_address.user_phone)
    $("#consignee_address").val(data_address.user_detail_address)
    
  
//  var data_goods = e.goods;
//		//console.log(data_4)
//		var str1 = "";
//		str1 += "<tr><td>"+ data_goods.goods_id +"</td><td>"+ data_goods.goods_name +"</td><td>"+ data_goods.now_price +"</td><td>"+ data_goods.buy_number +"</td><td>"+ data_goods.sum_price +"</td></tr>"
//		$("#datatable3").find('tbody').html(str1)
//  
    
    
    
    //商品信息
    if(e.paylist.result){
    	var data_paylist = e.paylist.result;
    	var str2 = "";
			if(data_paylist.length){
					for(var i=0;i<data_paylist.length;i++){
						var update_time = getDate(data_paylist[i].update_time)
						str2 += "<tr><td>"+ data_paylist[i].goods_id +"</td><td>"+ data_paylist[i].goods_name +"</td><td>"+ data_paylist[i].now_price +"</td><td>"+ data_paylist[i].buy_number +"</td><td>"+ data_paylist[i].freight_price +"</td><td>"+ data_paylist[i].pay_type +"</td><td>"+ update_time +"</td><td>"+ data_paylist[i].pay_status +"</td></tr>"
					}
			}
			
    }
    if(e.paylist.total){
    	str2 += "<tr><td colspan='6'>合计费用：<span>"+ e.paylist.total +"</span></td></tr>";
    }
		
	  
    $("#datatable3").find('tbody').html(str2);
    
    
    
    
    
    
    //操作日志
    var data_optionLog = e.optionLog;
		//console.log(data_optionLog)
		var str3 = "";
		var m = 0;
	    for(var i=0;i<data_optionLog.length;i++){
				m++;
				var create_time = getDate(data_optionLog[i].create_time)
				str3 += "<tr><td>"+ m +"</td><td>"+ data_optionLog[i].manager_name +"</td><td>"+ data_optionLog[i].operation_model +"</td><td>"+ create_time +"</td><td>"+ data_optionLog[i].operation_desc +"</td></tr>"
			}
    $("#datatable5").find('tbody').html(str3);
  
    //查看物流
    $(document).on("click", "#check",function() {
					
					$(document.body).css({
						"overflow-x": "hidden",
						"overflow-y": "hidden"
					});
					powidth = $(window).width() - 50;
					poheight = $(window).height() - 50;
					layerindex = layer.open({
						type: 2,
						title: "物流信息",
						fix: false,
						shadeClose: true,
						maxmin: true,
						area: [powidth + 'px', poheight + 'px'],
						content: "/chunfeng_logisticsinformation.php?express_id=" + express_no,
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
	    	
	    	var order_design = $("#order_design").val();
	    	var logistics = $("#logistics").val();
	    	var waybill = $("#waybill").val();
    	
	    	$.ajax({
	    		type:"post",
	    		url:local+"/shop_OrderAccompanyUpdate.php",
	    		data:{
	    			"order_id" : id,
	    			"order_detail_note":order_design,
	    			"express_no":waybill,
	    			"id":logistics,
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