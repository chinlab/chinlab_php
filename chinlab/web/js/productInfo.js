function initFileInput2(ctrlName, uploadUrl, initurl) {
    var control2 = $('#' + ctrlName);
    if (initurl != "") {
        initurl = '<img src="' + initurl + '" width="200" height="200" alt="图标"/>'
    }
    control2.fileinput({
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
function initFileInput3(ctrlName, uploadUrl, initurl) {
    var control3 = $('#' + ctrlName);
    if (initurl != "") {
        initurl = '<img src="' + initurl + '" width="200" height="200" alt="图标"/>'
    }
    control3.fileinput({
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
	var preImage = [];
	$.each(initurl, function(k, v){
		preImage[preImage.length] = '<img src="' + v + '" width="200" height="200" alt="图标"/>'
	});
	//console.log(preImage)
    control1.fileinput({
        language: 'zh', //设置语言
        uploadUrl: uploadUrl, //上传的地址
        dropZoneEnabled: true, //是否显示拖拽区域
        browseOnZoneClick: true,
        allowedFileExtensions: ['jpg', 'png', 'gif'], //接收的文件后缀
        initialPreview: preImage,
        overwriteInitial: true,
        maxFileSize: 1000,
        maxFilesNum: 10,
        msgFilesTooMany: "选择上传的文件数量({n}) 超过允许的最大数值{m}！",
    });
}

function mergeArray(arr1, arr2){
	function findItem(id, arrtest) {
	    for(var i = 0; i < arrtest.length; i++) {
	        if (id == arrtest[i].id) {
	            return i + 1;
	        }
	    }
	    return 0;
	}
	    //console.log(arr2.length);
	var i;
	for(i = 0; i< arr2.length; i++) {
	    locationn = findItem(arr2[i].id, arr1);
	    //console.log(locationn);
	    if (locationn > 0) {
	        arr1[locationn - 1] = arr2[i];
	    } else {
	        arr1[arr1.length] = arr2[i];
	    }
	}
	return arr1;
}





function del(arr){
	$(".del").click(function(){
		//console.log(arr)
		//console.log($(this).attr("data-id"))
		var inx = $(this).attr("data-id");
		arr.splice(inx,1);
		//console.log(arr)
		draw(arr);
	})
}
function draw(a){
	var str1 = "";
	var data_number = "";
	//console.log(a)
	for(var i=0;i<a.length;i++){
		if(a[i].count==9999){
			data_number = "不限次";
		}else{
			data_number = a[i].count;
		}
		
		str1 += "<tr><td>"+ a[i].desc +"</td><td>"+ data_number +"</td><td><a class='del' data-id='"+ i +"'>删除</a></td></tr>";
	}
	$("#datatable").find('tbody').html(str1);
	
	del(a);
}


$(document).ready(function(){
	
	
		var goods_image_index = [];
	    var goods_image_info = [];
	    var commit_goods_image_info = [];
	
	
	
		var href = window.location.search;    	
    	var id = href.replace("?","").split("=")[1];
		//获取列表信息
		var d1 = $("#info_goods").html();
		//console.log(d1)
		var e1 = $.parseJSON(d1);
		var list = e1.serviceList;
		//console.log(e1)
		if(e1.is_onsalt==1){
			$("#add_list").addClass('isHide');
			
		}else{
			$("#add_list").addClass('isShow');
			
		}
		
		
		
		var old_goods_img = e1.goods_image;
		//console.log(old_goods_img)
		
		
		
		var d2 = $("#info_admin").html();
		//console.log(d)
		var e2 = $.parseJSON(d2);
		//console.log(e2)
		if(e2==1){
			$("#is_onsalt").removeAttr('disabled');
			$("#sales_num").removeAttr('disabled');
		}else{
			$("#is_onsalt").attr('disabled','disabled');
			$("#sales_num").attr('disabled','disabled');
		}
		
		$("#product_name").val(e1.goods_name);
		$("#product_type").val(e1.goods_type);
		$("#card_type").val(e1.goods_card_type);
		$("#old_price").val(e1.original_price);
		$("#offer_price").val(e1.now_price);
		$("#effective_date").val(e1.goods_expire_time);
		$("#serve_num").val(e1.goods_service_limit);
		$("#carriage").val(e1.freight_price);
		$("#sales_num").val(e1.goods_amount);
		$("#is_onsalt").val(e1.is_onsalt);
		initFileInput2("tcicon2", "/shop_goodsimage.php?type=1", e1.goods_big_image);
		initFileInput3("tcicon3", "/shop_goodsimage.php?type=2", e1.goods_small_image);
		initFileInput1("tcicon", "/shop_goodsimage.php?type=3",old_goods_img);
		var arr = e1.goods_service;
		
		//console.log(arr)
		draw(arr);
		
		if(e1.is_onsalt==1){	
			$(".del").addClass('isHide');
		}else{
			$(".del").addClass('isShow');
		}
		
		
		//上传图片
		
	    $("#tcicon").on("fileuploaded", function (event, data, previewId, index) {
	        var code = data.response.state;      
	        if (code == "0") {
	       		layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
	            goods_image_index[goods_image_index.length] = previewId;
	            goods_image_info[previewId] = data.response.data.url;
	            //console.log(goods_image_index);
	            //console.log(goods_image_info);
//	            $('#tcicon').fileinput('refresh');
	        } else {
	            layer.msg(data.response.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
	            $('#tcicon').fileinput('reset');
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
	    
	    
	    //initFileInput("tcicon2", "/cms_uploadimage.php?type=4", "");
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
	    
	    //initFileInput2("tcicon3", "/cms_uploadimage.php?type=4", "");
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
	    
	    
	   var arr_registe = [];
	    //添加挂号
	    var layerregiste = "";
		$(document).on("click", "#add_registe",function() {
				
				var content = $("#registe_list").html();
				$(document.body).css({
					"overflow-x": "hidden",
					"overflow-y": "hidden"
				});
				powidth = $(window).width() - 50;
				poheight = $(window).height() - 50;
				
				layerregiste = layer.open({
					type: 1,
					title: "服务详情",
					fix: false,
					shadeClose: false,
					maxmin: true,
					area: [powidth + 'px', poheight + 'px'],
					content: content,
					end: function() {
						//console.log(arr);
						//console.log(arr_registe);
						mergeArray(arr,arr_registe);
						arr_registe = [];
						//console.log(arr)
						draw(arr);
				
						$(document.body).css({
							"overflow-x": "auto",
							"overflow-y": "auto"
						});
					}
				});
				var d = $("#info_service").html();
				//console.log(d)
				var e = $.parseJSON(d);
				//console.log(e)
				var str = "";
			
			    for(var i=0;i<e.length;i++){
			    	
//			    	if(e[i].back_type==1){
			    		if(e[i].is_int==1){
				    		str += "<tr><td><input type='checkbox' data-id='"+ e[i].val +"' data-type='"+ e[i].is_int +"' name='check'/></td><td class='desc'>"+ e[i].desc +"</td><td class='num'><input type='number' /></td></tr>";
				    	}else if(e[i].is_int==0){
				    		str += "<tr><td><input type='checkbox' data-id='"+ e[i].val +"' data-type='"+ e[i].is_int +"' name='check'/></td><td class='desc'>"+ e[i].desc +"</td><td class='num'></td></tr>";
				    	}
//			    	}		    	
			    	
			    }
//				console.log(str)
			    $("#datatable_1").find('tbody').html(str);
								
				
				$("#save_registe").click(function(){
					
					$("#datatable_1 tbody input:checked").each(function(k,v){
						//console.log(v)
						var obj = {};
						var is_int = $(v).attr("data-type");
						//console.log(is_int)
						var data_id = $(v).attr("data-id");
						var data_desc = $(v).parent().siblings('.desc').text();
						
						//console.log($(v).attr("data-id"))
						obj.id = data_id;
			
						//console.log($(v).parent().siblings('.desc').text())
						obj.desc = data_desc;
						
						//console.log($(v).parent().siblings('.num').find('input').val())
						if(is_int==0){
							obj.count = 9999;
						}else if(is_int==1){
							var data_num = $(v).parent().siblings('.num').find('input').val()
							obj.count = data_num;
						}
						//console.log(obj)
						arr_registe.push(obj);		
						
						//console.log(arr)
					})
					
					var is_count1 = 0;
					for(var i = 0;i<arr_registe.length;i++){
						if(arr_registe[i].count==0){
							is_count1++;
						}
					}
					if(is_count1>0){
						layer.msg("请添加数量", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
						arr_registe = [];
					}else{
						setTimeout("layer.closeAll()", 1000);
					}
					
					
					
					
					
					//console.log(arr)
					//setTimeout("layer.closeAll()", 1000);				
				})
			
			});
	    
	    
	    
	    
//	    var arr_exam = [];
//		    //添加体检
//		    var layerexam = "";
//			$(document).on("click", "#add_exam",function() {
//					
//					var content = $("#exam_list").html();
//					$(document.body).css({
//						"overflow-x": "hidden",
//						"overflow-y": "hidden"
//					});
//					powidth = $(window).width() - 50;
//					poheight = $(window).height() - 50;
//					
//					layerexam = layer.open({
//						type: 1,
//						title: "服务详情",
//						fix: false,
//						shadeClose: false,
//						maxmin: true,
//						area: [powidth + 'px', poheight + 'px'],
//						content: content,
//						end: function() {
//							//console.log(arr);
//							//console.log(arr_exam);
//							mergeArray(arr,arr_exam);
//							arr_exam = [];
//							//console.log(arr)
//							draw(arr);
//					
//							$(document.body).css({
//								"overflow-x": "auto",
//								"overflow-y": "auto"
//							});
//						}
//					});
//					var d = $("#info_service").html();
//					//console.log(d)
//					var e = $.parseJSON(d);
//					//console.log(e)
//					var str_exam = "";
//					var examinfo = e[28];
//					//console.log(examinfo)
//				    for(var i=0;i<e.length;i++){
//				    	if(e[i].back_type==2){	
//				    		for(var j=0;j<examinfo.length;j++){
//				    			str_exam += "<tr><td><input type='checkbox' data-id='"+ examinfo[j].val +"' name='check'/></td><td class='desc'>"+ examinfo[j].name +"<a class='examinfo' data-id='"+ examinfo[j].val +"' href='javascript:void(0)'>详情</a></td></tr>";
//				    		}	
//				    	}		    	
//				    	
//				    }
//				    $("#datatable_2").find('tbody').html(str_exam);
//				    
//				    
//					//详情
//					var layerinfo = "";
//					$(document).on("click", ".examinfo",function() {
//						var trid = $(this).attr("data-id");
//						$(document.body).css({
//							"overflow-x": "hidden",
//							"overflow-y": "hidden"
//						});
////						powidth = $(window).width() - 50;
////						poheight = $(window).height() - 50;
//						
//						layerinfo = layer.open({
//							type: 2,
//							title: "体检详情",
//							fix: false,
//							shadeClose: false,
//							maxmin: true,
//							area: ['375px','667px'],
//							content: "/userApi/goods_testpage_"+ trid +"_1.html",
//							end: function() {
//								$(document.body).css({
//									"overflow-x": "auto",
//									"overflow-y": "auto"
//								});
//							}
//						});
//					})
//					
//					
//					
//					
//					
//					
//					
//					$("#save_exam").click(function(){
//						var arr_ids = [];
//						var desc = "";
//						$("#datatable_2 tbody input:checked").each(function(k,v){
//							//console.log(v)
//							var obj = {};
//							//var is_int = $(v).attr("data-type");
//							//console.log(is_int)
//							var data_id = $(v).attr("data-id");
//							var data_desc = $(v).parent().siblings('.desc').text();
//							
//							//console.log($(v).attr("data-id"))
//							obj.id = 99;
//							arr_ids.push(data_id);
//							obj.items = arr_ids;
//							desc += data_desc+";";
//							//console.log(desc);
//							//console.log($(v).parent().siblings('.desc').text())
//							obj.desc = desc;
//							
//							//console.log($(v).parent().siblings('.num').find('input').val())
//							
//							obj.count = $("#sum").val();
//							//console.log(obj)
//							arr_exam.push(obj);		
//							
//						})
//						
//						var is_count2 = 0;
//						for(var i = 0;i<arr_exam.length;i++){
//							if(arr_exam[i].count==0){
//								is_count2++;
//							}
//						}
//						if(is_count2>0){
//							layer.msg("请添加数量", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
//							arr_exam = [];
//						}else{
//							setTimeout("layer.closeAll()", 1000);
//						}
//						
//						
//						//console.log(arr)
//						//setTimeout("layer.closeAll()", 1000);				
//					})
//				
//				});
//				
//				
//				
//			var arr_insurance = [];
//		    //添加保险
//		    var layerinsurance = "";
//			$(document).on("click", "#add_insurance",function() {
//					
//					var content = $("#insurance_list").html();
//					$(document.body).css({
//						"overflow-x": "hidden",
//						"overflow-y": "hidden"
//					});
//					powidth = $(window).width() - 50;
//					poheight = $(window).height() - 50;
//					
//					layerinsurance = layer.open({
//						type: 1,
//						title: "服务详情",
//						fix: false,
//						shadeClose: false,
//						maxmin: true,
//						area: [powidth + 'px', poheight + 'px'],
//						content: content,
//						end: function() {
//							//console.log(arr);
//							//console.log(arr_insurance);
//							mergeArray(arr,arr_insurance);
//							arr_insurance = [];
//							//console.log(arr)
//							draw(arr);
//					
//							$(document.body).css({
//								"overflow-x": "auto",
//								"overflow-y": "auto"
//							});
//						}
//					});
//					var d = $("#info_service").html();
//					//console.log(d)
//					var e = $.parseJSON(d);
//					//console.log(e)
//					var str_insurance = "";
//				
//				    for(var i=0;i<e.length;i++){
//				    	
//				    	if(e[i].back_type==3){
//				    		if(e[i].is_int==1){
//					    		str_insurance += "<tr><td><input type='checkbox' data-id='"+ e[i].val +"' data-type='"+ e[i].is_int +"' name='check'/></td><td class='desc'>"+ e[i].desc +"</td><td class='num'><input type='number' /></td></tr>";
//					    	}else if(e[i].is_int==0){
//					    		str_insurance += "<tr><td><input type='checkbox' data-id='"+ e[i].val +"' data-type='"+ e[i].is_int +"' name='check'/></td><td class='desc'>"+ e[i].desc +"</td><td class='num'></td></tr>";
//					    	}
//				    	}		    	
//				    	
//				    }
//	//				console.log(str)
//				    $("#datatable_3").find('tbody').html(str_insurance);
//									
//					
//					$("#save_insurance").click(function(){
//						
//						$("#datatable_3 tbody input:checked").each(function(k,v){
//							//console.log(v)
//							var obj = {};
//							var is_int = $(v).attr("data-type");
//							//console.log(is_int)
//							var data_id = $(v).attr("data-id");
//							var data_desc = $(v).parent().siblings('.desc').text();
//							
//							//console.log($(v).attr("data-id"))
//							obj.id = data_id;
//				
//							//console.log($(v).parent().siblings('.desc').text())
//							obj.desc = data_desc;
//							
//							//console.log($(v).parent().siblings('.num').find('input').val())
//							if(is_int==0){
//								obj.count = 9999;
//							}else if(is_int==1){
//								var data_num = $(v).parent().siblings('.num').find('input').val()
//								obj.count = data_num;
//							}
//							//console.log(obj)
//							arr_insurance.push(obj);		
//							
//							//console.log(arr)
//						})
//						
//						var is_count3 = 0;
//						for(var i = 0;i<arr_insurance.length;i++){
//							if(arr_insurance[i].count==0){
//								is_count3++;
//							}
//						}
//						if(is_count3>0){
//							layer.msg("请添加数量", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
//							arr_insurance = [];
//						}else{
//							setTimeout("layer.closeAll()", 1000);
//						}
//						
//						
//						//console.log(arr)
//						//setTimeout("layer.closeAll()", 1000);				
//					})
//				
//				});
//				
//				
//				
//				
//			var arr_phone = [];
//		    //添加电话服务
//		    var layerphone = "";
//			$(document).on("click", "#add_phone",function() {
//					
//					var content = $("#phone_list").html();
//					$(document.body).css({
//						"overflow-x": "hidden",
//						"overflow-y": "hidden"
//					});
//					powidth = $(window).width() - 50;
//					poheight = $(window).height() - 50;
//					
//					layerphone = layer.open({
//						type: 1,
//						title: "服务详情",
//						fix: false,
//						shadeClose: false,
//						maxmin: true,
//						area: [powidth + 'px', poheight + 'px'],
//						content: content,
//						end: function() {
//							//console.log(arr);
//							//console.log(arr_phone);
//							mergeArray(arr,arr_phone);
//							arr_phone = [];
//							//console.log(arr)
//							draw(arr);
//					
//							$(document.body).css({
//								"overflow-x": "auto",
//								"overflow-y": "auto"
//							});
//						}
//					});
//					var d = $("#info_service").html();
//					//console.log(d)
//					var e = $.parseJSON(d);
//					//console.log(e)
//					var str_phone = "";
//				
//				    for(var i=0;i<e.length;i++){
//				    	
//				    	if(e[i].back_type==4){
//				    		if(e[i].is_int==1){
//					    		str_phone += "<tr><td><input type='checkbox' data-id='"+ e[i].val +"' data-type='"+ e[i].is_int +"' name='check'/></td><td class='desc'>"+ e[i].desc +"</td><td class='num'><input type='number' /></td></tr>";
//					    	}else if(e[i].is_int==0){
//					    		str_phone += "<tr><td><input type='checkbox' data-id='"+ e[i].val +"' data-type='"+ e[i].is_int +"' name='check'/></td><td class='desc'>"+ e[i].desc +"</td><td class='num'></td></tr>";
//					    	}
//				    	}		    	
//				    	
//				    }
//	//				console.log(str)
//				    $("#datatable_4").find('tbody').html(str_phone);
//									
//					
//					$("#save_phone").click(function(){
//						
//						$("#datatable_4 tbody input:checked").each(function(k,v){
//							//console.log(v)
//							var obj = {};
//							var is_int = $(v).attr("data-type");
//							//console.log(is_int)
//							var data_id = $(v).attr("data-id");
//							var data_desc = $(v).parent().siblings('.desc').text();
//							
//							//console.log($(v).attr("data-id"))
//							obj.id = data_id;
//				
//							//console.log($(v).parent().siblings('.desc').text())
//							obj.desc = data_desc;
//							
//							//console.log($(v).parent().siblings('.num').find('input').val())
//							if(is_int==0){
//								obj.count = 9999;
//							}else if(is_int==1){
//								var data_num = $(v).parent().siblings('.num').find('input').val()
//								obj.count = data_num;
//							}
//							//console.log(obj)
//							arr_phone.push(obj);		
//							
//							//console.log(arr)
//						})
//						
//						var is_count4 = 0;
//						for(var i = 0;i<arr_phone.length;i++){
//							if(arr_phone[i].count==0){
//								is_count4++;
//							}
//						}
//						if(is_count4>0){
//							layer.msg("请添加数量", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
//							arr_phone = [];
//						}else{
//							setTimeout("layer.closeAll()", 1000);
//						}
//						
//						//console.log(arr)
//						//setTimeout("layer.closeAll()", 1000);				
//					})
//				
//				});
//				
//				
//			
//			var arr_health = [];
//		    //添加健康档案服务
//		    var layerhealth = "";
//			$(document).on("click", "#add_health",function() {
//					
//					var content = $("#health_list").html();
//					$(document.body).css({
//						"overflow-x": "hidden",
//						"overflow-y": "hidden"
//					});
//					powidth = $(window).width() - 50;
//					poheight = $(window).height() - 50;
//					
//					layerhealth = layer.open({
//						type: 1,
//						title: "服务详情",
//						fix: false,
//						shadeClose: false,
//						maxmin: true,
//						area: [powidth + 'px', poheight + 'px'],
//						content: content,
//						end: function() {
//							//console.log(arr);
//							//console.log(arr_health);
//							mergeArray(arr,arr_health);
//							arr_health = [];
//							//console.log(arr)
//							draw(arr);
//					
//							$(document.body).css({
//								"overflow-x": "auto",
//								"overflow-y": "auto"
//							});
//						}
//					});
//					var d = $("#info_service").html();
//					//console.log(d)
//					var e = $.parseJSON(d);
//					//console.log(e)
//					var str_health = "";
//				
//				    for(var i=0;i<e.length;i++){
//				    	
//				    	if(e[i].back_type==5){
//				    		if(e[i].is_int==1){
//					    		str_health += "<tr><td><input type='checkbox' data-id='"+ e[i].val +"' data-type='"+ e[i].is_int +"' name='check'/></td><td class='desc'>"+ e[i].desc +"</td><td class='num'><input type='number' /></td></tr>";
//					    	}else if(e[i].is_int==0){
//					    		str_health += "<tr><td><input type='checkbox' data-id='"+ e[i].val +"' data-type='"+ e[i].is_int +"' name='check'/></td><td class='desc'>"+ e[i].desc +"</td><td class='num'></td></tr>";
//					    	}
//				    	}		    	
//				    	
//				    }
//	//				console.log(str)
//				    $("#datatable_5").find('tbody').html(str_health);
//									
//					
//					$("#save_health").click(function(){
//						
//						$("#datatable_5 tbody input:checked").each(function(k,v){
//							//console.log(v)
//							var obj = {};
//							var is_int = $(v).attr("data-type");
//							//console.log(is_int)
//							var data_id = $(v).attr("data-id");
//							var data_desc = $(v).parent().siblings('.desc').text();
//							
//							//console.log($(v).attr("data-id"))
//							obj.id = data_id;
//				
//							//console.log($(v).parent().siblings('.desc').text())
//							obj.desc = data_desc;
//							
//							//console.log($(v).parent().siblings('.num').find('input').val())
//							if(is_int==0){
//								obj.count = 9999;
//							}else if(is_int==1){
//								var data_num = $(v).parent().siblings('.num').find('input').val()
//								obj.count = data_num;
//							}
//							//console.log(obj)
//							arr_health.push(obj);		
//							
//							//console.log(arr)
//						})
//						
//						var is_count5 = 0;
//						for(var i = 0;i<arr_health.length;i++){
//							if(arr_health[i].count==0){
//								is_count5++;
//							}
//						}
//						if(is_count5>0){
//							layer.msg("请添加数量", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
//							arr_health = [];
//						}else{
//							setTimeout("layer.closeAll()", 1000);
//						}
//						
//						
//						//console.log(arr)
//						//setTimeout("layer.closeAll()", 1000);				
//					})
//				
//				});
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    //保存
	    $(document).on("click", "#save",function(){
	    	//console.log(arr);
	    	var product_name = $("#product_name").val();
	    	var product_type = $("#product_type").val();
	    	var card_type = $("#card_type").val();
	    	var old_price = $("#old_price").val();
	    	var offer_price = $("#offer_price").val();
	    	var effective_date = $("#effective_date").val();
	    	var serve_num = $("#serve_num").val();
	    	var carriage = $("#carriage").val();
	    	var sales_num = $("#sales_num").val();
	    	var is_onsalt = $("#is_onsalt").val();
	    	var img1 = $("#tciconsrc").val();
	    	var img2 = $("#tciconsrc2").val();
	    	var img3 = $("#tciconsrc3").val();
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
	    		url:local+"/shop_updategoods.php",
	    		data:{
	    			"goods_id" : id,
	    			"goods_name":product_name,
	    			"goods_type":product_type,
	    			"goods_card_type":card_type,
	    			"original_price":old_price,
	    			"now_price":offer_price,
	    			"goods_expire_time":effective_date,
	    			"goods_service_limit":serve_num,
	    			"freight_price":carriage,
	    			"goods_amount":sales_num,
	    			"goods_service":arr,
	    			"is_onsalt":is_onsalt,
	    			"goods_big_image": img2,
					"goods_small_image": img3,
					"goods_image": fin_goods_image,
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






