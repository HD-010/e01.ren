/**
 * 视图小部件
 */
define(["jquery","common","logObj","auto"],function($,common,logObj,auto){
	var wget = {
		//小部件数据容器
		data : "",
		
		//已经打开的小部件（注：该标识用于，如是某小部件已经打开则不能重复打开）
		isOn : [],
		
		//小部件数据控制器，检测data是否为空，如果不为空则执行下的语句，如果为空则向服务端请求数据
		dataControl : function(funcName){
			if(!wget.data){
				funcName = common.ucFirst(funcName);
				eval("(this.get"+funcName+"())");
				return;
			}else{
				var myData = wget.data;
				wget.data = "";
				return myData
			}
		},
		
		//用户偏好设置视图部件
		message : function (){
			var str = this.closeBt();
			str += "提示：？？？？？";					//将提示内容与关闭按钮合并
			str = this.addFunc("noticeBack",str);	//添加内容到提示框
			str = this.addFunc("mask",str);			//添加内容到蒙版
			str = this.addFunc("fullMask",str);		//添加内容到全屏蒙版
			this.setWget(str);
		},
		
		/**
		 * 属性设置视图部件
		 */
		setSys : function (){
			//当前小部件名称
			var name = "wgetSys";
			//检查妆前小部件是否已经开启，如果已经开启则不需要重复开启
			if(common.inArray(name,this.isOn) != -1){return false;}
			//如果 wget.data为空，则从getSysAttr()获取数据
			var data = this.dataControl("sysAttr");	
			if(!data){return;}
			//添加内容到容器
			str = this.addFunc("setUpBack",data);
			//添加内容到蒙版
			str = this.addFunc("mask",str);	
			//添加内容到全屏蒙版
			str = this.addFunc("fullMask",str);		
			this.setWget(str);
			//打开小部件后，将其名称加入isOn数组
			this.isOn.push(name);
		},
		
		/*-------------------------------------以下为视图部件区--------------------------------------*/
		// 全屏遮罩
		fullMask : function(content) {
			content = content || "这里添加内容";
			return "<div name=maskParent><div name=maskParentTag></div>" + content + "</div>";
		},
		
		//部份遮罩
		mask : function(content){
			content = content || "这里添加内容";
			return "<div name=mask>"+content+"</div>";
		},
		
		//提示框背景
		noticeBack : function (content){
			return "<div name=noticeBack>"+content+"</div>";
		},
		
		//属性设置背景
		setUpBack : function (content){
			return "<div name=setUpBack>"+content+"</div>";
		},
		
		
		//将contents添加到视图部件func
		addFunc : function(func,contents){
			return eval("(this." + func + "(contents))");
		},
		
		//将视图部件添加到obj对象
		setWget : function(contents,obj){
			obj = obj || document.body;
			$(obj).append(contents);
			$("input[name='showLines']").on("change",function(){auto.setCookieOption(this)});
			$("input[name='showOnChang']").on("click",function(){auto.setCookieOption(this)});
			$("input[name='autoFormate']").on("click",function(){auto.setCookieOption(this)});
			$("input[name='loginState']").on("click",function(){auto.setCookieOption(this)});
			$("input[name='friendsState']").on("click",function(){auto.setCookieOption(this)});
			$("a[name='colseBt']").on("click",function(){wget.closeBox()});
			$("div[name='maskParentTag']").on("click",function(){wget.cancleMask()});
		
		},
		
		
		/**
		 * 关蒙版信息
		 */
		closeBox : function (){
			alert("该方法已经转移到wget.cancleMask（）");
			$("div[name=maskParent]").remove();
		},
		
		cancleMask : function(obj){
			//移出蒙版内容
			$("div[name=maskParent]").remove();
			//清空isOn是的元素
			this.isOn = [];
		},
		
		closeBt : function (){
			return "<div name=colseBt><a>&#215;</a></div>";
		},
		/*-------------------------------------end--------------------------------------*/
		
		/*-------------------------------------以下为视图部件内容区--------------------------*/
		getSysAttr : function(){
			//从服务端获取属性设置具体内容
			var data = "sysAttr";
			var oper = "wget";
			logObj.tranceData(data,oper,function(data,status){
				if(status == 'success'){
					wget.data = data;
					wget.setSys();
				}
			});
		},
		
		
		/*-------------------------------------end--------------------------------------*/
	}
	
	return wget;
})