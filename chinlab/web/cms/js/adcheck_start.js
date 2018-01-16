 
 
 
 
 $(document).ready(function(){
 	
 	var href = window.location.search;    	
	var id = href.replace("?","").split("=")[1];	    	
	//获取编写信息
	 var ev = detail_info;
	console.log(ev)
	$("#ad_title").val(ev.title);
	$("#channel_no").val(ev.title);
	var channel_no = ev.channel_no;
	var channel_name = ev.channel_name;
	var show_type = ev.show_type;
	var news_type = ev.news_type;
	var status = ev.status;
	var news_content = ev.news_content;
	var list_image = ev.news_photo.list_image[0];
	var banner_image = ev.news_photo.banner_image[0];
	console.log(banner_image)
	var news_url = ev.news_url;
	
	
	//预览
	var layerpreview = "";
   	$(document).on("click", "#pvw",function() {
		new_url = 'http://qr.liantu.com/api.php?text='+encodeURIComponent(news_url);
		
		window.open(new_url,'newwindow', 'toolbar=no,scrollbars=yes,resizable=no,top=300,left=450,width=400,height=400');
	});
	
	
	//审核通过
    $("#pass").click(function(){
		var title = $("#ad_title").val();
		$.ajax({
			type:"post",
			url:"/cms_updatematerial.php",
			data:{
				"material_id": id,
				"news_type": news_type,
				"status":2,
				"title" : title,
				"channel_no": channel_no,
				"channel_name" : channel_name,
				"show_type" : show_type,
				"news_url" : news_url,
				"news_content": news_content,
				"list_image" : list_image,
				"banner_image" : banner_image
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
    //审核不通过
	$("#no_pass").click(function(){
		var title = $("#ad_title").val();
		//console.log(news_content)
		$.ajax({
			type:"post",
			url:"/cms_updatematerial.php",
			data:{
				"material_id": id,
				"news_type": news_type,
				"status":3,
				"title" : title,
				"channel_no": channel_no,
				"channel_name" : channel_name,
				"show_type" : show_type,
				"news_url" : news_url,
				"news_content": news_content,
				"list_image" : list_image,
				"banner_image" : banner_image
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
 	
 })