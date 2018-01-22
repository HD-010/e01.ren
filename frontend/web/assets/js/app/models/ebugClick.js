/**
 * 所有可以点击的元素
 */
define(["jquery","ebug"],function($,ebug){
	$(document).ready(function(){
		var tagImg = $(document).find('img');
		var tagA = $(document).find('a');
		
		tagImg.click(function(){
			var that = $(this);
			var properties = {
				$screen_height: ebug.$screen_height,     //屏幕高度
				$screen_width : ebug.$screen_width,      //屏幕宽度
				$is_first_day : ebug.$is_first_day('$webClick'),     //是否首日访问
				$latest_referrer_host : ebug.$latest_referrer_host(), //最近一次站外域名
				$latest_search_keyword : ebug.$latest_search_keyword,    //最近一次搜索引擎关键词
				$latest_traffic_source_type : ebug.$latest_traffic_source_type(), //最近一次流量来源类型    
				$referrer_host : ebug.$referrer_host,//前向域名
				$url : ebug.$url,          //页面地址
				src : that.attr("src"),
				viw_page_name_zh : ebug.viw_page_name_zh,//页面标题 $title(有现用属性名)
				$is_first_time : ebug.$is_first_time('$webClick') //是否首次访问
			};
			ebugSend(properties);
		});
		
		tagA.click(function(){
			var that = $(this);
			var properties = {
				$screen_height: ebug.$screen_height,     //屏幕高度
				$screen_width : ebug.$screen_width,     //屏幕宽度
				$is_first_day : ebug.$is_first_day('$webClick'),     //是否首日访问
				$latest_referrer_host : ebug.$latest_referrer_host(), //最近一次站外域名
				$latest_search_keyword : ebug.$latest_search_keyword,    //最近一次搜索引擎关键词
				$latest_traffic_source_type : ebug.$latest_traffic_source_type(), //最近一次流量来源类型    
				$referrer_host : ebug.$referrer_host,//前向域名
				$url : ebug.$url,          //页面地址
				target : that.attr("href"),
				name : that.text(),
				viw_page_name_zh : ebug.viw_page_name_zh,//页面标题 $title(有现用属性名)
				$is_first_time : ebug.$is_first_time('$webClick') //是否首次访问
			};
			ebugSend(properties);
		});
	});
	
	function ebugSend(property){
		var property = {
			eventName : '$webClick',
			properties : property,
			eventType : 'PageView'     
		};
		console.log(property);
		ebug.send(property);
	}
});