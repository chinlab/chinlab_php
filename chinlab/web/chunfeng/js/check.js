jQuery(document).ready(function($){
	$.ajax({
		type:"post",
		url:local+"/admin_DayCountJson.php",
		async:true,
		success:function(data){
			//console.log(data)
			if(data.state=="302"){
				if (self != top) {      
			        top.location.href = "login.html"; 
				}else{  
					window.location.href = "login.html";
				}  
			}
			else{
				$("body").css("display","block");
			}
		}
	});
});

//浏览器关闭，则执行退出，清理缓存
/*$(window).bind('beforeunload',function(e){
   
   // $(document).mousemove(function(e){
	    //获取鼠标在屏幕上的坐标
	    x=e.screenX;//屏幕的左上角为参考点
	    y=e.screenY;//获取屏幕的x和y坐标
	    //获取鼠标在当前窗口区域中的坐标
	    x2=e.clientX;
	    y2=e.clientY;
	    //返回事件被触发时鼠标指针相对于文档左边缘的位置
	    x3=e.pageX;
	    y3=e.pageY;
	    bodyWidth = document.body.clientWidth;
	    console.log(x);
	    console.log(y); 
	    console.log(bodyWidth);
 //  });
    if(b && window.event.clientY < 0 || window.event.altKey){   
            console.log("这是一个关闭操作而非刷新");   
        	$.ajaxSetup({
           		 async:false
           	});
        	$.ajax({
           		type:"post",
           		url:local+"/admin_LogoutJson.php",
           		success:function(data){
           			//console.log(data)
           			if(data.state=="0"){
           				if (self != top) {      
           			        top.location.href = "login.html"; 
           				}else{  
           					window.location.href = "login.html";
           				}  
           			}
           		}
           	});
    }else{
    	console.log("这是一个刷新操作而非关闭");   
    }
});*/

