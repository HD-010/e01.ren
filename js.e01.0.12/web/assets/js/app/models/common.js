/**
 * 	公共对象
 */
define([ "jquery" ], function($) {
	var common = {
		//元素名称与其属性的依赖关系	//common.append.push([["autoFormate","loginState","friendsState"],"showOnChang","disabled|enlabled"]);
		//append : [],
		/** 
		 * 无刷新设置url参数
		 *@param arr= New Array('key'=>'value');
		 */
		setUrlNoFresh : function(arr) {
			var str, tag = 1;
			for ( var key in arr) {
				if (tag == 1) {
					str = key + "=" + arr[key];
				} else {
					str = str + "&" + key + "=" + arr[key];
				}

				tag++;
			}
			document.location.hash = str;
		},

		/** 无刷新修改url参数
		 *destiny是目标字符串，比如是http://www.huistd.com/?id=3&ttt=3 
		 *par是参数名，par_value是参数要更改的值，调用结果如下： 
		 *changeURLPar(test, 'id', 99); // http://www.huistd.com/?id=99&ttt=3 
		 *changeURLPar(test, 'haha', 33); // http://www.huistd.com/?id=99&ttt=3&haha=33 
		 *_________________________________________________
		 */
		changeURLPar : function(destiny, par, par_value) {
			var pattern = par + '=([^&]*)';
			var replaceText = par + '=' + par_value;
			if (destiny.match(pattern)) {
				var tmp = '/' + par + '=([^&]*)/';

				tmp = destiny.replace(eval(tmp), replaceText);
				return (tmp);
			} else {
				if (destiny.match('[\?]')) {
					return destiny + '&' + replaceText;
				} else {
					return destiny + '?' + replaceText;
				}
			}
			return destiny + '\n' + par + '\n' + par_value;
		},

		/**
		 * 获取url参数值
		 * @param key string 参数名称
		 */
		getQueryString : function(key) {
			var reg = new RegExp(key + '=([^&]*)');
			var results = location.href.match(reg);
			return results ? results[1] : null;
		},

		/**
		 * 刷新验证码
		 */
		changeAuthCode : function() {
			var num = new Date().getTime();
			var rand = Math.round(Math.random() * 10000);
			num = num + rand;
			$('#ver_code').css('visibility', 'visible');
			if ($("#vdimgck")[0]) {
				$("#vdimgck")[0].src = "../../include/vdimgck.php?tag=" + num;
			}
			return false;
		},

		/**
		 * 将图片保存到手机相册
		 */
		saveAs : function(Url) {
			var blob = new Blob([ '' ], {
				type : 'application/octet-stream'
			});
			var url = URL.createObjectURL(blob);
			var a = document.createElement('a');
			a.href = Url;
			a.download = Url.replace(/(.*\/)*([^.]+.*)/ig, "$2").split("?")[0];
			var e = document.createEvent('MouseEvents');
			e.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0,
					false, false, false, false, 0, null);
			a.dispatchEvent(e);
			URL.revokeObjectURL(url);
		},

		/************************数组常用方法****************************/
		/**
		 * 判断值是否是数组中的元素，是则反键名。否则返回-1
		 */
		inArray : function(value, array) {
			for ( var k in array) {
				if (array[k] === value) {
					return k;
				}
			}
			return -1;
		},
		
		/**
		 * 判断key是否是存在，是则反回1。否则返回-1
		 */
		arrayKeyExists : function(key, array) {
			for ( var k in array) {
				if (k === key) {
					return true;
				}
			}
			return false;
		},
		

		/************************end****************************/
		//将字符串的首字母大写
		ucFirst : function(str) {
			return str.substring(0, 1).toUpperCase() + str.substring(1);
		},

		//设置cookie
		setCookie : function(name, value, days) {
			var exp = new Date();
			exp.setTime(exp.getTime() + days * 24 * 60 * 60 * 1000);
			document.cookie = name + "=" + escape(value) + ";expires="
					+ exp.toGMTString();
			//设置本地存储
			eval("(" + "localStorage." + name + "=" + value + ")");
		},

		//获取cookie常用方法
		getCookies : function(name) {
			if (name && name.length > 0) {
				var val = eval('(localStorage.' + name + ')');
				if (val) {
					return val;
				}
			}
			var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
			if (arr = document.cookie.match(reg)) {
				return unescape(arr[2]);
			}
			return null;
		},

		/**
		 * 遍历对象
		 */
		allPrpos : function(obj) {
			// 用来保存所有的属性名称和值 
			var props = "";
			// 开始遍历 
			for ( var p in obj) { // 方法 
				if (typeof (obj[p]) == " function ") {
					obj[p]();
				} else { // p 为属性名称，obj[p]为对应属性的值 
					props += p + " = " + obj[p] + " <br/> ";
				}
			} // 最后显示所有的属性 
			document.write("<pre>" + props + "</pre>");
		},

	};

	return common;
});