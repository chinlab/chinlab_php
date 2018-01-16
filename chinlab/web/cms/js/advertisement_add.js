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
//      maxImageWidth: 220,
//      maxImageHeight: 156,
//      maxFileSize: 30,
        showCaption: false, //是否显示标题
        dropZoneEnabled: true, //是否显示拖拽区域
        browseOnZoneClick: true,
        browseClass: "btn btn-primary", //按钮样式             
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
        msgFilesTooMany: "选择上传的文件数量({n}) 超过允许的最大数值{m}！",
        initialPreview: initurl
    });
}



		function setSelectUserNo1(radioObj){  
      
	        var radioCheck= $(radioObj).val();
	        if("1"!=radioCheck){
	     		$(".content_ad").addClass("isShow") 
	     		$(".start_ad").removeClass("isShow") 
	        }
	    }
	    function setSelectUserNo2(radioObj){  
      
	        var radioCheck= $(radioObj).val();  
	        if("1"!=radioCheck){
	        	$(".content_ad").removeClass("isShow")
	     		$(".start_ad").addClass("isShow") 
	        }
	    }
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
 	//上传图片
		initFileInput("tcicon", "/cms_uploadimage.php?type=1", "");
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
	    
	    
	    initFileInput("tcicon2", "/cms_uploadimage.php?type=4", "");
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
	    
	    initFileInput("tcicon3", "/cms_uploadimage.php?type=2", "");
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
	        $("#tciconsrc3").val("");
	    });
	     $('#reseticon3').click(function () {
	        $('#tcicon3').fileinput('refresh');
	        $("#tciconsrc3").val("");
	    });
	    	    
	    //单选按钮
	    
	    $("#radio1").click(function(){
	    	setSelectUserNo1($(this))
	    })
	    $("#radio2").click(function(){
	    	setSelectUserNo2($(this))
	    })
	    $("#radio3").click(function(){
	    	setSelectUserNo3($(this))
	    })
	    $("#radio4").click(function(){
	    	setSelectUserNo4($(this))
	    })
	    
	    
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
	        
	        //文字验证
//	        $("#ad_title").blur(function(){
//	        	var length = $(this).val().length
//	        	//console.log(length)
//	        	if(length>18){
//	        		$("#articleTit_err").html("您输入的广告标题大于18个字");
//	        	}else{
//	        		$("#articleTit_err").html("");
//	        	}
//	        })
	        
	        
	        
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
	    	
	    	
//	    	$("#channel").change(function(){
//	    		var val = $("#channel").val();
//	    		for(var i=0;i<length;i++){
//		        	if(e[i].channel_no==val){
//		        		for(var j=0;j<e[i].item.length;j++){
//		        			str2 += "<option value='"+ e[i].item[j]['0'] +"'>"+ e[i].item[j]['1'] +"</option>";
//		        		}
//		        		$("#infor_place").html(str2);
//		        		str2 = "<option value=''>请选择</option>";
//		        	}
//		        }
//	    	})
	    	
//	    	$("#ad_place").change(function(){
//	    		console.log($("#ad_place").val())
//	    		if($("#ad_place").val()=="0"){
//	    			$(".upload_img").addClass("isShow");
//	    		}else{
//	    			$(".upload_img").removeClass("isShow");
//	    		}
//	    	})
	    	
	    	
	    	
	    	//保存
	    	$("#saveedit").click(function(){
   		
	    		var input_obj = $("#ad_type").find("input[name='radio']")
			    //console.log(input_obj)
			    var input_length = input_obj.length;
			    var input_val = "";
			    for(var i=0;i<input_length;i++){
			    	if(input_obj[i].checked==true){
			    		input_val = input_obj[i].dataset.id;
			    	}
			    }
			    //console.log(input_val);
	    		if(input_val==1){
	    			//内容
		    		var title = $("#ad_title").val();
		    		var channel_no = $("#channel").val();
		    		var channel_name = $("#channel").find("option:selected").text();
		    		var show_type = $("#ad_place").val();
		    		var news_url = $("#adcont_url").val();
		    		var news_content = encodeURIComponent(editor.$txt.html());
		    		var list_image = $("#tciconsrc").val();
		    		var banner_image = $("#tciconsrc2").val();
    			
	    			$.ajax({
		    			type:"post",
		    			url:"/cms_material.php",
		    			data:{
		    				"news_type": 0,
		    				"status":0,
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
	    		}else if(input_val==2){
	    			//开机
	    			var title = $("#ad_title").val();
		    		var banner_image = $("#tciconsrc3").val();
		    		var news_url = $("#adstart_url").val();
		    		
		    		$.ajax({
		    			type:"post",
		    			url:"/cms_material.php",
		    			data:{
		    				"news_type": 0,
		    				"status":0,
							"title" : title,
							"channel_no": 2,
							"channel_name" : "开机广告",
							"news_url" : news_url,
							"show_type" : 0,
							"news_content": "",
							"banner_image" : banner_image,
							"list_image" : banner_image,
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
		    		
	    		}
	    		
	    		
	    	})
	    	
	        
	        
	        
	        
	        
	        
	        
	        
	        
	        
	        
	        
	        
	        
	        
	        
	        
 })