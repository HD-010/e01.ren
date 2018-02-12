/**
 * 
 */
require.config({
	paths : {
		jquery : "http://cdn.e01.ren/common/lib/jquery",
		echarts : "http://cdn.e01.ren/charts/echarts/echarts",
	},
	shim:{
		echarts :{exports:"echarts"},
	}

});

require(["jquery","echarts"],function($,echarts){


	
});