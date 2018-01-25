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
			console.log(this);
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