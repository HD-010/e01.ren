/**
 * 
 */
define(["jquery","common"],function($,common){
	// 从测试环境获取日志对象
	var logObj = {
		// 调试环境baseUrl
		clientUrl : common.getCookies("clientUrl"),
		//
		baseUrl : function(){
			//console.log(this.clientUrl+"78876666");
		},
		serverUrl : "http://js.e01.ren/?r=edbug",
		//格式化代码展示背景色
		baseColor : [ "#006600", "#CC66CC", "#666666", "#336633", "#996699", "#FF66FF","#B8860B","#000080","#48D1CC"],
		//当前使用颜色
		color:"",
		// 日志对象 ,一条记录
		logData : "",
		//日志 序列号
		sialize : "",
		// 日志读取标识
		readTag : 'log',
		
		//格式化前的json代码
		code:"",
		//上一次格式化代码的结果（boolen true | false）
		preState : false,
		//自动获取日志状态是否开启boolen true | false
		auto : false,
		//是否已经暂停向codeList容器写入boolen true | false
		pause : false,
		
		// 按块显示日志
		viewLog:function(data, status) {
			if (status == 'success') {
				$('[name="contents"]').html($(data));
				$('span[name="ft"]').on("click",function(){
					logObj.formateCode(this);
				});
			}
		},
		
		// 获取要显示的日志内容
		tranceLog:function(data, status) {
			if (status == 'success') {
				// 获取数据的开始位置
				var strStart = data.indexOf('`DS`');
				status = data.substr(strStart + 7, 1);
				var strEnd = data.indexOf('`DE`');
				// 有用数据字串
				var dataStr = data.substring(strStart + 11, strEnd - 3);
				if (status == 'F') {
					viewLog('还没有日志', 'success');
				} else {
					logObj.tranceData(dataStr,'parseFormate',logObj.viewLog);
				}
			}
		},
		
		//设置代码格式化背景
		setColor:function(o, sialize) {
			if (sialize) {
				this.color = this.baseColor.shift();
				$(o).css({
					"background-color" : this.color,
					"color" : "#FFFFFF"
				});
				$(o).text("取消格式化");
				$("div[sialize=" + sialize + "]").css("background-color", this.color);
				this.baseColor.push(this.color);
			} else {
				this.color = $(o).css("background-color");
				$(o).text("格式化代码");
				$(o).css("background-color", "#9F9F9F");
			}
		},
	    //设置格式化代码显示尺寸
		setViewSize:function() {
			var views = $("div[name=codeList]").children().length;
			views = views > 4 ? 4 : views;
			var width = 80 / views + "%";
			$("div[name=list]").width(width);
			var height = screen.availHeight - 180 + "px";
			$("div[name=list]").height(height);
		},
		//根据sialize删除格式化后的代码
		removeCode:function(){
			$(this.logData).attr("sialize", "");
			$("div[sialize=" + this.logData.sialize + "]").remove();
			this.setViewSize();
			this.setColor(this.logData, 0);
		},
		
		//展示格式化后的代码
		viewCode:function(data,status){
			//将返回的结果写入状态
			if(!data){
				logObj.preState = false;
				return;
			}
			logObj.preState = true;
			//将data呈现到codeList容器中
			str = "<div name=list sialize="	+ logObj.logData.sialize + ">" + data + "</div>";
			var codeList = $("[name=codeList]").html();
			$("[name='codeList']").html(codeList + str);
			logObj.setViewSize();
			logObj.setColor(logObj.logData, logObj.logData.sialize);
		},
		
		/**
		 * 传输待处理数据 格式化json代码
		 * oper  操作方式
		 * data  需要处理的数据
		 * callBack 回调函数
		 * uri 测试服端接口 该参数默认是服务端接口
		 */
		tranceData:function(data,oper,callBack,uri){
			console.log(data);
			console.log(oper);
			console.log(uri);
			$.ajax({
				url : uri || logObj.serverUrl,
				type : 'POST',
				dataType : 'TEXT',
				data : {
					oper : oper,
					data : data,
					showLines : common.getCookies("showLines") || 5,
				},
				success : callBack,
				error : function(data) {
					console.log(data);
				},
			});
		},
		
		//根据sialize添加格式化后的代码
		addCode:function(o, sialize){
			//添加格式化后的代码
			logObj.logData.sialize = Date.parse(new Date());
			$(logObj.logData).attr("sialize", logObj.logData.sialize);
			var code = $(logObj.logData).siblings("[name='code']").text();
			//如果当前代码来自自动进程且已经格式代成功，则不需要重复格式化
			if((code === logObj.code) && logObj.preState && logObj.auto) {
				logObj.pause = true;
				return;
			}
			//记录当前需要格式代的代码
			logObj.code = code;
			//打开codeList容器，
			logObj.pause = false;
			//将内容载入传输工具
			logObj.tranceData(logObj.code,'codeFormate',logObj.viewCode);
		},
		
		//格式化o对象中的代码,souce为调用来源，比如souce="auto"为auto对象调用
		formateCode:function(o,souce){
			//调用来源，表示是手动点击调用或自动进行调用或其它，默认为空
			souce = souce || "";
			//当开启自动刷新，则禁止手动格式化代码
			if(this.auto && !souce){
				alert("自动刷新状态，禁止手动格式化代码");
				return;
			}
			//手动格式化代码
			this.logData = o;
			this.logData.sialize = $(this.logData).attr("sialize");
			//sialize不知存在则添加格式代码，存在则删除
			this.logData.sialize ? this.removeCode() : this.addCode();
		},
		
		//清空测试服务端日志
		clear : function (){
			var url = this.clientUrl;
			this.tranceData('clear','clear',function(data){
				logObj.tranceData(data,'func',function(data){
					console.log(data);
					//清空日志后以重新读取日志的方式来刷新页面，保证控制台日志可读
					logObj.tranceData('debug','read',logObj.tranceLog,logObj.clientUrl);
				},url);
			},url);
		},

	};
	
	return logObj;
});