/**
 * 用户登录状态管理
 * 
 */
define([ "jquery" ], function($) {
	var loginState = {
		/**
		 * 用户昵称显示控制
		 * 当前操作已经在header_normal.js向系统进程sys.js注册过
		 */
		showNick : function(){
			console.log("loginState.showNick运行成功...");
			if(sessionStorage.isGuest == 'fase'){
				$("li[name=nickName]").text(sessionStorage.nick);
			}else{
				$("li[name=nickName]").text("");
			}
		}
	};
	
	return loginState;
})