$(document).ready(function(){
		$(document).on("click", "#add",function() {
//					var trid = $(this).attr("trid");
			$(document.body).css({
				"overflow-x": "hidden",
				"overflow-y": "hidden"
			});
			powidth = $(window).width() - 500;
			poheight = $(window).height() - 300;
			layerindex = layer.open({
				type: 2,
				title: "添加权限",
				fix: false,
				shadeClose: true,
				maxmin: true,
				area: [powidth + 'px', poheight + 'px'],
				content: "/cms_jurisdictioninfo.php",
				end: function() {
					$(document.body).css({
						"overflow-x": "auto",
						"overflow-y": "auto"
					});
				}
			});
		});
 })