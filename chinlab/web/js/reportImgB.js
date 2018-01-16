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
	
	
	

	$(function(){
		var d = $("#info_state").html();
	    //console.log(d)
	    var e = $.parseJSON(d);
	    $(".examine_place").html(e.check_organization);
	    $(".examine_time").html(e.report_check_time);
		var data = e.orderfile_url;
		var str1 = "";
		var str2 = "";
		//console.log(data)
		if(data.length==0){
			$("#slider").css("display","none");
		}else{
			for(var i=0;i<data.length;i++){
				str1 +=　"<li><img src='"+ data[i].url +"'/></li>"
			}
			$("#big_img").html(str1)
			for(var j=0;j<data.length;j++){
				str2 +=　"<li><div><img src='"+ data[j].url +"'/></div></li>"
			}
			$("#sm_img").html(str2);
			$("#big_img li").eq(0).addClass("show");
			$("#sm_img li").eq(0).addClass("cur");
			
			
						
		}
		sss();
		
		
		
		
		
		
	})
	
	
	
