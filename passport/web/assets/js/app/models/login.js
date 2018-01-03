/**
 * 用户登录状态管理
 * 
 */
define(["sys","jquery","common"], function(sys,$,common) {
	//向系统进程注册用户昵称显示控制
	sys.regest('login.run');
	var login = {
		//服务地址
		url : "http://js.e01.ren/?r=passport/login",
		//运行管理——项（该值对应相应的函数）
		option : ["nick","stateCtl"],	
		
		//运行管理——控制
		run : function(){
			for(var o in login.option){
				eval("(login."+login.option[o]+"()"+")");
			}
		},
		
		/*---------------以下是login启动项对应的动作-----------------------*/
		/**
		 * 用户昵称显示控制
		 * 当前操作已经在header_normal.js向系统进程sys.js注册过
		 */
		nick : function(){
			if(sessionStorage.isGuest == 'false'){
				$("li[name=nickName]").text(sessionStorage.nick);
			}else{
				$("li[name=nickName]").text("");
			}
		},
		
		//登录与退出的状态控制
		stateCtl : function (){
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
		},
		
		//登录管理action为操作方法的名称
		loginOut : function(action){
			eval("(login."+action+"())");
		},
		
		//登录方法
		login : function(){
			
			
			
			console.log("登录成功...")
		},
		//登录方法
		out : function(){
			sessionStorage.isGuest = 'true';
			sessionStorage.nick = "";
			console.log("退出成功...")
		}
	};
	
	return login;
})