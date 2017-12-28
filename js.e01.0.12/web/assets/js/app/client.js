/**
 * 客户端首页
 */
require.config({
	baseUrl : "/assets/js/app",
	paths : {
		jquery : "./lib/jquery",
		common : "./models/common",
		wget : "./models/wget",
		auto : "./models/auto",
		logObj : "./models/logObj",
	}
});
require(["jquery","common","wget","auto","logObj"],
	function($,common,wget,auto,logObj){
		/*-----------------------以下设设置系统参数------------------------*/
		//设置客户端url
		localStorage.clientUrl = common.getCookies("clientUrl");
		
	
		/*-----------------------以下设置事件样式------------------------*/
		//设置事件样式
		$("li[name=t1]").mouseover(function() {
			$("ul[name=t2]").css("display", "block")
//			$("ul[name=t2]").css("visibility", "visible")
		});
		$("li[name=t1]").mouseout(function() {
//			$("ul[name=t2]").css("visibility", "hidden")
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
		
		
		/*------------------------------------------------------------*/
		
		/*------------------------------加载日志------------------------------*/
		logObj.tranceData('debug','read',logObj.tranceLog,logObj.clientUrl);
		
		/*------------------------------自动刷新------------------------------*/
		//开户自启动
		auto.process = setInterval(auto.run,1000);
	}
);

