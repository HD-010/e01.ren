/**
 * 配置文件 
 */
define(function(require){
	var interf  = {
		//事件分析－事件选项卡 视图接口
		eventOpt : "/?r=home/oper/event-opt",
		//事件分析－数据接口uri
		eventData : "/?r=home/events/data",
		//异步获取视图组件的接口uri
		veiwerUri : "/?r=home/wget/get",
	}
	
	//将以上配置项封装到 conf 对象中
	var config = {
		interf : interf,
	}
	return config;
});