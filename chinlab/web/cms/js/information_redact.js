	var goods_image_index = [];
	var goods_image_info = [];
	var text_img = [];
	var commit_goods_image_info = [];
	var old_goods_img = [];
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
	
	function initFileInput1(ctrlName, uploadUrl, initurl) {
	    var control1 = $('#' + ctrlName);
	//  if (initurl != "") {
	//      initurl = '<img src="' + initurl + '" width="200" height="200" alt="图标"/>'
	//  }
		//console.log(initurl)
		if(initurl!=""){
			var preImage = [];
			$.each(initurl, function(k, v){
				//console.log(k)
				//console.log(v)
				preImage[preImage.length] = '<img src="' + v + '" width="200" height="200" alt="图标"/>'
			});
		}
		
	    control1.fileinput({
	        language: 'zh', //设置语言
	        uploadUrl: uploadUrl, //上传的地址
	        dropZoneEnabled: true, //是否显示拖拽区域
	        browseOnZoneClick: true,
	        showUpload: false, //是否显示上传按钮
	        allowedFileExtensions: ['jpg', 'png', 'gif'], //接收的文件后缀
	        initialPreview: preImage,
	        overwriteInitial: true,
	        maxFileSize: 1000,
	        maxFilesNum: 10,
	        maxFileCount: 3,//文件上传最大限度
	        msgFilesTooMany: "选择上传的文件数量({n}) 超过允许的最大数值{m}！",
	    });
	}
	
	
	function setSelectUserNo1(radioObj){  
          
        var radioCheck= $(radioObj).val();
        if("1"==radioCheck){
            $(".editor").css("display","none");
            $(".article_url").removeAttr("disabled");
        }
        else{ 
            $(".editor").css("display","none");
            $(".article_url").removeAttr("disabled");
        }  
    }
    function setSelectUserNo2(radioObj){  
  
        var radioCheck= $(radioObj).val();  
        if("1"==radioCheck){
        	$(".editor").css("display","block");
            $(".article_url").attr("disabled","disabled"); 
        }
        else{
            $(".editor").css("display","block");
//          $(".article_url").val("");
            $(".article_url").attr("disabled","disabled");  
        }  
    }
    
    function initTciconUpload() {
		$("#tcicon").on("fileuploaded", function (event, data, previewId, index) {
			console.log("ddddddddddddddddddddd");
		var code = data.response.state;  
		//删除编辑器图片
		$.each(goods_image_index, function (k, v) {
			if (v.indexOf("editor_")>=0) {
				goods_image_info[v] = "";
		    	goods_image_index[k] = "";
			}
		});
		checkImageCount();
		if (code == "0") {
			layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
		    goods_image_index[goods_image_index.length] = previewId;
			goods_image_info[previewId] = data.response.data.url;
			//console.log("ddddddddddddddddddddd");
			showImagesView();
		} else {
		    layer.msg(data.response.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
		//		            $('#tcicon').fileinput('refresh');
		    }
		});
		$('#tcicon').on('filecleared', function (event) {
		$.each(goods_image_index, function (k, v) {
			//console.log(k)
			//console.log(v)
		    goods_image_info[v] = "";
		    goods_image_index[k] = "";
		    //console.log(goods_image_index);
		    //console.log(goods_image_info);
		    });
		});
		$('#tcicon').on('filesuccessremove', function (event, id) {
			$.each(goods_image_index, function (k, v) {
				//console.log(k)
				//console.log(v)
				//console.log(id)
			    if (v == id) {
			        goods_image_info[v] = "";
			        goods_image_index[k] = "";
			    }
			});
		});
	}
	
	function checkImageCount(){
		//检测图片张数
		var image_length = 0;
		$.each(goods_image_index, function (k, v) {
			if (goods_image_index[k] != "") {
				image_length = image_length + 1;
			}
		});
		if (image_length >= 3){
			goods_image_index = [];
	    	goods_image_info = [];
		}
	}
	//强制显示图片
	function showImagesView() {
			//检测图片张数
		var image_length = 0;
		var show_image = [];
		var preview_image = [];
		$.each(goods_image_index, function (k, v) {
			//console.log(goods_image_index[k]);
			if (goods_image_index[k] != "") {	
				show_image[image_length] = goods_image_info[v];
				image_length = image_length + 1;
			}
		});
		if (image_length <= 2){
			preview_image[0] = show_image[0];
		} else {
			preview_image = show_image;
		}
		//console.log(preview_image);
		var str = '<label for="tcicon">上传缩略图(尺寸:220*156)</label><a id="reseticon">一键导入</a><input id="tcicon" type="file" name="icon" class="form-control" multiple data-min-file-count="1"><input id="tciconsrc" type="hidden" name="icon" class="form-control">';
	    $("#img_list").html(str)
		initFileInput1("tcicon", "/cms_uploadimage.php?type=1", preview_image);
		initTciconUpload();
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
		
			
		    var href = window.location.search;    	
    		var id = href.replace("?","").split("=")[1];	    	
	    	//获取编写信息
		    var ev = detail_info;
	    	//console.log(ev)
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
			
			if(ev.news_content==""){
				$("#radio1").attr("checked","checked");
				$(".editor").css("display","none");
            	$(".article_url").removeAttr("disabled");
            	$("#articleurl").val(ev.news_url);
			}else{
				$("#radio2").attr("checked","checked");
				$(".editor").css("display","block");
            	$(".article_url").attr("disabled","disabled");
				$("#editor-trigger").html($('#info_content').html());
			}
			
			old_goods_img = ev.news_photo.list_image;
			
			initFileInput1("tcicon", "/cms_uploadimage.php?type=1", ev.news_photo.list_image);
			initTciconUpload();
			initFileInput("tcicon2", "/cms_uploadimage.php?type=4", ev.news_photo.banner_image[0]);
			$("#tciconsrc").val(ev.news_photo.list_image[0]);
			$("#tciconsrc2").val(ev.news_photo.banner_image[0]);
	    	
    		    	
	    	//banner图片上传是否显示	    	
	    	
	    	$("#infor_place").change(function(){
	    		//console.log($("#infor_place").val())
	    		if($("#infor_place").val()=="0"){
	    			$(".upload_img").addClass("isShow");
	    		}else{
	    			$(".upload_img").removeClass("isShow");
	    		}
	    	})
		    
		    
		    
		    //图片上传
 			//initFileInput("tcicon", "/cms_uploadimage.php", "");
//		    $("#tcicon").on("fileuploaded", function (event, data, previewId, index) {
//		        var code = data.response.state;      
//		        if (code == "0") {
//		       		layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
//		            $("#tciconsrc").val(data.response.data.url);
//		            $('#tcicon').fileinput('refresh');
//		        } else {
//		            layer.msg(data.response.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
//		            $('#tcicon').fileinput('reset');
//		        }
//		    });
//		    $('#tcicon').on('filecleared', function (event) {
//		        $("#tciconsrc").val("");
//		    });
//		     $('#reseticon').click(function () {
//		        $('#tcicon').fileinput('refresh');
//		        $("#tciconsrc").val("");
//		    });
		    
		    //initFileInput("tcicon2", "/cms_uploadimage.php", "");
		    $("#tcicon2").on("fileuploaded", function (event, data, previewId, index) {
		        var code = data.response.state;      
		        if (code == "0") {
		       		layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
		            $("#tciconsrc2").val(data.response.data.url);
		            $('#tcicon2').fileinput('refresh');
		        } else {
		            layer.msg(data.response.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
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
		    
		    
		    
		    
		    
		    //单选按钮切换
		    $("#radio1").click(function(){
		    	setSelectUserNo1($(this))
		    })
		    $("#radio2").click(function(){
		    	setSelectUserNo2($(this))
		    })
		    	        
	        
	        
	        $(document).on('click', '#reseticon', function () {
	  	
	  			goods_image_index = [];
    			goods_image_info = [];
		    	$("#editor-trigger img").each(function(k, v){
		    		//console.log($(this).attr("src"));
		    		if (k <= 2 ){
			    		goods_image_index[goods_image_index.length] = 'editor_' + k;
	            		goods_image_info['editor_' + k] = $(this).attr("src");
		    		}
		    		
		    	});   	
		    	showImagesView();
		    });
	         
	        
	        //保存
	        $("#saveedit").click(function(){
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
	    		
	    		var fin_goods_image = [];
	    	
		    	$.each(goods_image_index, function (k, v) {
					if (v != "") {
						commit_goods_image_info[commit_goods_image_info.length] = goods_image_info[v];
					}
				});
		    	if(commit_goods_image_info.length==0){
		    		fin_goods_image = old_goods_img;
		    	}else{
		    		fin_goods_image = commit_goods_image_info;
		    	}
	    		
	    		
	    		
	    		
	    		$.ajax({
	    			type:"post",
	    			url:"/cms_updatematerial.php",
	    			data:{
	    				"material_id": id,
	    				"news_type": 1,
	    				"status":0,
						"title" : title,
						"channel_no": channel_no,
						"channel_name" : channel_name,
						"show_type" : show_type,
						"author": author,
						"info_source" : info_source,
						"news_url" : news_url,
						"news_content": news_content,
						"list_image" : JSON.stringify(fin_goods_image),
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






