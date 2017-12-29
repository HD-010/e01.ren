/**
 * 头部_常规
 */
require.config({
	baseUrl : "/assets/js/app",
	paths : {
		jquery : "./lib/jquery",
		wget : "./models/wget",
		logObj : "./models/logObj",
		sys : "./models/sys",
	}
});

require(["sys","jquery","logObj","wget"],function(sys,$,logObj,wget){
	//开启系统进程
	sys.start();
	//向系统进程注册用户昵称显示控制
	sys.regest('loginState.showNick');
	
	/*-----------------------根据实际情况展示用户登录状态------------------------*/
	if(typeof(Storage)!=="undefined"){
		
		/**start 预置系统参数**/
		sessionStorage.isGuest = false;
		sessionStorage.nick = "婉清";
		/**end**/
		
		if(sessionStorage.isGuest == 'true'){
			//显示登录按钮
			$("a[name='login|out']").text("登录系统");
			//清除用户昵称
			$("li[name='nickName']").text("");
		}else{
			//显示退出按钮
			$("a[name='login|out']").text("退出 [->]");
			//显示用户昵称
			$("li[name='nickName']").text(sessionStorage.nick);
		}
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
		wget.setSys();
	});
	//点击空白处移除蒙版
	$("div[name=maskTag]").on("click",function(){
		$("div[name=parentMask]").remove();
	});
	
	
	//访客操作项目
	if(sessionStorage.isGuest == 'true'){
		//点击登录链接，在蒙版上显示登录框
		$("a[name='login|out']").on("click",function (){
			alert("登录成功");
		});
	}
	
	//登录用户操作项目
	if(sessionStorage.isGuest == 'false'){
		$("a[name='login|out']").on("click",function (){
			alert("退出成功");
		});
	}
});