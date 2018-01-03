/**
 * 客户端首页
 */
require.config({
	baseUrl : "/assets/js/app",
	paths : {
		sys : "./models/sys",
		jquery : "./lib/jquery",
		common : "./models/common",
		auto : "./models/auto",
		logObj : "./models/logObj",
	}
});
require(["jquery","common","auto","logObj"],
	function($,common,auto,logObj){
		/*-----------------------以下设设置系统参数------------------------*/
		//设置客户端url
		localStorage.clientUrl = common.getCookies("clientUrl");
		
	
		
		
		/*------------------------------加载日志------------------------------*/
		logObj.tranceData('debug','read',logObj.tranceLog,logObj.clientUrl);
		
	}
);

