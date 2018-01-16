function sss(){
		var oSlider=$('#slider'),
		oShowBox=oSlider.find('.show-box'),
		oShowPrev=oSlider.find('.show-prev'),
		oShowNext=oSlider.find('.show-next'),
		oMinPrev=oSlider.find('.min-prev'),
		oMinNext=oSlider.find('.min-next'),
		oMinBoxList=oSlider.find('.min-box-list'),
		aShowBoxLi=oShowBox.find('li'),
		aMinBoxLi=oMinBoxList.find('li'),
		iMinLiWidth=aMinBoxLi.first().outerWidth(),
		iNum=aMinBoxLi.length,
		iNow=0;//这里初始值为4的原因是 当前显示的图片下标为4

		//根据小图片的多少来计算出父容器的宽度,让其显示成一行
		oMinBoxList.css('width',iNum*iMinLiWidth+'px');

		//由于ie6 不支持max-width 所以设置图片的最大显示宽度 注意ie6下的css图片设置的宽度为100%,所以这里设置li即可
		var isIE6=!-[1,]&&!window.XMLHttpRequest;
		if(isIE6)  
		{
			for(var i =0;i<iNum;i++)
			{
				var o=aShowBoxLi.eq(i)
				o.css({width:o.outerWidth() > 643 ? 643 : o.outerWidth(),float:'none',margin:'0 auto'})
			}
		}

		oShowPrev.hover(function(){
			//采用链式操作减少dom操作.
			$(this).css({backgroundColor:'#F5F5F5'}).find('span').css({backgroundPosition:'-20px -47px'})
		},function(){
			$(this).css({backgroundColor:'#fff'}).find('span').css({backgroundPosition:'-20px 0px'})
		})
		oShowNext.hover(function(){
			$(this).css({backgroundColor:'#F5F5F5'}).find('span').css({backgroundPosition:'-89px -47px'})
		},function(){
			$(this).css({backgroundColor:'#fff'}).find('span').css({backgroundPosition:'-89px 0px'})
		})

		//键盘左右方向箭控制图片显示  (这里采用原生js)
		document.onkeyup=function(ev)
		{
			var o=window.event || ev; //获取事件对象
			if(o.keyCode==37)
			{
				changePrev()
			}
			else if(o.keyCode==39)
			{
				changeNext()
			}
		}

		//小图片列表添加点击事件
		aMinBoxLi.click(function(){
			var index=$(this).index();
			iNow=index;
			setLayout();//回调样式及动画函数
		})

		//左右按钮添加点击  回调
		oMinPrev.click(changePrev);
		oMinNext.click(changeNext);
		oShowPrev.click(changePrev);
		oShowNext.click(changeNext);

		function changePrev()
		{   //iNow相当于下标索引，固下标索引不能小于0
			if(iNow<=0)
			{
				return;
			}
			iNow--;
			setLayout()
		}
		function changeNext()
		{
			if(iNow>=iNum-1)
			{
				return;
			}
			iNow++;
			setLayout();
		}

		//设置显示样式及动画
		function setLayout()
		{
			var oCurLi = aShowBoxLi.eq(iNow);
			aShowBoxLi.hide().eq(iNow).fadeIn('slow');
			aMinBoxLi.removeClass('cur').eq(iNow).addClass('cur');

			//限制图片运动距离，以免出现空白.
			if(iNow>=4 && iNow< iNum-4) 
			{
				oMinBoxList.animate({left:-iMinLiWidth*(iNow-4)},100)
			}
		}
	}
	
	function isChack(){
		var isChack = 0;
		if($("input[name='reject_img']:checked").length >= 1){
			isChack = 1;
		}
		return isChack;
	}
	

	$(function(){
		var href = window.location.search;    	
		var id = href.replace("?","").split("=")[1];
		var pIndex = parent.layer.getFrameIndex(window.name);  
		  
		var d = $("#info_state").html();
	    //console.log(d)
	    var e = $.parseJSON(d);
	    //console.log(e)
	    $(".examine_place").html(e.check_organization);
	    $(".examine_time").html(e.report_check_time);
		var data_img = e.orderfile_url;
		var str1 = "";
		var str2 = "";
		//console.log(data)
		if(data_img.length==0){
			$("#main").css("display","none");
		}else{
			for(var i=0;i<data_img.length;i++){
				str1 +=　"<li><div class='error_img'><input type='checkbox' img-id='"+ data_img[i].id +"' name='reject_img' />选中驳回</div><div class='img_state' img-state='"+ data_img[i].state +"'>"+ data_img[i].state_desc +"</div><img src='"+ data_img[i].url +"'/></li>"
			}
			$("#big_img").html(str1)
			for(var j=0;j<data_img.length;j++){
				str2 +=　"<li><div><img src='"+ data_img[j].url +"'/></div></li>"
			}
			$("#sm_img").html(str2);
			$("#big_img li").eq(0).addClass("show");
			$("#sm_img li").eq(0).addClass("cur");
			
						
		}
		sss();
		
		
		
		
		
		
		
		
		var error_img = [];
		$(document).on("click","#reject",function(){
			
			if($("input[name='reject_img']:checked").length >= 1){
				layer.confirm('您是否要驳回选中的检查报告?30分钟内不可重复驳回！', {
					btn: ['确定','取消'] //按钮
				}, function(index){
//					console.log(index)
					$('input:checkbox[name=reject_img]:checked').each(function(){
						var img_obj = {};
			     		console.log($(this).parent('.error_img').siblings('img').attr('src'))
			     		var imgsrc = $(this).parent('.error_img').siblings('img').attr('src');
			     		var imgid = $(this).attr('img-id');
			     		var imgstate = $(this).parent('.error_img').siblings('.img_state').attr('img-state');
			     		img_obj.url = imgsrc;
			     		img_obj.id = imgid;
			     		img_obj.state = imgstate;
			     		error_img.push(img_obj);
			    	})
//					console.log(error_img)
					
					$.ajax({
			    		type:"post",
			    		url:local+"/groupcustomer/setphotoflag.php",
			    		data:{
			    			"order_id" : id,
			    			"photos":error_img,
			    		},
			    		async:true,
			    		success:function(data){
//			    			console.log(data)
			    			if(data.state==0){
								layer.msg(data.message, {icon: 1, closeBtn: 1, shadeClose: true,time:1000},function(){
									layer.close(index);
									parent.layer.close(pIndex);  
								});
							}else{
								layer.msg(data.message, {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
							}
			    		}
			    	});	
				}, function(index){
					
					layer.close(index);
				});
			}else{
				layer.msg("你没有选中要驳回的照片", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
			}		
		})
		
		
		
		
		
		
	})
	
	
	
