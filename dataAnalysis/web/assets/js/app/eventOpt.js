/**
 * 
 */
require.config({
	baseUrl : "/assets/js/app",
	paths : {
		jquery : "./lib/jquery",
		echarts : "http://echarts.e01.ren/echarts",
	},
	shim:{
		echarts :{exports:"echarts"},
	}

});

require(["jquery","echarts"],function($,echarts){


	
});