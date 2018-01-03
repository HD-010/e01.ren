/**
 * 用户登录状态管理
 * 
 */
define(["sys","jquery","common","easyForm"], function(sys,$,common,$e) {
	//向系统进程注册用户昵称显示控制
	sys.regest('login.run');
	var login = {
		//服务地址
		url : "http://passport.e01.ren/?r=openapi/login",
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
			if(!sessionStorage.getItem("isGuest") == "false"){
				$("li[name=nickName]").text(sessionStorage.getItem("nick"));
			}else{
				$("li[name=nickName]").text("");
			}
		},
		
		//登录与退出的状态控制
		stateCtl : function (){
			if(sessionStorage.getItem("isGuest") != "false"){
				//显示登录按钮
				$("a[name='login|out']").text("登录系统");
				//清除用户昵称
				$("li[name='nickName']").text("");
			}else{
				//显示退出按钮
				$("a[name='login|out']").text("退出 [->]");
				//显示用户昵称
				$("li[name='nickName']").text(sessionStorage.getItem("nick"));
			}
		},
		
		//登录管理action为操作方法的名称
		//loginOut : function(action){
			//eval("(login."+action+"())");
		//},
		
		//登录方法
		singin : function(){
			
			console.log("登录成功...")
		},
		//登录方法
		singout : function(){
			sessionStorage.removeItem("isGuest");
			sessionStorage.removeItem("nick");
			console.log("退出成功...")
		},
		
		/*---------------------用户登录验证-----------------------*/
		/**
		 * 验证用户名是否存在
		 */
		checkUName : function(obj){
			console.log("用户名不存在");
			return "用户名不存在";
		},
		/**
		 * 验证用户密码是否正确
		 */
		checkPswd : function(obj){
			return "密码不存在";
		},
		/**
		 * 验证用户名是否存在
		 */
		toSubmit : function(obj){
			return "登录成功";
		},
		
		/*-------------------------用户注册验证-----------------------*/
		/**
		 * 验证注册用户名
		 */
		singUpUName : function(o){
			$e("form[name='singUp']").valid({
				option : [["input[name=uname]"]],
				rule : "isTrueName", 
				message : "只能输入1-30个以字母开头的字串",
			});
		},
		/**
		 * 验证用户密码
		 */
		singUpPswd1 : function(obj){
			$e("form[name='singUp']").valid({
				option : [["input[name=pswd1]"]],
				rule : "isPasswd", 
				message : "只能输入6-20个字母、数字、下划线",
			});
		},
		/**
		 * 验证用户密码确认
		 */
		singUpPswd2 : function(obj){
			$e("form[name='singUp']").valid({
				option : [["input[name=pswd2]"]],
				rule : "isPasswd", 
				message : "只能输入6-20个字母、数字、下划线",
			});
		},
		/**
		 * 验证验证码有效性
		 */
		singUpVerification : function(obj){
			
		},
		/**
		 * 完成注册表单提交
		 */
		singUpSubmit : function(){
			
			$e("form[name='singUp']").required([
			    "input[name=uname]",                                
			    "input[name=pswd1]",                                
			    "input[name=pswd2]"                               
			]).submit();
		},
		
	};
	
	return login;
})