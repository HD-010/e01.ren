 
/******************************************************************
使用示例：
引入<script src="./static/js/easyform.js"></script>
设置非必填字段
setNotMust(['pws']);
设置登录地址
setLoginUrl('url');
设置登录回调函数
setLoginCall(funcName);

调用 
onblur="$e(this,['value','isNull',false,'请正确填写帐户名'],callback)"
注：callback（验证正确调用该函数）
提示表单：onclick="return $submit('必填字段不能为空！')"
******************************************************************/

 /*******************************************/
 /*****************易用表单设置思路**************/
 /*******************************************/
 /**
  * 1、指明非必填项notMust。当用户提交表单时，系统不对其初始值进行验证
  * 2、err 是指定项验证失败信息，记录各项验证结果（正确0，有误为1）
  * 3、all 是表单中所有输入项，由系统自主写入
  * 4、如果必填项在err中的值为1，则表法当前表单填写不正确(不能提交)
  */
/**
 * 这是一个登录页面的完整示例：
 	var formData = new Object();
 	formData.mustOption = '';
 	formData.action = 'http://localhost/mylib/chatroom/api.php?ct=ucenter&ac=login';
 	
	//设置非必填字段
	//setNotMust(formData.mustOption);
	//设置登录地址
	setLoginUrl(formData.action);
	//设置登录回调函数
	setLoginCall(callBack);
	
	
	function callBack(res){
		if(res.state == 0) err('登录失败：用户名或密码错误 ！');
		if(res.state == 1) {
			setUserInfro(res.data)
			if(sessionStorage.lasturl){
				location.href = sessionStorage.lasturl
			}else{
				location.href = './uins.html';
			}
		}
	}
	
	//设置用户信息
	function setUserInfro(userInfo){
		console.log(userInfo)
		sessionStorage.fromUin = userInfo.id;
	}
 */

define("$e",["jquery"],function($){
	var eform = new Object();
	eform.all;					//表单中所有输入项，由系统自主写入
	eform.notMust;       		//非必须项
	eform.err;					//指定项验证失败信息
	eform.loginUrl;				//远程登录接口
	eform.loginCall;			//登录回调函数
	
	
	//初始化...
	eform.serial = $('form').serialize();
	//初始化表单中所有输入项名称
	eform.all = setAll(0);
	//初始化表单中所有输入项初始值1(表示有错)
	eform.err = setErr(1);
	console.log(eform.err);
	
	/*******************************************/
	/****************efrom验证方法****************/
	/*******************************************/
	//checkOptionArr  验证选项,格式:[attr,rule,boolen,notice]
	//attr 验证指定属性的名称
	//rule 用于验证属性的规则名称
	//boolen 要求验证符合的结果true|false	  (true要求验证结果为真，false要求验证结果为假)
	//notice 验证错误提示内容
	function $e(obj,checkOptionArr,callBackFunction){
		var o =new Object();
		o.attrN = checkOptionArr[0];				//属性名称
		o.attrV = eval('obj.' + o.attrN);			//属性名对应的值
		o.rule = checkOptionArr[1];				//验证规则（方法）
		o.standard = checkOptionArr[2];			//要求验证符合的结果true|failse
		o.notice = checkOptionArr[3];			//要求验证符合的结果true|failse
		o.res = eval("rule." + o.rule + "('" + o.attrV + "')");
		
		if( o.res === o.standard){				//通过验证
			console.log(callBackFunction);
			if(callBackFunction){
				callBackFunction()
			}else{
				seccuse()
			}
			setErrOtion(obj)					//记录验证正确的项
			//console.log(eform.err)
		}else{									//没有通过验证
			err(o.notice)
		} 
	}
	
	//提交验证 , notice 禁止提交提示
	function $submit(notice){
		if(!notice) return;
		var tmp = inArray(1,eform.err);
		if(tmp !== false){
			if(inArray(tmp,eform.notMust) === false){
				err(notice);				//输出禁止提交提示
				return false;
			}
		}
		login(eform.loginUrl,eform.loginCall);		//登录远程服务器
		//禁止页面因提交跳转
		return false;
	}
	
	//返回 val 在 arr 中首次匹配的 key,若没有匹配值则返回 FALSE
	function inArray(val,arr){
		for(var i in eform.err){
			if(eform.err[i] === val){
				return i;
			}
		}
		return false;
	}
	
	/*******************************************/
	/******************系统函数*******************/
	/*******************************************/
	//记录验证正确的项
	function setErrOtion(o){
		eform.err[o.name] = 0;
	}
	
	//返回表单中所有输入项初始值n(需要设定的值)
	function setErr(n){
		var temp = new Array();
		for(var i in eform.all){
			temp[eform.all[i]]=n;
		}
		return temp;
	}
	
	//设置非必须项
	function setNotMust(options){eform.notMust = options;}
	
	//设置登录地址
	function setLoginUrl(url){eform.loginUrl = url;}
	
	//登录回调函数
	function setLoginCall(funcName){eform.loginCall = funcName;}
	
	//返回表单项（0名称｜1值）的一维数组
	function setAll(n){
		var tmp = eform.serial.split('&');
		var tmp_ = new Array();
		for(var i in tmp){
			var tmp__ = tmp[i].split('=');
			tmp_.push(tmp__[0]);
		}
		return tmp_;
	}
	
	//远程登录验证
	//api 登录地址  callbackfunction回调函数
	function login(api,callback){
		var data = $('form').serialize();
		ajax('post',api,'json',callback,data,'multipart/form-data')
	}
	
	
	/*******************************************/
	/******************通用方法*******************/
	/*******************************************/
	var common = new Object();
	//删除同一类名 的所有对象
	common.removeByClassName = function (classn){
		eval("$('." + classn + "').remove()");
	}
	//根据一个对象的类名找出同类名的所有对象，并将其删除 
	common.removeByObj = function (o){
		var classn = $(o).attr('class');
		console.log(classn)
		this.removeByClassName(classn);
	}
	
	/*******************************************/
	/*******************回调函数******************/
	/*******************************************/
	//当验证成功
	function seccuse(){
		console.log('验证通过')
	}
	//验证没通过显示提示信息
	function err(info){
		if(info != undefined){
			var htm = '<div class="easyFormNotice" '+
			'onclick="common.removeByObj(this)" '+
			'style="width:100%;height:100%;border:0;'+
			'background-color:black;position:fixed;'+
			'top:0;text-align:center;filter:alpha(opacity:30); '+
			'opacity:0.3; -moz-opacity:0.3;-khtml-opacity: 0.3">'+
			'</div><div class="easyFormNotice" '+
			'onclick="common.removeByObj(this)" '+
			'style=" width:40%;position: fixed; top: 35%;color: #fff;  '+
			'border: 1px solid black; background-color: black;  '+
			'text-align:center;  '+
			'padding: 16px; left: 25%;filter:alpha(opacity:100); '+
			'opacity:1; -moz-opacity:1;-khtml-opacity: 1"> '+ info +' </div>';
			$('body').append(htm);
		}
		setTimeout("common.removeByClassName('easyFormNotice')",3000);
	}
	
	/*******************************************/
	/******************验证规则*******************/
	/*******************************************/
	var rule = new Object();
	//空值
	rule.isNull = function (s){
		return (s === '') ? true : false ;
	}
	
	//校验是否全由数字组成 
	rule.isDigit = function (s) 
	{ 
		var patrn=/^[0-9]{1,20}$/; 
		if (!patrn.exec(s)) return false 
		return true 
	}
	
	//校验登录名：只能输入5-20个以字母开头、可带数字、“_”、“.”的字串 
	rule.isRegisterUserName = function (s) 
	{ 
		var patrn=/^[a-zA-Z]{1}([a-zA-Z0-9]|[._]){4,19}$/; 
		if (!patrn.exec(s)) return false 
		return true 
	}
	
	//校验用户姓名：只能输入1-30个以字母开头的字串 
	rule.isTrueName = function (s) 
	{ 
		var patrn=/^[a-zA-Z]{1,30}$/; 
		if (!patrn.exec(s)) return false 
		return true 
	}
	
	//校验密码：只能输入6-20个字母、数字、下划线 
	rule.isPasswd = function (s) 
	{ 
		var patrn=/^(\w){6,20}$/; 
		if (!patrn.exec(s)) return false 
		return true 
	}
	
	//校验普通电话、传真号码：可以“+”开头，除数字外，可含有“-” 
	rule.isTel = function (s) 
	{ 
		//var patrn=/^[+]{0,1}(\d){1,3}[ ]?([-]?(\d){1,12})+$/; 
		var patrn=/^[+]{0,1}(\d){1,3}[ ]?([-]?((\d)|[ ]){1,12})+$/; 
		if (!patrn.exec(s)) return false 
		return true 
	}
	
	//校验手机号码：必须以数字开头，除数字外，可含有“-” 
	rule.isMobil = function (s) 
	{ 
		var patrn=/^[+]{0,1}(\d){1,3}[ ]?([-]?((\d)|[ ]){1,12})+$/; 
		if (!patrn.exec(s)) return false 
		return true 
	}
	
	//校验邮政编码 
	rule.isPostalCode = function (s) 
	{ 
		//var patrn=/^[a-zA-Z0-9]{3,12}$/; 
		var patrn=/^[a-zA-Z0-9 ]{3,12}$/; 
		if (!patrn.exec(s)) return false 
		return true 
	}
	
	//校验电子邮箱 
	rule.isEmail = function (s) 
	{ 
		var patrn=/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/; 
		if (!patrn.exec(s)) return false 
		return true 
	}
	//校验url
	rule.isUrl = function (s) 
	{ 
		var patrn=/^[hH][tT][tT][pP]([sS]?):\/\/(\S+\.)+\S{2,}$/; 
		if (!patrn.exec(s)) return false 
		return true 
	}
	
	//校验搜索关键字 
	rule.isSearch = function (s) 
	{ 
		var patrn=/^[^`~!@#$%^&*()+=|\\\][\]\{\}:;'\,.<>/?]{1}[^`~!@$%^&()+=|\\\][\]\{\}:;'\,.<>?]{0,19}$/;
		if (!patrn.exec(s)) return false 
		return true 
	}
	
	//校验是ip地址
	rule.isIP = function (s) //by zergling 
	{ 
		var patrn=/^[0-9.]{1,20}$/; 
		if (!patrn.exec(s)) return false 
		return true 
	} 
	
	//校验复合登录名：只能输入5-20个以字母开头、可带数字、“_”、“.”的字串  或 手机号 或email
	rule.isComplexUserName = function (s){
		var res = this.isMobil(s) || this.isEmail(s);
		return res;
	}
	
	return $e : eform;
});