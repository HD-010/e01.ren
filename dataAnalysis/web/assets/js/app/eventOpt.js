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
	/*
	 * 点击+号添加对应项的内容
	 * */

	$(".addtionOpt").click(function(){
		//父贡html内容
		var parentHtml = $(this).parent().prev().html();
		
		//子项html内容
		var myHtml = setOptionContent(parentHtml);
		
		//添加项内容
		var newEventOpt ="<li>和</li><li>"  + 
		//$(this).parent().prev().html() + 
		'<select name="eventOpt[]">' + myHtml + '</select>'+
		"</li><li><span class='bt delOpt'>✕</span></li>";
		console.log(newEventOpt);
		$(newEventOpt).insertBefore(this);
		
		//添加项内容时绑定删除项事件
		$(".delOpt").on('click',function(){
			$(this).parent().prev().remove();
			$(this).parent().prev().remove();
			$(this).parent().remove();
		});
		
	});

	//设置option项内容
	function setOptionContent(str){
		//获取父项的值
		var val = $(str).val();
		console.log(val);
		var parentObj = $(str);
		//删除项中被选中的值
		var parentOptionObj = $(str).children("option");
		for(var i = 0; i < parentOptionObj.length; i ++){
			if(parentOptionObj.eq(i).text() == val){
				parentObj.children().eq(i).remove();
			}
			break;
		}
		//console.log(parentObj.html());
		return parentObj.html();
	}
	
});