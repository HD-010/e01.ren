/**
 * 简易表单插件
 * 作者：弘德誉曦
 * 
 * 关于数据验证的使用说明：
 * 如何校验表单数据类型
    //加入需要验证的项，这是一个二维数组，描述项与项之间的关系。有以下两种情况：
    //一、格式如 option :[["input[name=name1]","input[name=name2]","input[name=name3]"],"关系运算符"]
    //二、如果不需要将多项进行对比，则只需要写明项名称，如 option : [["input[name=uname]"]]
    //以上定义的两种option意思如下：
    //第一种，意思是option中的三个name值要能通过rule规则验证，并且要满足“关系运算符”表示的关系
    //第二种，意思是option中的name值要能通过rule规则验证
   $e("form[name='singUp']").valid({
		option : [["input[name=uname]"]],
		rule : "isTrueName", 
	});
	
	如何获取表单数据序列化字串？
	$e("form[name='singUp']").sialize()
	
	如何获取表单数据序列化对象？
	$e("form[name='singUp']").formData()
	
	如何校验必填项？
	$e("form[name='singUp']").required([
	    "input[name=uname]",                                
	    "input[name=pswd]",                                
	    "input[name=pswd2]",                               
	    "input[name=Verification]",                               
	])
	
	如何提交表单？
	$e("form[name='singUp']").required([
	    "input[name=uname]",                                
	    "input[name=pswd]",                                
	    "input[name=pswd2]",                               
	    "input[name=Verification]",                               
	]).submit({					//该对象为jquery  ajax参数对象
		url:this.url+"/sing-up",
		dataType:"JSON",
		success:function(data){
			console.log(data)
			if(data.state == 'success'){
				//显示注册 成功提示信息
				var message = $e().msg("注册成功，正在登录...");
				$("div[name=singUpTitle]").append(message);
				login.data = data;
				//三秒后自动完成登录
				setTimeout(login.singUpToIn,3000);
			}
		},
		error:'login.error'
	});
	
 */
define("easyForm",function(require){
	var $=require('jquery');
	//当前被验证的模块
	var currentModel;
	var easyForm = {
		//当前版本
		version : "easyForm 0.1.01",
		//调用过的表单
		formTag : "",
		//表单对象
		obj : "",
		//验证失败显示的信息
		message : "",
		//禁止提交（初始false允许提交）
		submitForbid : false,
		//表单必填项，在这里面的项必须通过合法验证,才能够完成easyForm.submit()动作
		requiredOption : [],
		//用户输入的某个验证规则的对象
		validRule : {},
		
		//---------------valid()操作的属性------------------
		validOption : "",				//需要验证的项
		validRes : '',					//对数据的验证结果，是一个临时数据
		allValidOption : [],			//所有已经验证过的项的选择符
		//---------------valid()操作的属性end------------------
		
		//序列化后的串
		sializeStr : "",
		//表单数据对象
		formDataObj : {},
		/**
		 * 初始化必填项
		 * arr array 各项的选择符名称的集合
		 */
		required : function(arr){
			this.requiredOption = arr;
			return this;
		},
		//提取表单数据对象
		formData :function(){
			this.sialize("object");
			return this.formDataObj;
		},
		/**
		 * 序列化表单元素
		 * 将表单元素序列化为name=value的字符串对象，赋值给easyForm.sializeObj
		 * tag 表示格式化后的数据类型，默认string表示序列化为字串，object表示序列化为对象
		 */
		sialize : function(type){
			type = type || 'string';
			//每次调用对上一次调用生成的数据进行清空，以确保生成的数据是最新数据
			this.sializeStr = "";
			//这是整个表单对象，及form标签中的html代码
			var htmlObj = this.obj.get(0);
			//获取表单中所有input对象
			var inputObj = $(htmlObj).find("input");
			this.each(inputObj,type);
			//获取表单中所有select对象
			var selectObj = $(htmlObj).find("select");
			this.each(selectObj,type);
			//获取表单中所有textarea对象
			var textareaObj = $(htmlObj).find("textarea");
			this.each(textareaObj,type);
			
			return this.sializeStr.substring(1);
		},
		/**
		 * 各项值验证的入口方法，如果在方法执行过程中，一旦验证不合法，会取消剩余项的验证并向用户提示非法输入的信息。
		 */
		valid : function(obj){
			//在验证开始之前先清空页面上数据格式非法的提示信息
			$("div[name=validNotice]").remove();
			
			//***************初始化验证参数*****************//
			//定义默认验证项为空，验证规则为空
			this.validRule = obj || {option : [[""],""],rule : ""};
			this.validOption = this.validRule.option[0];	      //需要验证的项
			this.message = this.validRule.message;
			
			var optionNum = this.validOption.length;  //计算需要进行验证的option项的个数
			
			//如果optionNum == 0,则提示错误
			if(optionNum === 0)return;
			
			//如果optionNum == 1,则不进行关系验证
			if(optionNum == 1){
				this.validing(0);
			}
			
			//如查optionNum > 1,则需要进行关系验证
			if(optionNum > 1){
				//记录多步验证中每一步验证的状态，如果所有步骤都验证完成，并且没有false失败状态,则进入关系运算符比较
				var validingRes = true;
				for(var i = 0; i < optionNum; i ++){
					if(!this.validing(i)){
						validingRes = false;
						break;
					}
				}
				//进入关系运算符比较,如果比较返回true,则不作任何动作。否则，将this.allValidOption中对应对象的value属性改为false,并提示用户
				if(validingRes){
					//获取关系运算符
					if(this.validRule.option[1] != undefined) {
						var relation = this.validRule.option[1];
						var compareRes;
						this.compare(relation);
					}else{
						return;
					}  
				}
				
			}
			
		},
		
		/**
		 * 循环每个值按照给定的关系进行对比
		 */
		compare : function(relation){
			var compareRes = true;
			for(var i = 0; i < this.validOption.length - 1; i ++){
				var res;
				eval("(res = ($(this.validOption[i]).val()" + relation + "$(this.validOption[i+1]).val()))");
				if(!res){
					var index = this.findIndexByName(this.validOption[i],this.allValidOption);
					this.allValidOption[index].value = false;
					this.message = this.validRule.option[2];
					this.notice($(this.validOption[i+1]));
				}
			}
			return compareRes;
		},
		/**
		 * 对单项数据进行rule验证。如果不合法则退出验证并提示用户
		 * 验证失败返回false,验证通过则返回true
		 */
	    validing : function(index){
	    	var relu = this.validRule.rule;				//获取验证规则
	    	var selecter = this.validOption[index];
			//在当前表单对象中查找项验证项，并获取它的值
			var value = this.obj.find(selecter).val();
			var index = this.findIndexByName(selecter,this.allValidOption);
			//开启数据提交权限
			this.submitForbid = false;
			//console.log(index);
			//将rule验证结果赋值给easyFrom.validRes
			eval("(this.validRes = rule."+relu+"('"+value+"'))");
			//如果验证失败，显示提示信息并
			if(!this.validRes){
				this.notice($(selecter));
				//添加项与验证结果的对应关系到this.allValidOption,如果allValidOption中已经存在该项，则不进行添加，只是修改value值
				if(index == -1){
					eval("(this.allValidOption.push({name:'"+selecter+"',value:false}))");
				}else{
					eval("(this.allValidOption["+index+"]['value'] = false)");
				}
				
				return false;
			}
			//添加项与验证结果的对应关系到this.allValidOption,如果allValidOption中已经存在该项，则不进行添加，只是修改value值
			if(index == -1){
				eval("(this.allValidOption.push({name:'"+selecter+"',value:true}))");
			}else{
				eval("(this.allValidOption["+index+"]['value'] = true)");
			}
			return true;
	    },
		
		/**
		 * 提交，数据提交需要做以下验证：
		 * 检查必填项required是否被设置 ，如果已经设置，需要在allValidOption中查看各个元素是否已经通过有效性验证。
		 * 如果没有值则提示需要输入。如果没有验证过，则说明该值为空。
		 * 如果已经通过验证，则检查其验证结果是否为true,如果为假则说明验证失败。
		 * params object 表单数据提交相关的参数
		 */
	    submit : function(obj){
	    	//如果禁止提交，则取消执行
	    	if(this.submitForbid) return;
	    	
	    	//提交表的必要条件验证，提交地址不能为空
    		if(obj.url === undefined){
    			console.log("提交地址不能为空");
    			return;
    		}
    		
	    	//提交前的数据验证，如果验证失败，则取消执行
	    	if(!this.submitValid()){
	    		//验证失败
	    		console.log("验证失败");
	    		return false;
	    	}
	    	
	    	//预定义ajax的参数对象
	    	var preData = {
	    		data:this.formData(),
	    	};
	    	
	    	//列出提交表的数据对象中可能出现的属性为函数的属性名称
	    	var callback = ["success","error"];
	    	//整理ajax参数对象,将用户定义的ajax的参数对象类分添加到preData中
	    	for(var o in obj){
	    		if(this.inArray(o,callback) == -1){
	    			//这些属性不会以函数的形式出现
	    			//添加对象属性
	    			eval("(preData."+o+" = obj."+o+")");
	    		}else{
	    			//这些属性可能会以函数的形式出现，若出现则将其添加到preData的成员方法
	    			var dataType;		//定义成员方法出现的类型，可能是string,可能是function
	    			eval("(dataType = typeof obj."+o+")");
	    			if(dataType == "function"){
	    				eval("(preData."+o+" = obj."+o+")");
	    			}else{
	    				if(typeof currentModel == "undefined"){
	    					var model;
	    	    			eval("(model = obj."+o+".substr(0,obj."+o+".indexOf('.')))");
	    	    			eval("(currentModel = require('"+model+"'))");
	    	    		}
	    				eval("(preData."+o+" = currentModel."+o+")");
	    			}
	    		}
	    	}
	    	//console.log(preData);
	    	$.ajax(preData);
	    	
	    	//提交完成后禁止重复提交
	    	this.submitForbid = true;
	    	
	    },
	    /**
	     * 提交前的数据验证
	     */
		submitValid : function(){
			this.message = "必填项不能为空！";
			for(var i in this.requiredOption){
				var isValid = false;
				console.log('所有验证项：')
				console.log(this.allValidOption);
				for(var j = 0;j < this.allValidOption.length; j++){
					//当前项已经验证过
					if(this.allValidOption[j]['name'] == this.requiredOption[i]){
						if(!this.allValidOption[j]['value']){ //当前项已经验证过，但验证失败
							this.message = "数据格式错误";
							this.notice($(this.requiredOption[i]));
							return;
						}else{								//当前项已经验证过，且验证通过
							isValid = true;
							break;
						}
					}
				}
				//当前项没有经过验证，说明当前项还没有填写
				if(!isValid){
					console.log(this.requiredOption[i] + this.message)
					this.notice($(this.requiredOption[i]));
					return false;
				}
			}
			//所有项都验证完毕，且没有发现非法数据，则返回true表示所有数据合法
			return true;
		},
		
		/**
		 * 
		 */
		/**
		 * 在当前操作的表单元素对象下显示数据格式非法的提示信息
		 */
		notice : function(elementObj){
			if(!this.message) return;
			var notice = "<div name=validNotice style='position: absolute;border: 0;border-radius: 4px;background-color:lavenderblush;padding: 4px;color: peru;text-align: center;'> " + this.message + " </div>";
			elementObj.parent().append(notice);
			this.submitForbid = true;
		},
		
		/**
		 * 在当前操作的表单元素对象下显示数据格式非法的提示信息
		 */
		msg : function(msg){
			if(!msg) return;
			return "<div name=validNotice style='position: absolute;border: 0;border-radius: 4px;background-color:lavenderblush;padding: 4px;color: peru;text-align: center;margin-top: 20%;'> " + msg + " </div>";
		},
		/**
		 * 判断值是否存在于数组中，如果存在则返回该值对应的第一个键名
		 */
		inArray : function(val,array){
			for(var o in array){
				if(array[o] === val){
					return o;
				}
			}
			return -1;
		},
		/**
		 * 根据对象的name值获取对应的index值
		 * obj object 形式如：
		 * [
		 * 	{name:"input[name=uname]",value:true},
		 * 	{name:"input[name=pswd1]",value:false},
		 * ]
		 */
		findIndexByName:function(name,obj){
			for(var j = 0;j < obj.length; j++){
				//当前项已经验证过
				if(obj[j]['name'] == name){
					return j;
				}
			}
			return -1;
		},
		
		/**
		 * 序列化表单数据
		 * obj 为表单对象
		 * type 为序列化数据为指定的类型
		 */
		each : function(obj,type){
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
				
				//将表单数据序列化为字符串
				if(type == 'string'){
					var str = "&"+name+"="+value.trim();
					this.sializeStr += str;
				}
				//将表单数据序列化对象
				if(type == 'object'){
					eval("("+"this.formDataObj."+name+" = '"+value+"')");
				}
			}
		},
		
		/**
		 * 自定义验证规则
		 */
		setRule : function(){
			var ruleNme,ruler;
			rule.ruleName = ruler;
		}
		
	};
	
	
	var rule = {
		/*-----------验证规则类方法------------*/
		isNull : function (s){
			 return (s === '') ? true : false ;
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
			 var patrn=/^(13[0-9]|15[0-9]|18[0-9])\d{8}$/; 
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
		 
		 //校验复合登录名：只能输入 手机号 或email
		 isMobilOrEmail : function (s){
			 var res = this.isMobil(s) || this.isEmail(s);
			 return res;
		 },
		 
		 //校验是否有特殊字符：只能输入非特殊字符
		 isSpecialChartor : function (s){
			 var patrn=/((?=[\x21-\x7e]+)[^A-Za-z0-9])/; 
			 if (patrn.exec(s)) return false;
			 return true;
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