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
			$e("form[name='singIn']").valid({
				option : [["input[name=uname]"]],
				rule : "isMobilOrEmail", 
				message : "请输入电子邮箱或手机号",
			});
		},
		/**
		 * 验证用户密码是否正确
		 */
		checkPswd : function(obj){
			$e("form[name='singIn']").valid({
				option : [["input[name=pswd]"]],
				rule : "isPasswd", 
				message : "只能输入6-20个字母、数字、下划线",
			});
		},
		/**
		 * 登录验证及登录成功重新加载页面
		 */
		toSubmit : function(){
			//登录前判断，已经登录的用户不能重新登录
			//if(this.isSingIn()) return;
			//登录前必填项验证
			$e("form[name=singIn]").required([
			    "input[name=uname]",                                
			    "input[name=pswd]",                                
			]).submit({
				url:this.url+"/sing-in",
				dataType:"JSON",
				success:function(data){
					console.log(data)
					if(data.state == 'success'){
						sessionStorage.setItem("isGuest",false);
						var nick = data.uname || data.id;
						sessionStorage.setItem("nick",nick);
						document.location.reload();
					}
				},
				error:'login.error'
			});
		},
		
		/**
		 * 登录前判断，已经登录的用户不能重新登录
		 */
		isSingIn : function(){
			if(sessionStorage.getItem('isGuest') == 'false'){
				return true;
			}
		},
		
		/*-------------------------用户注册验证-----------------------*/
		/**
		 * 验证注册用户名
		 */
		singUpUName : function(o){
			$e("form[name='singUp']").valid({
				option : [["input[name=uname]"]],
				rule : "isMobilOrEmail", 
				message : "请输入电子邮箱或手机号",
			});
		},
		/**
		 * 验证用户密码
		 */
		singUpPswd1 : function(obj){
			$e("form[name='singUp']").valid({
				option : [["input[name=pswd]","input[name=pswd2]"],"==","两次输入密码不一致"],
				rule : "isPasswd", 
				message : "只能输入6-20个字母、数字、下划线",
			});
		},
		/**
		 * 验证用户密码确认
		 */
		singUpPswd2 : function(obj){
			$e("form[name='singUp']").valid({
				option : [["input[name=pswd]","input[name=pswd2]"],"==","两次输入密码不一致"],
				rule : "isPasswd", 
				message : "只能输入6-20个字母、数字、下划线",
			});
		},
		/**
		 * 验证验证码有效性
		 */
		singUpVerification : function(obj){
			$e("form[name='singUp']").valid({
				option : [["input[name=Verification]"]],
				rule : "isDigit", 
			});
		},
		/**
		 * 完成注册表单提交,注册成功后动登录 
		 */
		singUpSubmit : function(){
			$e("form[name='singUp']").required([
			    "input[name=uname]",                                
			    "input[name=pswd]",                                
			    "input[name=pswd2]",                               
			    "input[name=Verification]",                               
			]).submit({
				url:this.url+"/sing-up",
				dataType:"JSON",
				success:function(data){
					
					if(data.state == 'success'){
						//显示注册 成功提示信息
						var message = $e().msg("注册成功，正在登录...");
						$("div[name=singUpTitle]").append(message);
						login.data = data;
						//三秒后自动完成登录
						setTimeout(login.singUpToIn,3000);
					}
				},
				error:'login.error'
			});
			
		},
		
		//注册成功后自动登录
		singUpToIn:function(){
			$.ajax({
				url:login.url+"/sing-in",
				dataType:"JSON",
				data:login.data,
				success:function(data){
					console.log(data);
					if(data.state == 'success'){
						sessionStorage.setItem("isGuest",false);
						var nick = data.uname || data.id;
						sessionStorage.setItem("nick",nick);
						document.location.reload();
					}
				},
				error:'login.error'
			})
		},
		
		
	};
	
	return login;
})