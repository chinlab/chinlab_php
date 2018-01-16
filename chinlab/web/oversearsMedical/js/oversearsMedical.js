window.onload = function(){
		var japan = document.getElementsByClassName("japan")[0];
		var taiwan = document.getElementsByClassName("taiwan")[0];
		var japanCon = document.getElementsByClassName('introContent1')[0];
		var taiWanCon= document.getElementsByClassName('introContent2')[0];
		 
		japan.onclick = function(){
			japanCon.style.display = "block";
			taiWanCon.style.display = "none";
		};
		taiwan.onclick = function(){
			japanCon.style.display = "none";
			taiWanCon.style.display = "block";
		}
	}