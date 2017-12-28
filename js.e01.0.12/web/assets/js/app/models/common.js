/**
 * 	公共对象
 */
define(["jquery"],function($){
	var common = {
		//定义元素和当前元素的关系:格式[[元素1名称，元素2名称，...],当前元名称,动作名称(或动作类型)1｜动作名称(或动作类型)2｜动作名称(或动作类型)3...]
		append:[],
		
		inArray : function(value,array){
			for(var k in array){
				if(array[k] === value){
					return true;
				}
			}
			return false;
		},
		
		//将字符串的首字母大写
		ucFirst : function (str){
			return str.substring(0,1).toUpperCase()+str.substring(1);
		},
		
		//设置cookie
		setCookie : function (name,value,days){
		    var exp = new Date();
		    exp.setTime(exp.getTime() + days*24*60*60*1000);
		    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
		    //设置本地存储
		    eval("("+"localStorage."+name+"="+value+")");
		},
		
		//获取cookie常用方法
		getCookies : function (name){
			if(name && name.length > 0){
				var val = eval('(localStorage.'+name+')');
				if(val){
					return val;
				}
			}
			var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
			if(arr=document.cookie.match(reg)){
				return unescape(arr[2]);
			}
			return  null;
		},
		
	};
	
	return common;
});