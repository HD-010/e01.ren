require.config({
	baseUrl : "/assets/js/app",
	paths : {
		jquery : "./lib/jquery",
		echarts : "http://echarts.e01.ren/echarts"
	},
	shim:{
		echarts :{exports:"echarts"}
	}

});
require(["jquery","echarts"],function($,echarts){
	var oper = new Object();
	
	//异步获取视图组件的接口
	oper.veiwerUri = "http://data-analysis.e01.ren/?r=home/wget/get";
	//所有事件名称的接口
	//oper.namesUri = "http://data-analysis.e01.ren/?r=home/oper/name";
	//oper.attrUri = "http://data-analysis.e01.ren/?r=home/oper/attr";
	
	//为减少请求次数，将oper.namesUri，oper.attrUri合成一次请求
	oper.eventOpt = "http://data-analysis.e01.ren/?r=home/oper/event-opt";
	
	$(document).ready(function(){
		//事件分析操作对象
		oper.eventsOpt = $("span[name=eventsOpt]");
		//漏斗分析操作对象
		oper.funnelOpt = $("span[name=funnelOpt]");
		//留存分析操作对象
		oper.retainedOpt = $("span[name=retainedOpt]");
		//分布分析操作对象
		oper.distributionOpt = $("span[name=distributionOpt]");
		//用户路径作对象
		oper.routeOpt = $("span[name=routeOpt]");
		//点击分析操作对象
		oper.clickOpt = $("span[name=clickOpt]");
		//用户分类作对象
		oper.userTypeOpt = $("span[name=userTypeOpt]");
		//属性分析作对象
		oper.attributeOpt = $("span[name=attributeOpt]");
		
		
		//操作对象对应的动作
		oper.eventsOpt.click(function(){
			$.ajax({
				url:oper.veiwerUri,
				async:true,
				type:'post',
				data:'wgetName=eventOpt',
				success:function(data,status){
					if(status === 'success'){
						var contents = $("div[name=contents]");
						var eventsOpt = $("div[name=eventsOpt]")
						if(eventsOpt.length){
							eventsOpt = data;
						}else{
							contents.append(data);
						}
					}
				}
			});
			
			//获取事件分析选项中显示事件名称项内容
			$.ajax({
				url:oper.eventOpt,
				async:true,
				type:'post',
				data:'1',
				dataType:"json",
				success:function(data,status){
					if(status === 'success'){
						//添加事件名称项内容
						var optHtml = "";
						var data = data.data.names;
						if((data.status == 1))
						for(var i=0; i < data.length; i ++){
							optHtml += "<option value="+data[i].events+">"+data[i].events+"</option>" ;
						}
						$("select[name=eventName]").append(optHtml);
						
						//添加事件属性项内容
						optHtml = "";
						data = data.data.attrs;
						for(var i=0; i < data.length; i ++){
							optHtml += "<option value="+data[i].column_name +">"+data[i].column_name+"</option>" ;
						}
						$("select[name=eventAttr]").append(optHtml);
					}
				}
			});
			
		});
		//操作对象对应的动作
		oper.funnelOpt.click(function(){
			console.log(this);
		});
		//操作对象对应的动作
		oper.retainedOpt.click(function(){
			console.log(this);
		});
		//操作对象对应的动作
		oper.distributionOpt.click(function(){
			console.log(this);
		});
		//操作对象对应的动作
		oper.routeOpt.click(function(){
			console.log(this);
		});
		//操作对象对应的动作
		oper.clickOpt.click(function(){
			console.log(this);
		});
		//操作对象对应的动作
		oper.userTypeOpt.click(function(){
			console.log(this);
		});
		//操作对象对应的动作
		oper.clickOpt.click(function(){
			console.log(this);
		});
		//操作对象对应的动作
		oper.attributeOpt.click(function(){
			console.log(this);
		});
	});
});








































/*// 基于准备好的dom，初始化echarts实例
var myChart = echarts.init(document.getElementById('main'));

// 指定图表的配置项和数据
var option = {
    title: {
        text: 'ECharts 入门示例'
    },
    tooltip: {},
    legend: {
        data:['销量']
    },
    xAxis: {
        data: ["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"]
    },
    yAxis: {},
    series: [{
        name: '销量',
        type: 'bar',
        data: [5, 20, 36, 10, 10, 20]
    }]
};

// 使用刚指定的配置项和数据显示图表。
myChart.setOption(option);*/