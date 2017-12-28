/*
 * 自启动项对象
 * 设置启动项,将需要自启动的项名称加入setOption
 * 启动项的值 以'true'或'false'两种状态存在于cookie中，值为'true'的项将跟随进程自动启动
 */
define(["jquery","common","logObj"],function($,common,logObj){
	var auto = {
		state : 'off',
		//设置启动项,将需要自启动的项名称加入setOption
		setOption : [
	         'showOnChang',
	         'autoFormate',
	         'loginState',
	         'friendsState'
         ],
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
        	 auto.check();
        	 //如果启动项为空，或则父级开关关闭，则关掉进程
        	 if(auto.option.length == 0 || !common.inArray("showOnChang",auto.option)){
        		 //关闭自动刷新状态
        		 auto.state = "off";
        		 //关闭自动格式化日志状态
        		 logObj.auto = false;
        		 //关闭自动运行进程
        		 clearInterval(auto.process);
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
         //检测登录状态
         loginState : function(){
        	 
         },
         //监测好友状态
         friendsState : function(){
        	 
         },
         
       //将表单属性写入cookie,时长7天,affect boolen 最不影响其他输入对象
 		setCookieOption : function (obj){
 			//暂停自动调用进程
 			clearInterval(auto.process);
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
 				for(var e in common.append){
 					//如果当前元素在append中，则检查和它相关边的元素，并使用相关动作进行作用
 					if(name == common.append[e][1]){
 						for(var a in common.append[e][0]){
 							//元素名称对应的对象
 							var appendA = $("input[name="+common.append[e][0][a]+"]");
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
 			
 			//开启自动调用进程
 			auto.process = setInterval(auto.run,1000);
 		},
         /*------------------------------------------------------*/
	};
	
	return auto;
});