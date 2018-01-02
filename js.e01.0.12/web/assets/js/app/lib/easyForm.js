/**
 * 简易表单插件
 * 作者：弘德誉曦
 */
define("easyForm",['jquery'],function($){
	
	var easyForm = {
		//当前版本
		version : "easyForm 0.1.01",
		//调用过的表单
		formTag : "",
		//表单数据，是表单对象的所有输入对象，包括隐藏域对象
		obj : {},
		//验证数据对象
		data : {
			//验证结果是否有为真，为真为1，为假为0.数据结构为 被验证元素名称："值"。这里的"被验证元素名称"可以是jquery中的选择符
			validate:[],
		},
		//当前操作的选择符
		selector : "",
		//当前被操作的数据
		currentData : "",
		//序列化后的串
		sializeObj : "",
		//序列化表单元素
		sialize : function(){
			easyForm.sializeObj = "";
			var htmlObj = easyForm.obj.get(0);
			var inputObj = $(htmlObj).find("input");
			easyForm.each(inputObj);
			var selectObj = $(htmlObj).find("select");
			easyForm.each(selectObj);
			var textareaObj = $(htmlObj).find("textarea");
			easyForm.each(textareaObj);
			return easyForm.sializeObj.substring(1);
		},
		
		submit : function(){
			
		},
		
		/**
		 * 数据有效性验证
		 * ruleName string 是rule对象中的某个方法名称
		 */
		rule : function(ruleName){
			//这里将this.currentData数据按照ruleName规则判断后存到this.data.validate数组中，对应的键为this.selector
			eval("(rule." + ruleName + "(easyForm.currentData))");
			return easyForm;
		},
		each : function(obj){
			for(var i = 0; i < obj.length; i++){
				if(
					obj.eq(i).attr("type") == "button" ||
					obj.eq(i).attr("type") == "submit" ||
					obj.eq(i).attr("type") == "reset"
				){
					continue;
				}
				name = obj.eq(i).attr("name");
				value = obj.eq(i).val();
				var str = "&"+name+"="+value.trim();
				easyForm.sializeObj += str;
			}
		}
	};
	
	
	var rule = {
		/*-----------验证规则类方法------------*/
		isNull : function (s){
			 easyForm.data[easyForm.selector] = (s === '') ? true : false ;
		 },
		 
		//校验是否全由数字组成 
		 isDigit : function (s) 
		 { 
			 var patrn=/^[0-9]{1,20}$/; 
			 if (!patrn.exec(s)) return false 
			 return true 
		 },
		 
		 //校验登录名：只能输入5-20个以字母开头、可带数字、“_”、“.”的字串 
		 isRegisterUserName : function (s) 
		 { 
			 var patrn=/^[a-zA-Z]{1}([a-zA-Z0-9]|[._]){4,19}$/; 
			 if (!patrn.exec(s)) return false 
			 return true 
		 },
		 
		 //校验用户姓名：只能输入1-30个以字母开头的字串 
		 isTrueName : function (s) 
		 { 
			 var patrn=/^[a-zA-Z]{1,30}$/; 
			 if (!patrn.exec(s)) return false 
			 return true 
		 },
		 
		 //校验密码：只能输入6-20个字母、数字、下划线 
		 isPasswd : function (s) 
		 { 
			 var patrn=/^(\w){6,20}$/; 
			 if (!patrn.exec(s)) return false 
			 return true 
		 },
		 
		 //校验普通电话、传真号码：可以“+”开头，除数字外，可含有“-” 
		 isTel : function (s) 
		 { 
			 //var patrn=/^[+]{0,1}(\d){1,3}[ ]?([-]?(\d){1,12})+$/; 
			 var patrn=/^[+]{0,1}(\d){1,3}[ ]?([-]?((\d)|[ ]){1,12})+$/; 
			 if (!patrn.exec(s)) return false 
			 return true 
		 },
		 
		 //校验手机号码：必须以数字开头，除数字外，可含有“-” 
		 isMobil : function (s) 
		 { 
			 var patrn=/^[+]{0,1}(\d){1,3}[ ]?([-]?((\d)|[ ]){1,12})+$/; 
			 if (!patrn.exec(s)) return false 
			 return true 
		 },
		 
		 //校验邮政编码 
		 isPostalCode : function (s) 
		 { 
			 //var patrn=/^[a-zA-Z0-9]{3,12}$/; 
			 var patrn=/^[a-zA-Z0-9 ]{3,12}$/; 
			 if (!patrn.exec(s)) return false 
			 return true 
		 },
		 
		 //校验电子邮箱 
		 isEmail : function (s) 
		 { 
			 var patrn=/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/; 
			 if (!patrn.exec(s)) return false 
			 return true 
		 },
		 //校验url
		 isUrl : function (s) 
		 { 
			 var patrn=/^[hH][tT][tT][pP]([sS]?):\/\/(\S+\.)+\S{2,}$/; 
			 if (!patrn.exec(s)) return false 
			 return true 
		 },
		 
		 //校验搜索关键字 
		 isSearch : function (s) 
		 { 
			 var patrn=/^[^`~!@#$%^&*()+=|\\\][\]\{\}:;'\,.<>/?]{1}[^`~!@$%^&()+=|\\\][\]\{\}:;'\,.<>?]{0,19}$/;
			 if (!patrn.exec(s)) return false 
			 return true 
		 },
		 
		 //校验是ip地址
		 isIP : function (s) //by zergling 
		 { 
			 var patrn=/^[0-9.]{1,20}$/; 
			 if (!patrn.exec(s)) return false 
			 return true 
		 },
		 
		 //校验复合登录名：只能输入5-20个以字母开头、可带数字、“_”、“.”的字串  或 手机号 或email
		 isComplexUserName : function (s){
			 var res = this.isMobil(s) || this.isEmail(s);
			 return res;
		 },
	};
	var $e = function(formTag){
		formTag = formTag || "";
		//如果formTag 不为空，则获取表单对象
		if(formTag.length > 0){
			easyForm.formTag = formTag;
			easyForm.obj = $(formTag);
			return easyForm;
		}else if(easyForm.obj.length > 0){
			return easyForm;
		}else{
			return easyForm.version;
		}
	};
	
	return $e;
});