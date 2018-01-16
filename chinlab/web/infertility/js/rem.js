//控制rem值
document.documentElement.style.fontSize=document.documentElement.clientWidth/7.5+'px';
//DOM reload-- firefox兼容  ,用户企图改变浏览器窗口大小的时候
window.addEventListener('resize',function(){document.documentElement.style.fontSize=document.documentElement.clientWidth/7.5+'px';})
