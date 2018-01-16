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
    var arr = href.replace("?","").split("&&");
    var service_type = arr[1].split('=')[1];
	//获取页面信息
	var d = $("#product_info").html();
    //console.log(d)
    var e = $.parseJSON(d);
    console.log(e)
    $("#membershipCard_number").val(e.card_no);
    $("#serviceProject").val(e.service_type_name);
    $("#customer_name").val(e.apply_user_name);
    $("#Idnumber").val(e.user_card_no);
    $("#phone_number").val(e.phone_no);
    
    //保存
    $(document).on("click", "#saveedit",function(){  	
    	var card_no = e.card_no;
    	var user_name = e.apply_user_name;
    	var user_card_no = e.user_card_no;
    	var user_phone = e.phone_no;
    	var current_order_design = $("#current_order_design").val();
    	$.ajax({
    		type:"post",
    		url:"/shop/selectservice.php",
    		data:{
    			"card_no" : card_no,
    			"service_type":service_type,
    			"user_name":user_name,
    			"user_card_no":user_card_no,
    			"user_phone":user_phone,
    			"current_order_design":current_order_design,
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
	});
    $(document).on('click','#reset',function(){
    	var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
		parent.layer.close(index);
    })
    
    
    
    
    
    
    
})