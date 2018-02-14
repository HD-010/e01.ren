/**
 * 当前模块用于将传入的数据可视化
 */
define(function(require){
	var echarts = require("echarts");
	var charts = {
		/*
		 * 绘制图表
		 * param id string 容器的id号
		 * param optionContent object 生成图表的具体数据
		 */
		make:function(id,optionContent){
			// 基于准备好的dom，初始化echarts实例
		    var myChart = echarts.init(document.getElementById(id));
		    // 使用刚指定的配置项和数据显示图表。
		    myChart.setOption(optionContent);
		}
	}
	
	return charts;
});