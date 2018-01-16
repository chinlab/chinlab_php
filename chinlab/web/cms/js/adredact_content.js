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
            
            
            //单选按钮
		    function setSelectUserNo3(radioObj){  
          
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
		    function setSelectUserNo4(radioObj){  
          
		        var radioCheck= $(radioObj).val();  
		        if("1"==radioCheck){
		        	$(".editor").css("display","block");
		            $(".article_url").attr("disabled","disabled"); 
		        }
		        else{
		            $(".editor").css("display","block");
		            $(".article_url").attr("disabled","disabled");
		        }  
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
    	//console.log(ev)
    	$("#ad_title").val(ev.title);
		$("#channel").val(ev.channel_no);
		$("#ad_place").val(ev.show_type);
		if(ev.news_content==""){
			$("#radio3").attr("checked","checked");
			$(".editor").css("display","none");
        	$(".article_url").removeAttr("disabled");
        	$("#articleurl").val(ev.news_url);
		}else{
			$("#radio4").attr("checked","checked");
			$(".editor").css("display","block");
        	$(".article_url").attr("disabled","disabled");
			$("#editor-trigger").html($('#info_content').html());
		}
		initFileInput("tcicon", "/cms_uploadimage.php?type=1", ev.news_photo.list_image[0]);
		initFileInput("tcicon2", "/cms_uploadimage.php?type=4", ev.news_photo.banner_image[0]);
		$("#tciconsrc").val(ev.news_photo.list_image[0]);
		$("#tciconsrc2").val(ev.news_photo.banner_image[0]);
	
	
		//上传图片
//		initFileInput("tcicon", "/cms_uploadimage.php", "");
	    $("#tcicon").on("fileuploaded", function (event, data, previewId, index) {
	        var code = data.response.state;      
	        if (code == "0") {
	       		layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
	            $("#tciconsrc").val(data.response.data.url);
	            $('#tcicon').fileinput('refresh');
	        } else {
	            layer.msg(data.response.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
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
	    
	    
//	    initFileInput("tcicon2", "/cms_uploadimage.php", "");
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


		//单选按钮
	    $("#radio3").click(function(){
	    	setSelectUserNo3($(this))
	    })
	    $("#radio4").click(function(){
	    	setSelectUserNo4($(this))
	    })			    
	 
        //保存
        $("#saveedit").click(function(){
        	var status = ev.status;
    		var title = $("#ad_title").val();
    		var channel_no = $("#channel").val();
    		var channel_name = $("#channel").find("option:selected").text();
    		var show_type = $("#ad_place").val();
    		var news_url = $("#adcont_url").val();
    		var news_content = encodeURIComponent(editor.$txt.html());
    		var list_image = $("#tciconsrc").val();
    		var banner_image = $("#tciconsrc2").val();
    		//console.log(news_content)
    		$.ajax({
    			type:"post",
    			url:"/cms_updatematerial.php",
    			data:{
    				"material_id": id,
    				"news_type": 0,
    				"status":status,
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
		        
		        
		        
		        
		        
		        
});
            
		
			