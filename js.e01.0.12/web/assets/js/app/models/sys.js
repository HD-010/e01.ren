/**
 * 失败源于当前模块与auto不能相互依赖，如何处理两个模块之前相互调用的问题
 * 系统进程（该进行随浏览器启动，用户不能操作。为顶层）
 * 设计该进程用于用户动态，及系统事务动态
 */
define(function(require){
	var common = require("common");
	var sys = {
		//当前进程名称:如sys表示系统进程名称,auto表示自启动项进程名称
		process:[],
		/**
		 * 进程处理项的名称，当前模块项的名称为单名，如：userSate;
		 * 其他模块的名称以'.'连接,如：auto.run
		 * 该项的值以两种形式存在，第一种是固定存在的，为当前模块启动的项 
		 * 第二种是由程序临时添加的，为后代模块的运行的项
		 */
		option:["loginState"],
		
		//进程管理——暂停(指定的进程或所有进程);
		stop : function(processName){
			processName = processName || "";
			//如果processName进程名存在则将其关闭
			if(processName && (common.arrayKeyExists(processName,this.process) )){
				clearInterval(this.process[processName]);
			}
			//如果processName为空则关闭所有运行的进程
			if(processName == ""){
				for(var pn in this.process){
					clearInterval(this.process[pn]);
				}
			}
		},
		
		//进程管理——启动
		start : function(){
			for(var op in this.option){
				if(this.option[op].indexOf('.') == -1){
					//启动系统级（sys自有）进程
					eval("(this.process['"+this.option[op]+"'] = setInterval(this."+this.option[op]+",1000))");
				}else{
					//自动载入没有加载的模块(就近加载)
					var chain = this.option[op];
					var model = chain.substr(0,chain.indexOf('.'));
					eval("("+model+" = require('"+model+"')"+")");
					eval("(this.process['"+this.option[op]+"'] = setInterval("+this.option[op]+",1000))");
				}
			}
		},
		
		//注册进程，如果当前注册项已经存在则不予处理
		regest : function(processName){
			if(common.inArray(processName,this.option) == -1){
				this.option.push(processName);
				console.log(processName + "注册成功...");
			}
		},
		
        /*---------------以下是系统进程执行的动作-----------------------*/
		//检测登录状态
        loginState : function(){
       	 console.log(sys.process);
        },
	}
	return sys;
});