/**
 * 简易表单插件
 * 作者：弘德誉曦
 * 
 * 关于数据验证的使用说明：
    //加入需要进行验证的项，这是一个二维数组，描述项与项之间的关系。有以下两种情况：
    //一、格式如 option :[["input[name=name1]","input[name=name2]","input[name=name3]"],"关系运算符"]
    //二、如果不需要将多项进行对比，则只需要写明项名称，如 option : [["input[name=uname]"]]
    //以上定义的两种option意思如下：
    //第一种，意思是option中的三个name值要能通过rule规则验证，并且要满足“关系运算符”表示的关系
    //第二种，意思是option中的name值要能通过rule规则验证
   $e("form[name='singUp']").valid({
		option : [["input[name=uname]"]],
		rule : "isTrueName", 
	});
 */
define("easyForm",['jquery'],function($){
	
	var easyForm = {
		//当前版本
		version : "easyForm 0.1.01",
		//调用过的表单
		formTag : "",
		//验证失败显示的信息
		message : "",
		//表单数据，是表单对象的所有输入对象，包括隐藏域对象
		obj : {},
		//表单必填项，在这里面的项必须通过合法验证,才能够完成easyForm.submit()动作
		requiredOption : [],
		
		//---------------valid()操作的属性------------------
		validOption : "",				//需要验证的项
		validRes : '',					//对数据的验证结果，是一个临时数据
		allValidOption : [],			//所有已经验证过的项的选择符
		//---------------valid()操作的属性end------------------
		
		//序列化后的串
		sializeObj : "",
		
		/**
		 * 初始化必填项
		 * arr array 各项的选择符名称的集合
		 */
		required : function(arr){
			this.requiredOption = arr;
			return this;
		},
		
		/**
		 * 序列化表单元素
		 * 将表单元素序列化为name=value的字符串对象，赋值给easyForm.sializeObj
		 */
		sialize : function(){
			//每次调用对上一次调用生成的数据进行清空，以确保生成的数据是最新数据
			this.sializeObj = "";
			//这是整个表单对象，及form标签中的html代码
			var htmlObj = this.obj.get(0);
			//获取表单中所有input对象
			var inputObj = $(htmlObj).find("input");
			this.each(inputObj);
			//获取表单中所有select对象
			var selectObj = $(htmlObj).find("select");
			this.each(selectObj);
			//获取表单中所有textarea对象
			var textareaObj = $(htmlObj).find("textarea");
			this.each(textareaObj);
			
			return this.sializeObj.substring(1);
		},
		/**
		 * 各项值验证的入口方法，如果在方法执行过程中，一旦验证不合法，会取消到剩余项的验证并向用户提示非法输入的信息。
		 */
		valid : function(obj){
			//在验证开始之前先清空页面上数据格式非法的提示信息
			$("div[name=validNotice]").remove();
			//定义默验证项为空，验证规则为空
			obj = obj || {option : [[""]],rule : ""};
			
			this.validOption = obj.option[0];	      //需要验证的项
			this.message = obj.message;
			var optionNum = this.validOption.length;  //计算需要进行验证的option项的个数
			var relu = obj.rule;				//获取验证规则
			
			//如果optionNum == 0,则提示错误
			if(optionNum === 0)return;
			
			
			//如果optionNum == 1,则不进行关系验证
			if(optionNum == 1){
				var selecter = this.validOption[0]
				var value = $(selecter).val();
				var index = this.findIndexByName(selecter,this.allValidOption);
				console.log(index);
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
					
					return;
				}
				//添加项与验证结果的对应关系到this.allValidOption,如果allValidOption中已经存在该项，则不进行添加，只是修改value值
				if(index == -1){
					eval("(this.allValidOption.push({name:'"+selecter+"',value:true}))");
				}else{
					eval("(this.allValidOption["+index+"]['value'] = true)");
				}
			}
			//如查optionNum > 1,则需要进行关系验证
			//..................
			
			
		},
		
		/**
		 * 提交，数据提交需要做以下验证：
		 * 检查必填项required是否被设置 ，如果已经设置，需要在allValidOption中查看各个元素是否已经通过有效性验证。
		 * 如果没有值则提示需要输入。如果没有验证过，则说明该值为空。
		 * 如果已经通过验证，则检查其验证结果是否为true,如果为假则说明验证失败。
		 * params object 表单数据提交相关的参数
		 */
		submit : function(){
			this.message = "必填项不能为空！";
			for(var i in this.requiredOption){
				var isValid = false;
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
					console.log(this.requiredOption[i]);
					console.log(this.requiredOption);
					console.log(this.allValidOption);
					this.notice($(this.requiredOption[i]));
					return;
				}
			}
		},
		/**
		 * 在当前操作的表单元素对象下显示数据格式非法的提示信息
		 */
		notice : function(elementObj){
			var notice = "<div name=validNotice style='position: absolute;border: 0;border-radius: 4px;background-color:lavenderblush;padding: 4px;color: peru;text-align: center;'> " + this.message + " </div>";
			elementObj.parent().append(notice);
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
				this.sializeObj += str;
			}
		},
		
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