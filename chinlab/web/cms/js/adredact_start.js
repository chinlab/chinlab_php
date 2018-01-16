function initFileInput(ctrlName, uploadUrl, initurl) {
	    var control = $('#' + ctrlName);
	    if (initurl != "") {
	        initurl = '<img src="' + initurl + '" width="200" height="200" alt="图标"/>'
	    }
	    control.fileinput({
	        language: 'zh', //设置语言
	        uploadUrl: uploadUrl, //上传的地址
	        showClose: true,
	        showUpload: false, //是否显示上传按钮
	        allowedFileExtensions: ['jpg', 'png', 'gif'], //接收的文件后缀
	        maxFileCount: 1,//文件上传最大限度
	        showCaption: false, //是否显示标题
	        dropZoneEnabled: true, //是否显示拖拽区域
	        browseOnZoneClick: true,
	        browseClass: "btn btn-primary", //按钮样式             
	        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
	        msgFilesTooMany: "选择上传的文件数量({n}) 超过允许的最大数值{m}！",
	        initialPreview: initurl
	    });
}

$(document).ready(function(){
	
	
	var href = window.location.search;    	
	var id = href.replace("?","").split("=")[1];	    	
	//获取编写信息
	var ev = detail_info;
	//console.log(ev)
	$("#ad_title").val(ev.title);
	$("#adstart_url").val(ev.news_url);
	initFileInput("tcicon3", "/cms_uploadimage.php?type=2", ev.news_photo.banner_image[0]);
	$("#tciconsrc3").val(ev.news_photo.banner_image[0]);
	
	
	
 	//上传图片
//	initFileInput("tcicon3", "/cms_uploadimage.php", "");
    $("#tcicon3").on("fileuploaded", function (event, data, previewId, index) {
        var code = data.response.state;      
        if (code == "0") {
       		layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
            $("#tciconsrc3").val(data.response.data.url);
            $('#tcicon3').fileinput('refresh');
        } else {
            layer.msg(data.response.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
            $('#tcicon3').fileinput('reset');
        }
    });
    $('#tcicon3').on('filecleared', function (event) {
        $("#tciconsrc").val("");
    });
     $('#reseticon3').click(function () {
        $('#tcicon3').fileinput('refresh');
        $("#tciconsrc3").val("");
    });
	
	
	
	//保存
	$("#saveedit").click(function(){
		var status = ev.status;
		var channel_no = ev.channel_no;
		var channel_name = ev.channel_name;
		var show_type = ev.show_type;
		var news_content = ev.news_content;
		var list_image = ev.news_photo.list_image[0];
		var title = $("#ad_title").val();
		var news_url = $("#adstart_url").val();
		var banner_image = $("#tciconsrc3").val();
		//console.log(news_content)
		$.ajax({
			type:"post",
			url:"/cms_updatematerial.php",
			data:{
				"material_id": id,
				"news_type": 0,
				"status": status,
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