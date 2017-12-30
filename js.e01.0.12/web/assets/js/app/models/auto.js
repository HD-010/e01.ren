/*
 * 自启动项对象
 * 设置启动项,将需要自启动的项名称加入setOption
 * 启动项的值 以'true'或'false'两种状态存在于cookie中，值为'true'的项将跟随进程自动启动
 */
define(["sys","jquery","common","logObj"],function(sys,$,common,logObj){
	//将当前模块的进程的启动管理名称写入系统进程管理项
	sys.regest('auto.run');
	var auto = {
		//启动状态 string on | off
		state : 'off',
		//设置启动项,将需要自启动的项名称加入setOption
		setOption : [
	         'showOnChang',
	         'autoFormate',
	         'loginState',
	         'friendsState'
         ],
         //元素名称与其属性的依赖关系	//common.append.push([["autoFormate","friendsState"],"showOnChang","disabled|enlabled"]);
 		 append : [],
         //启动进程
         process : '',	
         //启动项（该值对应相应的函数）
         option : [],
         //检查启动项,将需要启动的项目添加到option中
         check : function(){
        	 auto.option = [];
        	 for(var o in auto.setOption){
        		 if(common.getCookies(auto.setOption[o]) == 'true'){
        			 auto.option.push(auto.setOption[o]);
        		 }
        	 }
         },
         
         //运行启动项
         run : function (){
        	 
        	 console.log("auto.run准备运行...");
        	 auto.check();
        	 console.log("auto.run运行成功...");
        	 //如果启动项为空，或则父级开关关闭，则关掉进程
        	 if(auto.option.length == 0 || (common.inArray("showOnChang",auto.option) == -1)){
        		 //关闭自动刷新状态
        		 auto.state = "off";
        		 //关闭自动格式化日志状态
        		 logObj.auto = false;
        		 //关闭自动运行进程
        		 //clearInterval(auto.process);
        		 sys.stop('auto.run');
        		 return;
        	 }
        	 //修改启动状态为on
        	 auto.state = "on";
        	 //开启自动格式化日志状态
        	 logObj.auto = true;
        	 //执行启动项动作
        	 for(var a in auto.option){
        		 eval("(auto."+auto.option[a]+"())");
        	 }
         },
         
         /*---------------以下是启动项对应的动作-----------------------*/
         //自动刷新页面显示
         showOnChang : function (){
        	 // 获取日志内容并将日志内容显示到布局中
        	 logObj.tranceData('debug','read',logObj.tranceLog,logObj.clientUrl);
         },
         
         //自动格式化最后一条记录
         autoFormate : function (){
        	 //获取页面中可以格式化的代码块
        	 var codes = $("span[name=ft]");
        	 //调用模式化程序
        	 logObj.formateCode(codes.eq(0),'auto');
        	 if(!logObj.pause){
        		 $("[name=codeList]").children().remove();
        	 }
         },
         
         //监测好友状态
         friendsState : function(){
        	 
         },
         
       //将表单属性写入cookie,时长7天,affect boolen 最不影响其他输入对象
 		setCookieOption : function (obj){
 			//暂停进程
 			//clearInterval(auto.process);
 			sys.stop();
 			var name,val,type;
 			
 			//输入对象的名称 
 			name = $(obj).attr('name');
 			//输入对象的值
 			val = $(obj).val();
 			//输入对象的类型
 			type = $(obj).attr('type');
 			
 			//为输入对象的类型为checkbox的val赋值
 			if(type == 'checkbox'){
 				val =  $(obj).is(":checked");
 				//console.log($("input[name=autoFormate]").is(":disabled"));
 				for(var e in auto.append){
 					//如果当前元素在append中，则检查和它相关边的元素，并使用相关动作进行作用
 					if(name == auto.append[e][1]){
 						for(var a in auto.append[e][0]){
 							//元素名称对应的对象
 							var appendA = $("input[name="+auto.append[e][0][a]+"]");
 							//当前元的值
 							if(val){
 								appendA.removeAttr("disabled");
 							}else{
 								appendA.attr("disabled",true);
 							}
 						}
 					}
 				}
 			}
 			
 			//将输入对象的名称和值写入cookie
 			common.setCookie(name,val,7);
 			
 			//开启进程
 			//auto.process = setInterval(auto.run,1000);
 			sys.start();
 		},
         /*------------------------------------------------------*/
	};
	
	return auto;
});