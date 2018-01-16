 function initFileInput(ctrlName, uploadUrl, initurl) {
    if (typeof(initurl) == "string"){ 
    if (initurl != "") {
        initurl = '<img src="' + initurl + '" width="200" height="200" alt="图标"/>'
    }
	$("#" + ctrlName).html(initurl);
	} else {
		var str = "";
		$.each(initurl, function(k, v){
			if (v != "") {
        		str += '<img src="' + v + '" width="200" height="200" alt="图标"/>'
    		}
	
		});
		$("#" + ctrlName).html(str);
	}
	 /*
    control.fileinput({
        language: 'zh', //设置语言
        uploadUrl: uploadUrl, //上传的地址
        showClose: false,
        showUpload: false, //是否显示上传按钮
        allowedFileExtensions: ['jpg', 'png', 'gif'], //接收的文件后缀
        maxFileCount: 1,//文件上传最大限度
        showCaption: false, //是否显示标题
        dropZoneEnabled: false, //是否显示拖拽区域
        browseOnZoneClick: false,
        browseClass: "btn btn-primary", //按钮样式             
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
        msgFilesTooMany: "选择上传的文件数量({n}) 超过允许的最大数值{m}！",
        initialPreview: initurl
    });
    */
}

            

$(document).ready(function(){
		
			//富文本编辑器
		    // 阻止输出log
	        wangEditor.config.printLog = false;
	
	        var editor = new wangEditor('editor-trigger');
	
	        // 上传图片
	        editor.config.uploadImgUrl = '/cms_uploadimagetext.php';
	        editor.config.uploadParams = {
	            // token1: 'abcde',
	            // token2: '12345'
	        };
	        editor.config.uploadHeaders = {
	            // 'Accept' : 'text/x-json'
	        }
	         editor.config.uploadImgFileName = 'icon';
	
	         //  隐藏网络图片
	           editor.config.hideLinkImg = true;
	
	        // 只粘贴纯文本
	         editor.config.pasteText = true;
	
	        // 跨域上传
	        // editor.config.uploadImgUrl = 'http://localhost:8012/upload';
	
	        // 第三方上传
	        // editor.config.customUpload = true;
        
	        editor.create();
			
		
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
	    	//console.log(ev);
	    	$("#articleTit").val(ev.title);
			$("#channel").val(ev.channel_no);
			var val_start = $("#channel").val();
    		for(var i=0;i<length;i++){
	        	if(e[i].channel_no==val_start){
	        		for(var j=0;j<e[i].item.length;j++){
	        			str2 += "<option value='"+ e[i].item[j]['0'] +"'>"+ e[i].item[j]['1'] +"</option>";
	        		}
	        		$("#infor_place").html(str2);
	        		str2 = "<option value=''>请选择</option>";
	        	}
	        }
			$("#infor_place").val(ev.show_type);
			if($("#infor_place").val()==0){
	    		$(".upload_img").addClass("isShow");
	    	}else{
	    		$(".upload_img").removeClass("isShow");
	    	}
			$("#author").val(ev.author);
			$("#source").val(ev.info_source);
			$("#articleurl").val(ev.news_url);
			$("#editor-trigger").html($('#info_content').html());
			initFileInput("preview_first", "/cms_uploadimage.php?type=1", ev.news_photo.list_image);
			initFileInput("preview_two", "/cms_uploadimage.php?type=4", ev.news_photo.banner_image[0]);
			$("#tciconsrc").val(ev.news_photo.list_image[0]);
			$("#tciconsrc2").val(ev.news_photo.banner_image[0]);
	    	var news_url = ev.news_url;
	    	//console.log(news_url)
    		
    		
    		$("#channel").change(function(){
	    		var val = $("#channel").val();
	    		for(var i=0;i<length;i++){
		        	if(e[i].channel_no==val){
		        		for(var j=0;j<e[i].item.length;j++){
		        			str2 += "<option value='"+ e[i].item[j]['0'] +"'>"+ e[i].item[j]['1'] +"</option>";
		        		}
		        		$("#infor_place").html(str2);
		        		str2 = "<option value=''>请选择</option>";
		        	}
		        }
	    	})
    		
    		
    		
    		
	    	//banner图片上传是否显示	    	
	    	
	    	$("#infor_place").change(function(){
	    		//console.log($("#infor_place").val())
	    		if($("#infor_place").val()=="0"){
	    			$(".upload_img").addClass("isShow");
	    		}else{
	    			$(".upload_img").removeClass("isShow");
	    		}
	    	})
		    
		    
		    
//		    initFileInput("tcicon", "/news_uploadicon.html", "");
			/*
		    $("#tcicon").on("fileuploaded", function (event, data, previewId, index) {
		        var code = data.response.code;
		        if (code == "0") {
		        	layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true});
		            $("#tciconsrc").val(data.response.url);
		            $('#tcicon').fileinput('refresh');
		        } else {
		            layer.msg(data.response.message, {icon: 5, closeBtn: 1, shadeClose: true});
		            $('#tcicon').fileinput('reset');
		        }
		    });
		    $('#tcicon').on('filecleared', function (event) {
		        $("#tciconsrc").val("");
		    });
		    $('#reseticon').click(function () {
		        $('#tcicon').fileinput('refresh');
		        $("#tciconsrc").val("");
		    });
		    */
		    
//		    initFileInput("tcicon2", "/news_uploadicon.html", "");
			/*
		    $("#tcicon2").on("fileuploaded", function (event, data, previewId, index) {
		        var code = data.response.code;
		        if (code == "0") {
		        	layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true});
		            $("#tciconsrc2").val(data.response.url);
		            $('#tcicon2').fileinput('refresh');
		        } else {
		            layer.msg(data.response.message, {icon: 5, closeBtn: 1, shadeClose: true});
		            $('#tcicon2').fileinput('reset');
		        }
		    });
		    $('#tcicon2').on('filecleared', function (event) {
		        $("#tciconsrc2").val("");
		    });
		     $('#reseticon2').click(function () {
		        $('#tcicon2').fileinput('refresh');
		        $("#tciconsrc2").val("");
		    });
		    */
		    
		    
		    
		    //预览
			var layerpreview = "";
		   	$(document).on("click", "#pvw",function() {
				new_url = 'http://qr.liantu.com/api.php?text='+encodeURIComponent(news_url);

				window.open(new_url,'newwindow', 'toolbar=no,scrollbars=yes,resizable=no,top=300,left=450,width=400,height=400');
				/*
				console.log(news_url)
				$(document.body).css({
					"overflow-x": "hidden",
					"overflow-y": "hidden"
				});
				powidth = $(window).width() - 50;
				poheight = $(window).height() - 50;
				layerpreview = layer.open({
					type: 2,
					title: "预览",
					area: ['375px', '667px'],
					fix: false,
					shadeClose: true,
					maxmin: true,
					content: news_url,
					end: function() {
						$(document.body).css({
							"overflow-x": "auto",
							"overflow-y": "auto"
						});
					}
				});
				*/
			});
			
			 //审核通过
	        $("#pass").click(function(){
	    		var title = $("#articleTit").val();
	    		var channel_no = $("#channel").val();
	    		var channel_name = $("#channel").find("option:selected").text();
	    		var show_type = $("#infor_place").val();
	    		var author = $("#author").val();
	    		var info_source = $("#source").val();
	    		var news_url = $("#articleurl").val();
	    		var news_content = encodeURIComponent(editor.$txt.html());
	    		var list_image = $("#tciconsrc").val();
	    		//console.log(list_image)
	    		var banner_image = $("#tciconsrc2").val();
	    		//console.log(banner_image)
	    		//console.log(news_content)
	    		$.ajax({
	    			type:"post",
	    			url:"/cms_updatematerial.php",
	    			data:{
	    				"material_id": id,
	    				"news_type": 1,
	    				"status":2,
						"title" : title,
						"channel_no": channel_no,
						"channel_name" : channel_name,
						"show_type" : show_type,
						"author": author,
						"info_source" : info_source,
						"news_url" : news_url,
						"news_content": news_content,
						"list_image" : JSON.stringify(ev.news_photo.list_image),
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
	    		var title = $("#articleTit").val();
	    		var channel_no = $("#channel").val();
	    		var channel_name = $("#channel").find("option:selected").text();
	    		var show_type = $("#infor_place").val();
	    		var author = $("#author").val();
	    		var info_source = $("#source").val();
	    		var news_url = $("#articleurl").val();
	    		var news_content = encodeURIComponent(editor.$txt.html());
	    		var list_image = $("#tciconsrc").val();
	    		var banner_image = $("#tciconsrc2").val();
	    		//console.log(news_content)
	    		
	    		$.ajax({
	    			type:"post",
	    			url:"/cms_updatematerial.php",
	    			data:{
	    				"material_id": id,
	    				"news_type": 1,
	    				"status":3,
						"title" : title,
						"channel_no": channel_no,
						"channel_name" : channel_name,
						"show_type" : show_type,
						"author": author,
						"info_source" : info_source,
						"news_url" : news_url,
						"news_content": news_content,
						"list_image" : JSON.stringify(ev.news_photo.list_image),
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