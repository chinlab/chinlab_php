window.onload = function(){
		var america  = document.getElementsByClassName('america')[0];
		var taiWan  = document.getElementsByClassName('taiWan')[0];
		var instroCon1 = document.getElementsByClassName('instroCon1')[0];
		var instroCon2 = document.getElementsByClassName('instroCon2')[0];
		america.onclick = function(){
			instroCon1.style.display = "block";
			instroCon2.style.display = "none";
			america.style.background = "#b7e5ff";
			taiWan.style.background = "#d6d7d7";
		};
		taiWan.onclick = function(){
			instroCon1.style.display = "none";
			instroCon2.style.display = "block";
			america.style.background = "#d6d7d7";
			taiWan.style.background = "#b7e5ff";
		}
	}