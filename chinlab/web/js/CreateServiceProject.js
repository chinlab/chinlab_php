//时间格式
  function getDate(tm){ 
		var tt=new Date(parseInt(tm) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ") 
		return tt; 
	} 
	
	
	
	function draw(arr){
		var str = "";
		var items_type = "";
		var length = arr.length;
		for(var i=0;i<length;i++){
			if(arr[i].items_type==1){
				items_type = "预约挂号";
			}else if(arr[i].items_type==2){
				items_type = "体检类";
			}else if(arr[i].items_type==3){
				items_type = "保险类";
			}else if(arr[i].items_type==4){
				items_type = "电话服务";
			}else if(arr[i].items_type==5){
				items_type = "健康档案类";
			}		
			str += "<tr><td>"+ (i+1) +"</td><td>"+ arr[i].customer_name +"</td><td>"+ arr[i].items_name +"</td><td>"+ items_type +"</td><td>"+ arr[i].items_price +"</td><td><a class='del' data-id='"+ i +"'>删除</a></td></tr>" 
		}
		$("#datatable").find("tbody").html(str);
		del(arr);
	}
	
	
	function del(arr){
		$(".del").click(function(){
			var inx = $(this).attr("data-id");
			arr.splice(inx,1);
			draw(arr);
		})
	}


	$(document).ready(function(){
		
		var d = $("#info_list").html();
		//console.log(d)
		var e = $.parseJSON(d);
		console.log(e)
		var str1 = "<option value=''>请选择</option>";
		//console.log(data1)
		for(var i=0;i<e.length;i++){
			str1 += "<option value='"+ e[i].val +"'>"+ e[i].name +"</option>"
		}
		$("#items_classify").html(str1);
		
		$(document).on("blur","#customer_company",function(){
			var company = $("#customer_company").val();
			if(company.length>25){
				layer.msg("服务项目字数不能大于25个字", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
				$("#customer_company").val("");
			}
		})
		
		
		$(document).on("blur","#service_items",function(){
			var items = $("#service_items").val();
			if(items.length>20){
				layer.msg("服务项目字数不能大于20个字", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
				$("#service_items").val("");
			}
		})
		
		
		
		$(document).on("blur","#service_cost",function(){
			var price = $("#service_cost").val();
			if(price<0){
				layer.msg("服务费用不能为负数", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
				$("#service_cost").val("");
			}
		})
		
		
		var arr = [];
		
		$(document).on("click","#addProject",function(){
			var obj = {};
			if($("#customer_company").val()=="" || $("#service_items").val()=="" || $("#service_cost").val()=="" || $("#items_classify").val()=="" ){
				layer.msg("内容不能为空", {icon: 5, closeBtn: 1, shadeClose: true,time:1000});
			}else{
				obj.customer_name = $("#customer_company").val();
				obj.items_name = $("#service_items").val();
				obj.items_type = $("#items_classify").val();
				obj.items_price = $("#service_cost").val();
				arr.push(obj);
				draw(arr);
				$("#customer_company").val("");
				$("#service_items").val("");
				$("#items_classify").val("");
				$("#service_cost").val("");
				console.log(arr)
			}
			
		})

		//保存
		$(document).on("click", "#save",function(){
			
				
				$.ajax({
					type:"post",
					url:local+"/groupcustomer/addcustomer.php",
					data:{
						"items" : arr,
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
		
	})
