var hotSaleImg = "";
var productListImg = "";
var productBannerImg = "";
var productInfoImg = [];
var tagarr = [];
//图片展示
function showimglist(){
	if(hotSaleImg!=""){
		$("#hotsalebox").addClass('isShow');
		$("#hotsale_list img").attr('src',hotSaleImg);
	}else{
		$("#hotsalebox").removeClass('isShow');
	}
	if(productListImg!=""){
		$("#projectimgbox").addClass('isShow');
		$("#projectimg_list img").attr('src',productListImg);
	}else{
		$("#projectimgbox").removeClass('isShow');
	}
	if(productBannerImg!=""){
		$("#bannerimgbox").addClass('isShow');
		$("#bannerimg_list img").attr('src',productBannerImg);
	}else{
		$("#bannerimgbox").removeClass('isShow');
	}
	if(productInfoImg.length>0){
		//console.log(productInfoImg);
		$("#detailimgbox").addClass('isShow');
		var str = '';
		for(var i=0;i<productInfoImg.length;i++){
			str += '<li class="col-sm-2 pull-left"><img src="'+ productInfoImg[i] +'"/></li>'
		}
		$("#projectdetails_list").html(str);
		str = '';
	}else{
		$("#detailimgbox").removeClass('isShow');
	}
}

function keywordList(arr){
	var str = "";
	 for(var i=0;i<arr.length;i++){
	 	str += "<li><div class='tags'><span>"+ arr[i] +"</span><span class='removetag icon-remove' data-id='"+ i +"'></span></div></li>"
	 }
	$("#keywordList").html(str)
}
function initFileInput(ctrlName, uploadUrl,type,num) {
	//console.log(num)
    var control = $('#' + ctrlName);
    control.fileinput({
        language: 'zh', //设置语言
        uploadAsync:false, //默认异步上传
        uploadUrl: uploadUrl, //上传的地址
        uploadExtraData:{
        	'type':type,
        },
        showClose: true,
        showUpload: true, //是否显示上传按钮
        allowedFileExtensions: ['jpg', 'png', 'gif'], //接收的文件后缀
        maxFileCount: num,//文件上传最大限度
        maxFilesNum: num,
        showCaption: false, //是否显示标题
        dropZoneEnabled: true, //是否显示拖拽区域
        browseOnZoneClick: true,
        browseClass: "btn btn-primary", //按钮样式             
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
        msgFilesTooMany: "选择上传的文件数量({n}) 超过允许的最大数值{m}！",
        layoutTemplates:{
        	actionUpload:'',
        }
    }).on("filebatchuploadsuccess", function(event, data, previewId, index) {
    	var code = data.response.state;
    	if (code == "0") {
   			if(type==1){
   				hotSaleImg = data.response.data.url;
   				afreshInit(1,1);
   				layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
            	showimglist()
   			}else if(type==2){
   				productListImg = data.response.data.url;
            	afreshInit(2,1);
   				layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
            	showimglist()
   			}else if(type==3){
   				productBannerImg = data.response.data.url;
            	afreshInit(3,1);
   				layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
            	showimglist()
   			}else if(type==4){
   				productInfoImg = [];
   				var infoimgurl = data.response.data.url;
	        	for(var i=0;i<infoimgurl.length;i++){
	        		productInfoImg.push(infoimgurl[i]);
	        	}
	        	layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
	        	afreshInit(4,30);
            	showimglist();
   			}
        } else {
            layer.msg(data.response.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
            showimglist()
        }
	}).on("filecleared", function(event) {
		$("#tciconsrc").val("");
	});
}
//强制初始化
function afreshInit(type,num){
	var str = '<label for="tcicon">上传图片</label><input id="tcicon" type="file" name="icon[]" multiple data-min-file-count="1" class="form-control file-loading"><input id="tciconsrc" type="hidden" name="icon" class="form-control">';
    $("#imgupload").html(str)
	initFileInput("tcicon", "/shop_overseasgoodsimage.php",type,num);
}











$(document).ready(function(){
	var href = window.location.search;    	
    var goods_id = href.replace("?","").split("=")[1];
	//单选按钮可取消选中
	$(document).on("click","input:radio",function(e){
	    var $radio = $(this);
	    //console.log($radio.data('waschecked'))
	    if ($radio.data('waschecked') == true){
	      $radio.prop('checked', false);
	      $radio.data('waschecked', false);
	    } else {
	      $radio.prop('checked', true);
	      $radio.data('waschecked', true);
	    }
	});
	
	
	//下拉菜单
	var c = $("#info_categoryList").html();
	var category = $.parseJSON(c);
	//console.log(category)
	var str1 = "<option value=''>请选择</option>";
	var str2 = "<option value=''>请选择</option>";
	var str3 = "<option value=''>请选择</option>";
	var str4 = "";
	for(var i=0;i<category.length;i++){
		str1 += "<option value='"+ category[i].val +"'>"+ category[i].name +"</option>"
	}
	$("#category_name").html(str1);
	$(document).on("change","#category_name",function(e){
	 	var category_val = $(this).val();
	 	//console.log(category_val)
	 	if(category_val==1){
	 		$("#subclass_name").removeAttr('disabled');
	 	}else{
	 		$("#subclass_name").attr('disabled','disabled');
	 	}
	 	for(var i=0;i<category.length;i++){
			if(category[i].val==category_val){
				var subclass = category[i].category;
				for(var j=0;j<subclass.length;j++){
					str2 += "<option value='"+ subclass[j].val +"'>"+ subclass[j].name +"</option>"
				}
				$("#subclass_name").html(str2);
				str2 = "<option value=''>请选择</option>";
			}
		}
	});
	var s = $("#info_is_sale").html();
	var salelist = $.parseJSON(s);
	//console.log(salelist)
	for(var i=0;i<salelist.length;i++){
		str3 += "<option value='"+ salelist[i].val +"'>"+ salelist[i].name +"</option>"
	}
	$("#is_sale").html(str3);
	var i = $("#info_imageType").html();
	var imguploadlist = $.parseJSON(i);
	//console.log(imguploadlist)
	for(var i=0;i<imguploadlist.length;i++){
		str4 += "<option value='"+ imguploadlist[i].val +"'>"+ imguploadlist[i].name +"</option>"
	}
	$("#upload_img").html(str4);
	
	var e = $("#info_goodsInfo").html();
	var d = $.parseJSON(e);
	//console.log(d)
	$("#category_name").val(d.oc_parent_id);
	if(d.oc_parent_id==1){
	 		$("#subclass_name").removeAttr('disabled');
 	}else{
 		$("#subclass_name").attr('disabled','disabled');
 	}
 	for(var i=0;i<category.length;i++){
		if(category[i].val==d.oc_parent_id){
			var subclass = category[i].category;
			for(var j=0;j<subclass.length;j++){
				str2 += "<option value='"+ subclass[j].val +"'>"+ subclass[j].name +"</option>"
			}
			$("#subclass_name").html(str2);
			str2 = "<option value=''>请选择</option>";
		}
	}
	//console.log(d.oc_parent_id)
	$("#subclass_name").val(d.oc_id);
	//console.log(d.oc_id)
	$("#produce_name").val(d.goods_name);
	$("#produce_desc").val(d.goods_desc);
	$("#sale_price").val(d.sale_price);
	$("#favoure_price").val(d.favoure_price);
	$("#is_sale").val(d.is_sale);
	$("#hospital_name").val(d.hospital_name);
	$("#hospital_desc").val(d.hospital_desc);
	$("#goods_country").val(d.goods_country);
	$("#share_desc").val(d.share_desc);
	$("#share_title").val(d.share_title);
	hotSaleImg = d.goods_index_image;
	productListImg = d.list_image;
	productBannerImg = d.banner_image;
	productInfoImg = d.goods_image;
	tagarr = d.goods_point;
	$('.taglist input:radio[name="tag"][value="'+ d.goods_tag +'"]').prop('checked', "checked");
	$('.electplace input:radio[name="place"][value="'+ d.goods_index_location +'"]').prop('checked', "checked");
	showimglist()
	
	
	//产品名称字数限制
	$(document).on("change","#produce_name",function(e){
		var Pname_length = $(this).val().length;
		if(Pname_length<2){
			layer.msg("少于2个字，添加失败", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
		}else if(Pname_length>20){
			layer.msg("超出20个字，添加失败", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
		}
	});
	//销售价格
	$(document).on("keyup","#sale_price",function(e){
		if($(this).val()<0){
			layer.msg("输入价格不在范围内", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
			$(this).val("");
		}else if($(this).val()>9999999){
			layer.msg("输入价格不在范围内", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
			$(this).val("");
		}
	});
	
	
	//添加关键词
	$(document).on("click","#addTag",function(e){
	 	var taginput = $("#project_trait").val();
	 	//console.log(taginput.length)
	 	var reg = /^[a-zA-Z\u4e00-\u9fa5]+$/g;	 	
	 	if(taginput.length>5){
	 		layer.msg("字数超出5个", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
	 		$("#project_trait").val("");
	 		return false;
	 	}else{
	 		if(!reg.test(taginput)){
				layer.msg("关键字只能输入字母和汉字", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
				$("#project_trait").val("");
				return false;
			}else{
				if(tagarr.length>5){
					layer.msg("关键词条数超过六条", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
				}else{
					if($.inArray(taginput, tagarr)==-1){
						tagarr.push(taginput);
						console.log(tagarr)
				 		//console.log(tagarr)
				 		keywordList(tagarr); 
				 		$("#project_trait").val("");
					}else{
						layer.msg("已有该关键词", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
						$("#project_trait").val("");
					}
					
				}
			}
	 	}
	}) 
	keywordList(tagarr); 
	$(document).on("click",".removetag",function(e){
	 	var id = $(this).attr("data-id");
	 	//console.log(id)
	 	tagarr.splice(id,1)
	 	//console.log(tagarr)
	 	keywordList(tagarr);
	});
	
	//是否上架下架
	if($("#is_sale").val()==1){
		$(".newArrival").attr('disabled','disabled');
		$(document).off("click",".removetag");
	}
	//产品上下架
	$(document).on("change","#is_sale",function(e){
		issaleval = $(this).val();
		if(issaleval==0){
			layer.msg("选择该产品下架在不同位置均被下架", {icon: 7, closeBtn: 1, shadeClose: true,time:1000});
		}
	})	
	//医疗机构字数限制
	$(document).on("change","#hospital_name",function(e){
		var Hname_length = $(this).val().length;
		if(Hname_length>20){
			layer.msg("超出20个字，添加失败", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
		}
	});
	//医院描述字数限制
	$(document).on("change","#hospital_desc",function(e){
		var Hdesc_length = $(this).val().length;
		if(Hdesc_length>25){
			layer.msg("超出25个字，添加失败", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
		}
	});
	//分享标题字数限制
	$(document).on("change","#share_title",function(e){
		var Stitle_length = $(this).val().length;
		if(Stitle_length<5){
			layer.msg("少于5个字，添加失败", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
		}else if(Stitle_length>25){
			layer.msg("超出25个字，添加失败", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
		}
	});
	//分享描述字数限制
	$(document).on("change","#share_desc",function(e){
		var Sdesc_length = $(this).val().length;
		if(Sdesc_length<5){
			layer.msg("少于5个字，添加失败", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
		}else if(Sdesc_length>30){
			layer.msg("超出30个字，添加失败", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
		}
	});
	//图片上传
	initFileInput("tcicon", "/shop_overseasgoodsimage.php","","1");
	$("#tcicon").attr('disabled','disabled');
	//选位置单选框监听
	$(document).on('change','input:radio[name="place"]',function(){
		var hotsaleplace = $('input:radio[name="place"]:checked').val();
		//console.log(hotsaleplace)
		$.ajax({
			type:"post",
			url:local+"/shop/locationselect.php",
			data:{
				"goods_index_location" : hotsaleplace,
			},
			async:true,
			success:function(data){
				if(data.state==100){
					layer.confirm(data.message, {
						btn: ['确定','取消'] //按钮
					}, function(index){
						layer.close(index);
					}, function(index){
						$('input:radio[name="place"]').prop('checked', false);
						$('input:radio[name="place"]').data('waschecked', false);
						layer.close(index);
					});
				}
			}
		});	
	})
	//下拉菜单监听事件
	$(document).on('change','#upload_img',function(){
		var selectvalue = $(this).val();
		//console.log(selectvalue)
		if(selectvalue==1){
			afreshInit(1,1);
			$(".electplace").addClass('isShow');
			$("#tcicon").click(function(){
				var val=$('input:radio[name="place"]:checked').val();	
				if(val==null){
			        layer.msg("没有选中首页热卖图片的位置", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
			        return false;
			   }
			})
		}else if(selectvalue==2){
			afreshInit(2,1);
			$(".electplace").removeClass('isShow')
		}else if(selectvalue==3){
			afreshInit(3,1);
			$(".electplace").removeClass('isShow')
		}else if(selectvalue==4){
			afreshInit(4,30);
			$(".electplace").removeClass('isShow')
		}else{
			afreshInit("",30);
			$("#tcicon").attr('disabled','disabled');
			$(".electplace").removeClass('isShow')
		}
	})
	//保存
	$(document).on("click", "#save",function(){
		var oc_parent_id = $("#category_name").val();
		console.log(oc_parent_id)
		var oc_id = $("#subclass_name").val();	
		var goods_name = $("#produce_name").val();
		var goods_desc = $("#produce_desc").val();
		var sale_price = $("#sale_price").val();
		var favoure_price = $("#favoure_price").val();
		var is_sale = $("#is_sale").val();
		var hospital_name = $("#hospital_name").val();
		var hospital_desc = $("#hospital_desc").val();
		var goods_country = $("#goods_country").val();
		var share_desc = $("#share_desc").val();
		var share_title = $("#share_title").val();
		
		
		
		console.log(hotSaleImg)	
		console.log(productListImg)	
		console.log(productBannerImg)	
		console.log(productInfoImg)	
		var goods_index_image = hotSaleImg;
		var list_image = productListImg;
		var banner_image = productBannerImg;
		var goods_image = productInfoImg;
		var goods_index_location = $('input:radio[name="place"]:checked').val();
		console.log(goods_index_location)
		var goods_tag = $('.taglist input:radio[name="tag"]:checked').val();
		console.log(goods_tag)
		var goods_point = tagarr;
		console.log(goods_point)	
		$.ajax({
			type:"post",
			url:local+"/shop/updateoverseasgoods.php",
			data:{
				"goods_id" : goods_id,
				"oc_parent_id" : oc_parent_id,
				"oc_id" : oc_id,
				"goods_name" : goods_name,
				"goods_desc" : goods_desc,
				"sale_price" : sale_price,
				"favoure_price" : favoure_price,
				"goods_point" : goods_point,
				"is_sale" : is_sale,
				"hospital_name" : hospital_name,
				"hospital_desc" : hospital_desc,
				"goods_tag" : goods_tag,
				"goods_country" : goods_country,
				"share_title" : share_title,
				"share_desc" : share_desc,
				"goods_index_location" : goods_index_location,
				"goods_index_image" : goods_index_image,
				"list_image" : list_image,
				"banner_image" : banner_image,
				"goods_image" : goods_image,
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
	 
});
			