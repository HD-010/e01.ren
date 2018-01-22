/**
 * ebug属性模块
 */
define(["jquery"],function($){
var ebug = {
api : location.origin + '?r=common/elog',//数据发送 接口 
baseData : eval('(' + getCookie('sensorsdata2015jssdkcross') + ')' ),
sensorsUTag : function(){
if(typeof getSensorsUTag == 'function'){
return  getSensorsUTag();
}else{
return '';
}
},
/*所有事件都有的预置属性*/
$screen_height : screen.width,     //屏幕高度
$screen_width : screen.height,     //屏幕宽度
$latest_utm_source : '',    //最近一次付费广告系列来源（只要有来源参数，就会重置）
$latest_utm_medium : '',    //最近一次付费广告系列媒介
$latest_utm_term : '',      //最近一次付费广告系列字词
$latest_utm_content : '',   //最近一次付费广告系列内容
$latest_utm_campaign : '',  //最近一次付费广告系列名称
//PageView预置属性
$url : location.href,          //页面地址
$url_path : location.pathname,         //页面路径
$title : document.title,         //页面标题 
//暂未注解的预置属性
$utm_source : '',       //广告系列来源
$utm_medium : '',       //广告系列媒介
$utm_term : '',         //广告系列字词
$utm_content : '',      //广告系列内容
$utm_campaign : '',     //广告系列名称
//最近一次站外前向地址
$latest_referrer : function(){
return  this.baseData.props.$latest_referrer;
},
//前向域名
$referrer_host : function(){
return this.baseData.props.$referrer_host;
},
//最近一次流量来源类型
$latest_traffic_source_type :function(){
return this.baseData.props.$latest_traffic_source_type;    
},
//最近一次搜索引擎关键词
$latest_search_keyword : function(){
return this.baseData.props.$latest_search_keyword;
},
//最近一次站外域名
$latest_referrer_host : function(){
return this.baseData.props.$latest_referrer_host;
},
//是否首日访问
$is_first_day : function(eventName){
var now = new Date().getTime(),
keyStr=eventName+"isFirstDay";
if(typeof(localStorage) === undefined){
return true;//如果浏览器Storage不可用，则总返回是第一天使用
}else {
if(localStorage.getItem(keyStr) === undefined) {//第一次访问 返回true
localStorage.setItem(keyStr,now);//记录第一天首次访问的时间
return true;
}else{
return (now - localStorage.getItem(keyStr)) < 3600 * 24 * 100 ? true : false;
}
}
},
//是否首次访问
$is_first_time : function (eventName){
var now = new Date().getTime(),
keyStr=eventName+"isFirstTime";
if(typeof(localStorage) === undefined){
return true;//如果浏览器Storage不可用，则总返回是第一天使用
}else {
if(localStorage.getItem(keyStr) === undefined) {//第一次访问 返回true
localStorage.setItem(keyStr,now);//记录第一天首次访问的时间
return true;
}else{
return false;
}
}
},
//首次访问时间
$first_visit_time : function (){
if(getCookies("$first_visit_time")) {return;}
setCookie('$first_visit_time',(new Date()).valueOf());
return this;
},
//首次前向地址
$first_referrer : function (){
if(getCookies("$first_referrer")) {return;}
setCookie("$first_referrer",this.baseData.props.$latest_referrer);
return this;
},
//首次前向域名
$first_referrer_host : function(){
if(getCookies("$first_referrer_host")) {return;}
setCookie("$first_referrer_host",this.baseData.props.$referrer_host);
return this;
},
//首次使用的浏览器语言
$first_browser_language : function(){
if(getCookies("$first_browser_language")) {return;}
setCookie("$first_browser_language",navigator.language || '');
return this;
},
/**
* 发送神策日志
*@param array data 形式如 : [
*                                 'eventName' => 'enaem',     //事件名称
*                                 'properties' => [],         //事件属性
*                                 'eventType'=>'PageView'     //事件类型    如：PageView|UserAgent|WebClick
*                                 ]
* @param boolen ceche 是否缓存，如果缓存则等下次调用时发送数据  ,默认不缓存0
*使用场景：
*1.如果埋点只在服务端取数据或者只在客户端埋点取数据（异步传输） ，直接使用方法send($sensorsData)发送数据
*2.如果在服务端或客户端取获取不到完整的数据，可以先获取服务端数据并将其缓存send($sensorsData,1) 然后在客户端获取数据后通过异步传输，调用sensorLog($sensorsData)发送数据,反之亦然。
*/
send : function(data,cache){
cache=cache||0;
console.log(data);
$.ajax({   
url:this.api,   
type:'post',   
data:{'saPre':JSON.stringify(data),'cache':cache,'sensorsUTag':this.sensorsUTag()},   
async : false, //默认为true 异步   
success:function(res){console.log(res)} 
}); 
}
};
/************************神策客户端数据采集公共方法end****************************/ 
//获取神策对象的cookie
function getCookie (name){
var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
var intVal='{"distinct_id":"取值异常","$device_id":"取值异常","props":{"$latest_traffic_source_type":"取值异常","$latest_referrer":"取值异常","$latest_referrer_host":"取值异常","$latest_search_keyword":"取值异常"}}';
if(arr=document.cookie.match(reg)){
if(decodeURIComponent(arr[2]).indexOf('props') == -1) return intVal;
return decodeURIComponent(arr[2]);
}else{
return intVal;
}
}
//设置cookie
function setCookie(name,value){
var Days = 3000;
var exp = new Date();
exp.setTime(exp.getTime() + Days*24*60*60*1000);
document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
//获取cookie常用方法
function getCookies(name){
var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
if(arr=document.cookie.match(reg))
return unescape(arr[2]);
else
return null;
}
return ebug;
});