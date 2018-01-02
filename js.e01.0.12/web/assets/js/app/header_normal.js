/**
 * 头部_常规
 */
require.config({
	baseUrl : "/assets/js/app",
	paths : {
		jquery : "./lib/jquery",
		easyForm : "./lib/easyForm",
		
		wget : "./models/wget",
		logObj : "./models/logObj",
		sys : "./models/sys",
		login:"./models/login",
		wgetSysAttr:"./models/wgetSysAttr",
		wgetSingIn:"./models/wgetSingIn",
		wgetSingUp :"./models/wgetSingUp",
	}
});

require([
		"sys",
		"jquery",
		"logObj",
		"wget",
		"login",
		"wgetSysAttr",
		"wgetSingIn",
		"wgetSingUp"
	],function(
		sys,
		$,
		logObj,
		wget,
		login,
		wgetSysAttr,
		wgetSingIn,
		wgetSingUp
	){
	
	
	/*-----------------------根据实际情况展示用户登录状态------------------------*/
	if(typeof(Storage)!=="undefined"){
		
		/**start 预置系统参数**/
		sessionStorage.setItem("isGuest",true);
		sessionStorage.setItem("nick","");
		/**end**/
		
	}else{
		alert("浏览器版本过低，不能正常运行！");
	}
	
	
	
	/*-----------------------以下设置事件样式------------------------*/
	//设置事件样式
	$("li[name=t1]").mouseover(function() {
		$("ul[name=t2]").css("display", "block")
	});
	$("li[name=t1]").mouseout(function() {
		$("ul[name=t2]").css("display", "none")
	});
	
	/*-----------------------以下为点击事件------------------------*/
	//清空调试环境生成的日志
	$("a[name=journal]").on("click",function(){
		logObj.clear();
	});
	//设置用户属性
	$("a[name=setSys]").on("click",function(){
		//wget.setSysAttr();
		wgetSysAttr.loadWget();
	});
	//点击空白处移除蒙版
	$("div[name=maskTag]").on("click",function(){
		$("div[name=parentMask]").remove();
	});
	
	
	//访客操作项目
	//点击登录链接，在蒙版上显示登录框
	$("a[name='login|out']").on("click",function (){
		var action = (sessionStorage.getItem("isGuest") == "false") ? "singout" : "singin";
		if(action == "singin"){
			//设置用户登录框 
			wgetSingIn.loadWget();
		}else{
			//用户退出系统
			login.singout();
			console.log("用户退出系统");
		}
		
	});
	
	
});