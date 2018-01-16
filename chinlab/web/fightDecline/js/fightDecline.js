window.onload = function(){
		var taiCon = document.getElementsByClassName("taiCon")[0];
		var germCon = document.getElementsByClassName("germCon")[0];
		var ulCon1 = document.getElementsByClassName("ulCon1")[0];
		var ulCon2 = document.getElementsByClassName("ulCon2")[0];
		taiCon.onclick = function(){
			ulCon1.style.display = "block";
			ulCon2.style.display = "none";
			taiCon.style.background = "#ffc600";
			germCon.style.background = "#d8d7d7";
			
		};
		germCon.onclick = function(){
			ulCon1.style.display = "none";
			ulCon2.style.display = "block";
			taiCon.style.background = "#d8d7d7";
			germCon.style.background = "#ffc600";
		}
	}