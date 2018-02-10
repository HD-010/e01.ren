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
	//数据接口
	var serverUrl = "http://data-analysis.e01.ren/?r=home/events/data";
	// 指定图表的配置项和数据
	
	//这是一组默认数据，用于数据加载等待中显示
	var optionContent = {
		    title: {
		        //text: '一天用电量分布',
		        //subtext: '纯属虚构'
		    },
		    tooltip: {
		        trigger: 'axis',
		        axisPointer: {
		            type: 'cross'
		        }
		    },
		    toolbox: {
		        show: true,
		        feature: {
		            saveAsImage: {}
		        }
		    },
		    xAxis:  {
		        type: 'category',
		        boundaryGap: false,
		        data: ['00:00', '01:15', '02:30', '03:45', '05:00', '06:15', '07:30', '08:45', '10:00', '11:15', '12:30', '13:45', '15:00', '16:15', '17:30', '18:45', '20:00', '21:15', '22:30', '23:45']
		    },
		    yAxis: {
		        type: 'value',
		        axisLabel: {
		            formatter: '{value} W'
		        },
		        axisPointer: {
		            snap: true
		        }
		    },
		    visualMap: {
		        show: false,
		        dimension: 0,
		        pieces: [{
		            lte: 6,
		            color: 'green'
		        }, {
		            gt: 6,
		            lte: 8,
		            color: 'red'
		        }, {
		            gt: 8,
		            lte: 14,
		            color: 'green'
		        }, {
		            gt: 14,
		            lte: 17,
		            color: 'red'
		        }, {
		            gt: 17,
		            color: 'green'
		        }]
		    },
		    series: [
		        {
		            name:'用电量',
		            type:'line',
		            color: 'pink',
		            smooth: true,
		            data: [300, 280, 250, 260, 270, 300, 550, 500, 400, 390, 380, 390, 400, 500, 600, 750, 800, 700, 600, 400],
		            markArea: {
		                data: [ [{
		                    name: '早高峰',
		                    xAxis: '07:30'
		                }, {
		                    xAxis: '10:00'
		                }], [{
		                    name: '晚高峰',
		                    xAxis: '17:30'
		                }, {
		                    xAxis: '21:15'
		                }] ]
		            }
		        },
		        
		    ]
		};
	
	// 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('chartContent'));
    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(optionContent);
    
    //获取真实数据
    $.ajax({
    	url:serverUrl,
    	type:"post",
    	data:getEventOptionsData(),
    	dataType:"JSON",
    	success:function(data,status){
    		console.log(data);
    		if(status == 'success'){
    			optionContent.series[0].data = data;
    			//通过时间日期模块获取xAxis
    			optionContent.xAxis.data = [1,2];
    			// 使用真实数据显示图表。
    			myChart.setOption(optionContent);
    		}
    	}
    });
    
    /**
     * 获取事件选项卡选择的数据
     */
    function getEventOptionsData(){
    	return "data";
    };
});



/*以下为数据原型：
 * 
 * var optionContent = {
		    title: {
		        //text: '一天用电量分布',
		        //subtext: '纯属虚构'
		    },
		    tooltip: {
		        trigger: 'axis',
		        axisPointer: {
		            type: 'cross'
		        }
		    },
		    toolbox: {
		        show: true,
		        feature: {
		            saveAsImage: {}
		        }
		    },
		    xAxis:  {
		        type: 'category',
		        boundaryGap: false,
		        data: ['00:00', '01:15', '02:30', '03:45', '05:00', '06:15', '07:30', '08:45', '10:00', '11:15', '12:30', '13:45', '15:00', '16:15', '17:30', '18:45', '20:00', '21:15', '22:30', '23:45']
		    },
		    yAxis: {
		        type: 'value',
		        axisLabel: {
		            formatter: '{value} W'
		        },
		        axisPointer: {
		            snap: true
		        }
		    },
		    visualMap: {
		        show: false,
		        dimension: 0,
		        pieces: [{
		            lte: 6,
		            color: 'green'
		        }, {
		            gt: 6,
		            lte: 8,
		            color: 'red'
		        }, {
		            gt: 8,
		            lte: 14,
		            color: 'green'
		        }, {
		            gt: 14,
		            lte: 17,
		            color: 'red'
		        }, {
		            gt: 17,
		            color: 'green'
		        }]
		    },
		    series: [
		        {
		            name:'用电量',
		            type:'line',
		            smooth: true,
		            data: [300, 280, 250, 260, 270, 300, 550, 500, 400, 390, 380, 390, 400, 500, 600, 750, 800, 700, 600, 400],
		            markArea: {
		                data: [ [{
		                    name: '早高峰',
		                    xAxis: '07:30'
		                }, {
		                    xAxis: '10:00'
		                }], [{
		                    name: '晚高峰',
		                    xAxis: '17:30'
		                }, {
		                    xAxis: '21:15'
		                }] ]
		            }
		        },
		        //这里有多个对象则有多条曲线....
		    ]
		};
	
	// 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('chartContent'));
    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(optionContent);
 */