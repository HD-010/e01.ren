/**
 * 视图小部件
 */
define(function(require){
	var $ = require("jquery");
	var common = require("common");
	var logObj = require("logObj");
	var auto = require("auto");
	
	var wget = {
		//系统自动加载的模型名称
		loadModel : [],
		//小部件数据容器
		data : [],
		//已经打开的小部件（注：该标识用于，如是某小部件已经打开则不能重复打开）
		isOn : [],
		
		/**
		 * 小部件数据控制器，检测data是否为空，如果不为空则执行下面的语句，如果为空则向服务端请求数据
		 * funcName string 小部件模型名称。如：“wgetSingIn”
		 */
		dataControl : function(funcName){
			//如果小部模型名称在loadModel中存在 ，说明该小部件已经加载过。那么在data中也有其对应的数据，只需要将它取出来，不需要再向服务器获取
			if(common.inArray(funcName,wget.loadModel) != -1){
				var index = common.inArray(funcName,wget.loadModel);
				//取出该小部件在data中对应的数据
				return wget.data[index];
			}else{
				var modelName = funcName;
				funcName = common.ucFirst(funcName);
				eval("(" + modelName + ".get"+funcName+"())");
				return;
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
		
		
		/*-------------------------------------以下为视图部件预置组件区--------------------------------------*/
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
		
		/**
		 * 将视图部件添加到obj对象
		 * contents string(html) 视图内容
		 * name string 视图名称
		 * obj Object 添加到的对象，默认添加到document.body对象
		 */
		setWget : function(contents,name,obj){
			obj = obj || document.body;
			$(obj).append(contents);
			//将事件列表绑定事件名称对应的视图
			eval("("+name+".bind"+common.ucFirst(name)+"Event())");
		},
		
		
		/**
		 * 关闭蒙版信息
		 */
		closeBox : function (){
			alert("该方法已经转移到wget.cancleMask（）");
			$("div[name=maskParent]").remove();
		},
		
		//当点击背景为白色的区域，关闭蒙版
		cancleMask : function(obj){
			//移出蒙版内容
			$("div[name=maskParent]").remove();
			//清空isOn是的元素
			wget.isOn = [];
		},
		
		
		//关闭按钮
		closeBt : function (){
			return "<div name=colseBt><a>&#215;</a></div>";
		},
		/*-------------------------------------end--------------------------------------*/
		
		/*-------------------------------------视图对外接口--------------------------------------*/
		/**
		 * 自动加载小部件接口
		 * wgetName string 小部件名称
		 */
		regest : function(wgetName){
			//在加载一个小部件前，先清空蒙版。以免蒙版上出现多小部件堆叠
			wget.cancleMask();
			//如果传来的模块名称为空，则不再往下执行
			if(typeof(wgetName) == "undefined"){ return;}
			//如果对的模块没有加载，则先加载
			if(common.inArray(wgetName, wget.loadModel) == -1){
				eval('('+ wgetName + '= require("' + wgetName + '")' +')');
				//将加载完成的模记入wget.loadModel
				wget.loadModel.push(wgetName);
			}
			var actionName = common.ucFirst(wgetName);
			//调用视图部件的接口
			eval("("+ wgetName + ".set" + actionName + "()" +")");
			//获取系统属性设置视图内容
			eval("("+ wgetName + ".get" + actionName + "()" +")");
			//绑定视图事件列表
			eval("("+ wgetName + ".bind" + actionName + "Event()" +")");
		},
		
		/**
		 * 清空小部件数据容器
		 * 该方法用于视图加载前。如果当前需要加载的小部件名称和当前小部件名称不一致，则将数据清空
		 */
		clear : function (){
			wget.data = "";
		},
		
		/**
		 * 向wget.data添加数据
		 */
		setData : function(data){
			wget.data.push(data);
		}
		
		/*-------------------------------------end--------------------------*/
		
	}
	
	return wget;
})