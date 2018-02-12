/**
 * 控制左则导航栏，相关内容如下:
 * 1、项目显示与隐藏
 * 2、被选择项的数据控制
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
	//默认所有项目不展开，为css默认设置
	//关闭所有项目的展开状态
	function hid(){
		$(".navN01").find("ul").hide();
	}
	

	
	var oper = new Object();
	
	//异步获取视图组件的接口
	oper.veiwerUri = "http://data-analysis.e01.ren/?r=home/wget/get";
	//所有事件名称的接口
	//oper.namesUri = "http://data-analysis.e01.ren/?r=home/oper/name";
	//oper.attrUri = "http://data-analysis.e01.ren/?r=home/oper/attr";
	
	
	
	$(document).ready(function(){
		
		//被选中的项目展开
		$('font[name="option"]').click(function(){
			//点击时更换项目名称前的小标签
			var tag = $(this).siblings('font[name="littag"]');
			var littag = tag.text() == '▸' ? '▾' : '▸';
			tag.text(littag);
			
			//点击时隐藏或展开项目子类名称
			$(this).parent().parent().find("ul").toggle();
		});
		
		
		
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
		
		
		//为减少请求次数，将oper.namesUri，oper.attrUri合成一次请求
		oper.eventOpt = "http://data-analysis.e01.ren/?r=home/oper/event-opt";
		//事件分析操作对象
		oper.eventsOpt = $("span[name=eventsOpt]");
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
					//console.log(data);
					if(status === 'success'){
						//添加事件名称项内容
						var optHtml = "";
						var names = data.names;
						if((names.status == 1))
						for(var i=0; i < names.data.length; i ++){
							optHtml += "<option value="+names.data[i].events+">"+names.data[i].events+"</option>" ;
						}
						$("select[name=eventName]").append(optHtml);
						
						//添加事件属性项内容
						optHtml = "";
						var attrs = data.attrs;
						for(var i=0; i < attrs.data.length; i ++){
							optHtml += "<option value="+attrs.data[i].column_name +">"+attrs.data[i].column_name+"</option>" ;
						}
						$("select[name=eventAttr]").append(optHtml);
					}
				}
			});
			
		});
		
		//操作对象对应的动作
		oper.funnelOpt.click(function(){
			//console.log(this);
		});
		//操作对象对应的动作
		oper.retainedOpt.click(function(){
			//console.log(this);
		});
		//操作对象对应的动作
		oper.distributionOpt.click(function(){
			//console.log(this);
		});
		//操作对象对应的动作
		oper.routeOpt.click(function(){
			//console.log(this);
		});
		//操作对象对应的动作
		oper.clickOpt.click(function(){
			//console.log(this);
		});
		//操作对象对应的动作
		oper.userTypeOpt.click(function(){
			//console.log(this);
		});
		//操作对象对应的动作
		oper.clickOpt.click(function(){
			//console.log(this);
		});
		//操作对象对应的动作
		oper.attributeOpt.click(function(){
			//console.log(this);
		});
	});
	
});