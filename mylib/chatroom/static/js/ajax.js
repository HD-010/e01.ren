/*作者：HD_e01
*使用说明 
*param method 可用"get"或"post"
*param url="/statics/demosource/demo_get.php?t="(如果method="post",url不带参数)
*param type 为服务器返回的数据类型，可以是'text'|'xml'|'josn'
*param callbackfunction 设定回调函数名称
*param data	设定post数据
*data="fname=Henry&lname=Ford"
*return 函数返回三种类型的数据:type为text返回str;
*						type为xml返回xmldom;
*						type为josn返回array;
*
*调用方式
*var method='get';
*var url='process.php?name=HD_e01';
*var type='text';
*var callbackfunction 为处理返回结果的函数,如果不返回信息，为可以为空
*var data	设定post数据
*ajax(method,url,type,callbackfunction,data);
*function callbackfunction(res){
*	//res为process.php返回后被处理的数据,其类型可能是str,xmldom或array,依type的类型而定
*	alert(res);
*}
*
*
*以下为php文件处理程序范例
*header("Content-Type:text/mxl;charset=utf-8");//告诉浏览器，返回的是xml格式
*header("Content-Type:text/html;charset=utf-8");//告诉浏览器，返回的是text格式
*header("Cache-Control:no-cache");
*$name=$_GET['name'];
*$str = "{'name':'$name'}";
*echo $str;
**/


var Ajax;
function ajax(method,url,type,callbackfunction,data,contenttype){
	Ajax=newAjax();
	
	if(!Ajax){
		alert("您的浏览器不支持当前服务，请下载最新版本！");
	}else{			
		//打开请求
		 switch(method){
		 case "get":
			 Ajax.open("GET",url,true);
			 break;
		 case "post":
			 Ajax.open("POST",url,true);
			 
			 switch(contenttype){
				 case 'multipart':
					 var content = "multipart/form-data";
					 break;
				 default :
					 var content = "application/x-www-form-urlencoded";	 
			 }
			 
			 Ajax.setRequestHeader("Content-type",content);
			 break;
		 }
		 
		 //指定响应函数（这是一个回调函数）
		 //Ajax.onreadystatechange=callbackfunction;
		 if(callbackfunction != null){
			 switch(type){
			 case 'text':
				 Ajax.onreadystatechange=function(){
				 	if(Ajax.readyState==4 && Ajax.status==200){
						
				 			callbackfunction(Ajax.responseText);
						
					}
				 }
				 break;
			 case 'xml':
				 Ajax.onreadystatechange=function(){
				 	if(Ajax.readyState==4 && Ajax.status==200){
				 		callbackfunction(Ajax.responseXML);
					}
				 }
				 break;
			 case 'json':
				 Ajax.onreadystatechange=function(){
				 	if(Ajax.readyState==4 && Ajax.status==200){
				 		var res=Ajax.responseText;
				 		//使用eval函数将res字串，转换成相应的对象
				 		var res_obj = eval("(" + res + ")");
				 		//var res_obj = JSON.parse(res);
				 		callbackfunction(res_obj);
				 		//alert(res_obj.name);
					}
				 }
				 break;
			 }
		 }
		 
		 
		 
		 //发送请求,如果是get请求则填入null
		 //如果是post请求，则填入实际的数据
		 Ajax.send(data);
	}
}

//创建Ajax对象
function newAjax(){
	
	var xmlhttp;

	var xmlhttp;
	 if (window.XMLHttpRequest)
	   {// code for IE7+, Firefox, Chrome, Opera, Safari
	   xmlhttp=new XMLHttpRequest();
	   
	   }
	 else
	   {// code for IE6, IE5
	   xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	   
	   }
	 
	 return xmlhttp;
}

//回调函数
/*function callbackfunction(){
	//window.alert(Ajax);
	//取出process.php返回的数据
	if(Ajax.readyState==4 && Ajax.status==200){
		alert(Ajax.responseText);
	}
}*/
//默认回调函数不对返回值作处理
function callbackfunction(){}


	

