var hotSaleImg = "";
var productListImg = "";
var productBannerImg = [];
var productInfoImg = [];
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
	if(productBannerImg.length>0){
		$("#bannerimgbox").addClass('isShow');
		var bannerstr = '';
		for(var i=0;i<productBannerImg.length;i++){
			bannerstr += '<li class="col-sm-3 pull-left"><img src="'+ productBannerImg[i] +'"/></li>'
		}
		$("#bannerimg_list").html(bannerstr);
		bannerstr = '';
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
   			if(type==5){
   				hotSaleImg = data.response.data.url;
   				afreshInit(5,1);
   				layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
            	showimglist()
   			}else if(type==6){
   				productListImg = data.response.data.url;
            	afreshInit(6,1);
   				layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
            	showimglist()
   			}else if(type==7){
   				productBannerImg = [];
   				var bannerimgurl = data.response.data.url;
	        	for(var i=0;i<bannerimgurl.length;i++){
	        		productBannerImg.push(bannerimgurl[i]);
	        	}
            	afreshInit(7,30);
   				layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
            	showimglist()
   			}else if(type==8){
   				productInfoImg = [];
   				var infoimgurl = data.response.data.url;
	        	for(var i=0;i<infoimgurl.length;i++){
	        		productInfoImg.push(infoimgurl[i]);
	        	}
	        	layer.msg(data.response.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000});
	        	afreshInit(8,30);
            	showimglist();
   			}
        } else {
            layer.msg(data.response.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
            showimglist();
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
	//获取信息
	var e = $("#info_goods").html();
	var d = $.parseJSON(e);
	console.log(d)
	$("#goods_type").val(d.goods_type);
	//console.log(d.oc_id)
	$("#goods_name").val(d.goods_name);
	$("#original_price").val(d.original_price);
	$("#now_price").val(d.now_price);
	$("#freight_price").val(d.freight_price);
	$("#goods_amount").val(d.goods_amount);
	$("#is_onsalt").val(d.is_onsalt);
	hotSaleImg = d.goods_big_image;
	productListImg = d.goods_small_image;
	productBannerImg = d.banner_image;
	productInfoImg = d.goods_image;
	console.log(d.goods_tag)
	$('.taglist input:radio[name="tag"][value="'+ d.goods_tag +'"]').prop('checked', "checked");
	$('.electplace input:radio[name="place"][value="'+ d.goods_index_location +'"]').prop('checked', "checked");
	showimglist()
	
	
	//产品名称字数限制
	// $(document).on("change","#produce_name",function(e){
	// 	var Pname_length = $(this).val().length;
	// 	if(Pname_length<2){
	// 		layer.msg("少于2个字，添加失败", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
	// 	}else if(Pname_length>20){
	// 		layer.msg("超出20个字，添加失败", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
	// 	}
	// });
	//销售价格
	// $(document).on("keyup","#sale_price",function(e){
	// 	if($(this).val()<0){
	// 		layer.msg("输入价格不在范围内", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
	// 		$(this).val("");
	// 	}else if($(this).val()>9999999){
	// 		layer.msg("输入价格不在范围内", {icon: 5, closeBtn: 1, shadeClose: true,time:2000});
	// 		$(this).val("");
	// 	}
	// });
	
	//是否上架下架
	if($("#is_onsalt").val()==1){
		$(".newArrival").attr('disabled','disabled');
		$(document).off("click",".removetag");
	}
	//产品上下架
	$(document).on("change","#is_onsalt",function(e){
		issaleval = $(this).val();
		if(issaleval==0){
			layer.msg("选择该产品下架在不同位置均被下架", {icon: 7, closeBtn: 1, shadeClose: true,time:2000});
		}
	})	
	
	//图片上传
	initFileInput("tcicon", "/shop_overseasgoodsimage.php","","1");
	$("#tcicon").attr('disabled','disabled');
	//选位置单选框监听
	$(document).on('change','input:radio[name="place"]',function(){
		var hotsaleplace = $('input:radio[name="place"]:checked').val();
		//console.log(hotsaleplace)
		$.ajax({
			type:"post",
			url:"/shop/goodlocationselect.php",
			data:{
				"goods_index_location" : hotsaleplace,
			},
			async:true,
			success:function(data){
				if(data.state==100){
					layer.confirm(data.message, {
						btn: ['确定','取消'], //按钮
						closeBtn:0
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
			afreshInit(5,1);
			$(".electplace").addClass('isShow');
			$("#tcicon").click(function(){
				var val=$('input:radio[name="place"]:checked').val();	
				if(val==null){
			        layer.msg("没有选中首页热卖图片的位置", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
			        return false;
			   }
			})
		}else if(selectvalue==2){
			afreshInit(6,1);
			$(".electplace").removeClass('isShow');
		}else if(selectvalue==3){
			afreshInit(7,6);
			$(".electplace").removeClass('isShow');
		}else if(selectvalue==4){
			afreshInit(8,30);
			$(".electplace").removeClass('isShow');
		}else{
			afreshInit("",30);
			$("#tcicon").attr('disabled','disabled');
			$(".electplace").removeClass('isShow');
		}
	})
	//保存
	$(document).on("click", "#save",function(){
		var goods_type = $("#goods_type").val();
		var goods_name = $("#goods_name").val();	
		var original_price = $("#original_price").val();
		var now_price = $("#now_price").val();
		var goods_tag = $('input:radio[name="tag"]:checked').val();
		console.log(goods_tag)
		var freight_price = $("#freight_price").val();
		var goods_amount = $("#goods_amount").val();
		var is_onsalt = $("#is_onsalt").val();
		console.log(hotSaleImg)	
		console.log(productListImg)	
		console.log(productBannerImg)	
		console.log(productInfoImg)	
		var goods_index_image = hotSaleImg;
		var list_image = productListImg;
		var banner_image = productBannerImg;
		var goods_image = productInfoImg;
		var goods_index_location = $('.electplace input:radio[name="place"]:checked').val();
		console.log(goods_index_location)	
		$.ajax({
			type:"post",
			url:"/shop_updategoods.php",
			data:{
				"goods_id" : goods_id,
				"goods_type" : goods_type,
				"goods_name" : goods_name,
				"original_price" : original_price,
				"now_price" : now_price,
				"goods_tag" : goods_tag,
				"freight_price" : freight_price,
				"goods_amount" : goods_amount,
				"is_onsalt" : is_onsalt,
				"goods_big_image" : goods_index_image,
				"goods_small_image" : list_image,
				"banner_image" : banner_image,
				"goods_image" : goods_image,
				"goods_index_location" : goods_index_location,
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
			