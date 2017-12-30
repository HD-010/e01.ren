/**
 * 用户登录状态管理
 * 
 */
define(["sys","jquery"], function(sys,$) {
	//向系统进程注册用户昵称显示控制
	sys.regest('login.nick');
	var login = {
		/**
		 * 用户昵称显示控制
		 * 当前操作已经在header_normal.js向系统进程sys.js注册过
		 */
		nick : function(){
			console.log("login.nick运行成功...");
			if(sessionStorage.isGuest == 'fase'){
				$("li[name=nickName]").text(sessionStorage.nick);
			}else{
				$("li[name=nickName]").text("");
			}
		}
	};
	
	return login;
})