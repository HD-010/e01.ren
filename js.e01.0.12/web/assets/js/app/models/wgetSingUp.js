/**
 * 登录设置视图部件
 */
define(["jquery","common","logObj","wget","login"],function($,common,logObj,wget,login){
	var wgetSingUp = {
		loadWget:function(){
			wget.regest("wgetSingUp");
		},
		/**
		 * 属性设置视图部件的接口
		 */
		setWgetSingUp : function (){
			//当前小部件名称
			var name = "wgetSingUp";
			//检查当前小部件是否已经开启，如果已经开启则不需要重复开启
			if(common.inArray(name,wget.isOn) != -1){return false;}
			var data = wget.dataControl(name);	
			if(!data){return;}
			//添加内容到容器
			str = wget.addFunc("setUpBack",data);
			//添加内容到蒙版,使用部份遮罩
			str = wget.addFunc("mask",str);	
			//添加内容到全屏蒙版，全屏遮罩
			str = wget.addFunc("fullMask",str);		
			wget.setWget(str,name);
			//打开小部件后，将其名称加入isOn数组
			wget.isOn.push(name);
		},
		//获取系统属性设置视图内容
		getWgetSingUp : function(){
			//从服务端获取属性设置具体内容
			var data = "singUp";
			var oper = "wget";
			logObj.tranceData(data,oper,function(data,status){
				if(status == 'success'){
					wget.setData(data);
					wgetSingUp.setWgetSingUp();
				}
			});
		},
		//系统属性设置视图事件绑定列表
		bindWgetSingUpEvent : function(){
			$("input[name='uname']").on("blur",function(){login.singUpUName(this)});
			$("input[name='pswd[]']").on("blur",function(){login.singUpPswd(this)});
			$("input[name='Verification']").on("blur",function(){login.singUpVerification(this)});
			$("input[name='singUpSubmit']").on("click",function(){login.singUpSubmit()});
			$("div[name='maskParentTag']").on("click",function(){wget.cancleMask()});
		}
	}
	return wgetSingUp;
});