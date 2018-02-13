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
		var parentObj = $(this).parent().prev().find("select");
		var parentsObj = $(this).parent().prevAll().find("select");
		
		$(this).parent().prevAll().find("select").each(function(index){
			console.log($(this).val());
		});
		var parentsVal = [];
		
		//子项html内容
		//var myHtml = setOptionContent(parentObj.html(),);
		
		//添加项内容
		var newEventOpt ="<li>和</li><li>"  + 
		$(this).parent().prev().html() + 
		//'<select name="eventOpt[]">' + myHtml + '</select>'+
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
function setOptionContent(){
	
}
	
});