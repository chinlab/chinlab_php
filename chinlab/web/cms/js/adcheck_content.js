


$(document).ready(function(){
	
	//获取频道下拉菜单内容
    var d = $("#info_channel").html();
    //console.log(d)
    var e = $.parseJSON(d);
    //console.log(e)
    var length = e.length ;
    var str1 = "<option value=''>请选择</option>";
    var str2 = "<option value=''>请选择</option>";
    for(var i=0;i<length;i++){
    	str1 += "<option value='"+ e[i].channel_no +"'>"+ e[i].channel_name +"</option>";
    }
	$("#channel").html(str1);
	
	
	
 	var href = window.location.search;    	
	var id = href.replace("?","").split("=")[1];	    	
	//获取编写信息
	var ev = detail_info;
	//console.log(ev)
	$("#ad_title").val(ev.title);
	$("#channel").val(ev.channel_no);
	$("#ad_place").val(ev.show_type);
	var news_type = ev.news_type;
	var status = ev.status;
	var news_content = ev.news_content;
	var list_image = ev.news_photo.list_image[0];
	var banner_image = ev.news_photo.banner_image[0];
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
		var channel_no = $("#channel").val();
		var channel_name = $("#channel").find("option:selected").text();
		var show_type = $("#ad_place").val();
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
		var channel_no = $("#channel").val();
		var channel_name = $("#channel").find("option:selected").text();
		var show_type = $("#ad_place").val();
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