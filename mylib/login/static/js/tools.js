
/*html 5不显示菜单栏，url地址栏，页面居中显示
*_________________________________________________
*/
function showcenter(){ 
	if(document.documentElement.scrollHeight <= document.documentElement.clientHeight) { 
		bodyTag = document.getElementsByTagName('body')[0]; 
		bodyTag.style.height = document.documentElement.clientWidth / screen.width * screen.height + 60+'px'; 
	} 
		setTimeout(function() { 
		window.scrollTo(0, 1) 
	}, 0);
}; 



/* 无刷新设置url参数
*@param arr= New Array('key'=>'value');
*_________________________________________________
*/
function seturl(arr){
	var str; var tag=1;
	for(var key in arr){
		if(tag==1){
			str = key + "=" + arr[key];
		}else{
			str =  str + "&" + key + "=" + arr[key];
		}
		
		tag++;
	}
	document.location.hash=str;
}

/* 无刷新设置url
*
*_________________________________________________
*/
function seturl(url){
	document.location.href=url;
}

/* 无刷新修改url参数
*destiny是目标字符串，比如是http://www.huistd.com/?id=3&ttt=3 
*par是参数名，par_value是参数要更改的值，调用结果如下： 
*changeURLPar(test, 'id', 99); // http://www.huistd.com/?id=99&ttt=3 
*changeURLPar(test, 'haha', 33); // http://www.huistd.com/?id=99&ttt=3&haha=33 
*_________________________________________________
*/
function changeURLPar(destiny, par, par_value) { 
	var pattern = par+'=([^&]*)'; 
	var replaceText = par+'='+par_value; 
	if (destiny.match(pattern)) { 
		var tmp = '/'+par+'=([^&]*)/'; 
		
		tmp = destiny.replace(eval(tmp), replaceText);
		return (tmp); 
	} else { 
		if (destiny.match('[\?]')) { 
			return destiny+'&'+ replaceText; 
		} else { 
			return destiny+'?'+replaceText; 
		} 
	} 
	return destiny+'\n'+par+'\n'+par_value; 
}


/* 获取url及参数
*_________________________________________________
*/
function geturl(){
	return document.location.href;
}



/* 获取url参数值的三种方式
*调用方法
*var Request = new Object(); 
*Request = GetRequest(); 
*var 参数1,参数2,参数3,参数N; 
*参数1 = Request['参数1']; 
*参数2 = Request['参数2']; 
*参数3 = Request['参数3']; 
*参数N = Request['参数N']; 
*_________________________________________________
*/
//方法一
function getQueryString(key) { 
	var reg = new RegExp(key + '=([^&]*)');  
    var results = location.href.match(reg);  
    return results ? results[1] : null;   
} 
function getQueryString1(href,key) { 
	var reg = new RegExp(key + '=([^&]*)');
	var results = href.match(reg);  
	return results ? results[1] : null;   
} 
//方法二
/** 
 * 获取url参数值 
 * @method getUrlParam 
 * @param {String} paramName 参数名 
 * @return {String} 参数值 
 */  
getUrlParam = function(paramName) {  
    var href = window.location.href;  
    var url = decodeURI(href);  
    var idx = url.indexOf("?");  
    var params = url.substring(idx + 1);  
    if (params) {  
        params = params.split("&");  
        for (var i = 0; i < params.length; i += 1) {  
            var param = params[i].split("=");  
            if (param[0] == paramName) {  
                //完善获取url参数的逻辑  
                var pArr = [];  
                for (var k = 1, len = param.length; k < len; k++) {  
                    pArr.push(param[k]);  
                }  
                var p = pArr.join('=');  
                var idx1 = p.indexOf("#");  
                if (idx1 != -1) {  
                    p = p.substring(0, idx1);  
                }  
                return p;  
            }  
        }  
    }  
}; 

//方法三
/** 
 * 通过正则表达式获取url参数 
 * 支持锚点#与自定义参数分割形式 
 */  
getUrlParamObject = function(external, split) {  
    var reg = /^.*\?{1}(.*)/;  
    var result = [];  
    var href = window.location.href;  
    var url = decodeURI(href);  
    var param = reg.exec(url);  
    if (param == null || param.length == 1 || param[1] == '')  
        return result;  
    var reg2 = /(?:([^&#]*?)=([^&#]*))[&#]?/g;  
    //  匹配前面参数  
    param[1].replace(reg2, function(a, b, c) {  
        analyseParam(b, c);  
    });  
    /*var reg3 = /[&#](?:([^&#]*?)=([^&#]*))$/g; 
    //   匹配最末尾参数 
    param[1].replace(reg3,function(a,b,c){ 
        analyseParam(b,c); 
    });*/  
    function analyseParam(key, value) {  
        if (/=/.test(value) && external) {  
            var reg4 = new RegExp('(?:([^' + split + ']*?)=([^' + split + ']*))' + split + '', 'g');  
            var sub = [];  
            value.replace(reg4, function(a, b, c) {  
                sub.push({  
                    key: b,  
                    value: c,  
                    type: 'string'  
                });  
            });  
            var reg5 = new RegExp('' + split + '(?:([^' + split + ']*?)=([^' + split + ']*))$', 'g');  
            value.replace(reg5, function(a, b, c) {  
                sub.push({  
                    key: b,  
                    value: c,  
                    type: 'string'  
                });  
            });  
            result.push({  
                key: key,  
                type: 'object',  
                value: sub  
            });  
        } else {  
            result.push({  
                key: key,  
                type: 'string',  
                value: value  
            });  
        }  
    }  
    return result;  
};  


//刷新验证码;   

//_________________
function changeAuthCode() {
	var num = 	new Date().getTime();
	var rand = Math.round(Math.random() * 10000);
	num = num + rand;
	$('#ver_code').css('visibility','visible');
	if ($("#vdimgck")[0]) {
		$("#vdimgck")[0].src = "../../include/vdimgck.php?tag=" + num;
	}
	return false;
}


/* 记录登录需求页的url
*_________________________________________________
*/
$("document").ready(function(){
	$(".footer").click(function(){
		var lasturl=location.href;
		setCookie('lasturl', lasturl, 365);
		//alert(getCookie('lasturl'));
	});
});



/* 记录需求页的url
 *_________________________________________________
 */
function set_lasturl(){
	var lasturl=location.href;
	setCookie('lasturl', lasturl, 365);
//alert(getCookie('lasturl'));
}






/**
 *判断值是否是数组中的元素，是则反键名。否则返回-1
 *_________________________________________________
 **/
 function inarray(value,array){
	 var tag = -1;
	 var x;
	 for(x in array) {
		 if(array[x] == value){
			 tag = x;
			 break;
		 }
	 }
	 return (tag);
 }





// 设置cookie
//_________________________________________________

function setCookie(cname, cvalue, exdays) {  
	var d = new Date();  
	d.setTime(d.getTime() + (exdays*24*60*60*1000));  
	var expires = "expires="+d.toUTCString();  
	document.cookie = cname + "=" + cvalue + "; " + expires;  
}  


// 获取cookie
function getCookie(cname) {  
	var name = cname + "=";  
	var ca = document.cookie.split(';');  
	for(var i=0; i<ca.length; i++) {  
		var c = ca[i];  
		while (c.charAt(0)==' ') c = c.substring(1);  
		if (c.indexOf(name) != -1) return c.substring(name.length, c.length);  
}  
	return "";  
}  


//将图片保存到手机相册
function saveAs(Url){
    var blob=new Blob([''], {type:'application/octet-stream'});
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = Url;
    a.download = Url.replace(/(.*\/)*([^.]+.*)/ig,"$2").split("?")[0];
    var e = document.createEvent('MouseEvents');
    e.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
    a.dispatchEvent(e);
    URL.revokeObjectURL(url);
}


// 清除cookie
function clearCookie(name) {   
	setCookie(name, "", -1);   
}

//验证cookie
function checkCookie() {  
	var user = getCookie("username");  
	if (user != "") {  
		alert("Welcome again " + user);  
	} else {  
		user = prompt("Please enter your name:", "");  
		if (user != "" && user != null) {  
			setCookie("username", user, 365);  
		}  
	}  
} 




//遍历对象
//_________________________________________________
function allPrpos ( obj ) { 
  // 用来保存所有的属性名称和值 
  var props = "" ; 
  // 开始遍历 
  for ( var p in obj ){ // 方法 
  if ( typeof ( obj [ p ]) == " function " ){ obj [ p ]() ; 
  } else { // p 为属性名称，obj[p]为对应属性的值 
  props += p + " = " + obj [ p ] + " <br/> " ; 
  } } // 最后显示所有的属性 
  document.write ( "<pre>"+props+"</pre>" ) ;
}
