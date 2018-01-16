$(document).ready(function() {
				$(".menuc").click(function() {
					var url = $(this).attr("url");
					$("#iframecon").attr("src", url);
				})
				
				
				$("#side-menu").find("li").each(function(){			
					$(this).find("a").mouseover(function(){
						$(this).siblings("#longbar").css("display","block")	
					})
					$(this).find("a").mouseout(function(){
						$(this).siblings("#longbar").css("display","none")	
					})
				})
				$(".nav").find("li").each(function(){
					$(this).find("a").mouseover(function(){
						$(this).siblings("#shortbar").css("display","block")	
					})
					$(this).find("a").mouseout(function(){
						$(this).siblings("#shortbar").css("display","none")	
					})
				})
				
				
				
				var layerindex = "";
				var layerindex2 = "";
				$(document).on("click", ".personal",
					function() {
	//					var trid = $(this).attr("trid");
						$(document.body).css({
							"overflow-x": "hidden",
							"overflow-y": "hidden"
						});
						powidth = $(window).width() - 500;
						poheight = $(window).height() - 300;
						layerindex = layer.open({
							type: 2,
							title: "个人资料",
							fix: false,
							shadeClose: false,
							maxmin: true,
							area: [powidth + 'px', poheight + 'px'],
							content: "/cms/personal.php",
							end: function() {
								$(document.body).css({
									"overflow-x": "auto",
									"overflow-y": "auto"
								});
							}
						});
					});
				$(document).on("click", ".changePw",
					function() {
	//					var trid = $(this).attr("trid");
						$(document.body).css({
							"overflow-x": "hidden",
							"overflow-y": "hidden"
						});
						powidth = $(window).width() - 500;
						poheight = $(window).height() - 300;
						layerindex2 = layer.open({
							type: 2,
							title: "修改密码",
							fix: false,
							shadeClose: false,
							maxmin: true,
							area: [powidth + 'px', poheight + 'px'],
							content: "/cms/changepw.php",
							end: function() {
								$(document.body).css({
									"overflow-x": "auto",
									"overflow-y": "auto"
								});
							}
						});
					});
				
				
			});
		
			function iFrameHeight() {
				var ifm = document.getElementById("iframecon");
				var subWeb = document.frames ? document.frames["iframepage"].document : ifm.contentDocument;
				if(ifm != null && subWeb != null) {
					ifm.height = $(window).height() - 60;
				}
			}
			
			
			
			