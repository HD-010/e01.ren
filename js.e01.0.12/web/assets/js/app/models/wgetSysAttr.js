/**
 * 系统属性设置视图部件
 */
define(["jquery","common","logObj","wget"],function($,common,logObj,wget){
	var wgetSysAttr = {
		loadWget:function(){
			wget.regest("wgetSysAttr");
		},
		/**
		 * 属性设置视图部件的接口
		 */
		setWgetSysAttr : function (){
			//当前小部件名称
			var name = "wgetSysAttr";
			//检查当前小部件是否已经开启，如果已经开启则不需要重复开启
			if(common.inArray(name,wget.isOn) != -1){return false;}
			//如果 wget.data为空，则从getSysAttr()获取数据
			//var data = wget.dataControl("wgetSysAttr");	
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
		getWgetSysAttr : function(){
			//从服务端获取属性设置具体内容
			var data = "sysAttr";
			var oper = "wget";
			logObj.tranceData(data,oper,function(data,status){
				if(status == 'success'){
					wget.setData(data);
					wgetSysAttr.setWgetSysAttr();
				}
			});
		},
		//系统属性设置视图事件绑定列表
		bindWgetSysAttrEvent : function(){
			$("input[name='showLines']").on("change",function(){auto.setCookieOption(this)});
			$("input[name='showOnChang']").on("click",function(){auto.setCookieOption(this)});
			$("input[name='autoFormate']").on("click",function(){auto.setCookieOption(this)});
			$("input[name='loginState']").on("click",function(){auto.setCookieOption(this)});
			$("input[name='friendsState']").on("click",function(){auto.setCookieOption(this)});
			$("a[name='colseBt']").on("click",function(){wget.closeBox()});
			$("div[name='maskParentTag']").on("click",function(){wget.cancleMask()});
		}
	}
	return wgetSysAttr;
});