require.config({
	baseUrl : "",
	paths : {
		jquery : "http://cdn.e01.ren/common/lib/jquery",
		easyForm : "http://cdn.e01.ren/form/lib/easyform",
		echarts : "http://cdn.e01.ren/charts/echarts/echarts"
	},
	shim:{
		echarts :{exports:"echarts"}
	}

});

require(["jquery","easyForm","echarts"],function($,$e,echarts){
	//登录注册对象
	$(document).ready(function(){
		init();
		showVerify();
	});
	
	//初始化sing对象
	function init(){
		if(typeof localStorage.sing == "undefined"){
			//控制验证码是否显示的标识
			localStorage.singVerify = 0;
			//记录登录失败的次数
			localStorage.singErrNum = 1;
		}
	}
	
	//控制验证码输入框显示
	function showVerify(){
		//当控制验证码是否显示的标识为1则显示验证码
		if(localStorage.singVerify == 1){
			$('li[class="in up"]').css({"display":"block"});
		}else{
			return false;
		}
	}
	
	//控制密码确认和注册按钮显示
	function showUp(){
		$('li[class="in up"]').css({"display":"block"});
	}
	
	//显示注册视图
	$("font[name='showUp']").click(function(){
		$('li.in').css({"display":"none"});
		$('input.in').css({"display":"none"});
		$('li.in').find("input").attr("disabled",true);
		$('li.up').css({"display":"block"});
		$('input.up').css({"display":"inline"});
		$('li.up').find("input").attr("disabled",false);
	});
	
	//显示登录视图
	$("font[name='showIn']").click(function(){
		$('li.up').css({"display":"none"});
		$('input.up').css({"display":"none"});
		$('li.up').find("input").attr("disabled",true);
		$('li.in').css({"display":"block"});
		$('input.in').css({"display":"inline"});
		$('li.in').find("input").attr("disabled",false);
	});
	
	//校验登录表单数据类型
	$("input[name=username]").blur(function(){
		$e("form[name='sing']").valid({
			option : [["input[name=username]"]],
			rule : "isTrueName",
			message : "请填写正确的用户名"
		});
	});
	$("input[name=password]").blur(function(){
		$e("form[name='sing']").valid({
			option : [["input[name=password]"]],
			rule : "isPasswd",
			message : "请填写正确的登录密码"
		});
	});
	
	//登录提交
	$("input.in[type='submit']").click(function(){
		 var res = $e("form[name='sing']").required([
          "input[name=username]",
          "input[name=password]",
          ]).submit({					//该对象为jquery  ajax参数对象
        	  url:this.url+"/sing-in",
        	  dataType:"JSON",
        	  success:function(data){
        		  console.log(data)
        		  if(data.state == 'success'){
        			  //显示注册 成功提示信息
        			  var message = $e().msg("登录成功，正在跳转...");
        			  $("div[name=singUpTitle]").append(message);
        			  login.data = data;
        			  //三秒后自动完成登录
        			  setTimeout(login.singUpToIn,3000);
        		  }
        	  }
          });
		  if(!res){
			  localStorage.singErrNum ++;
			  //当错误次数大于3次，则要求输入验证码
			  if(localStorage.singErrNum >= 4){
				  localStorage.singVerify = 1;
				  showVerify();
			  }
			  console.log(localStorage.singErrNum);
		  }
		  return false;
	});
	
	//校验注册表单数据类型
	$("input[name=username]").blur(function(){
		$e("form[name='sing']").valid({
			option : [["input[name=username]"]],
			rule : "isTrueName", 
			message : "请填写正确的用户名"
		});
	});
	$("input[name=password]").blur(function(){
		$e("form[name='sing']").valid({
			option : [["input[name=password]","input[name=password1]"],"=="],
			rule : "isPasswd", 
			message : "两次输入的密码不一致"
		});
	});
	$("input[name=password1]").blur(function(){
		$e("form[name='sing']").valid({
			option : [["input[name=password1]","input[name=password]"],"=="],
			rule : "isPasswd", 
			message : "两次输入的密码不一致"
		});
	});
	$("input[name=verify]").blur(function(){
		$e("form[name='sing']").valid({
			option : [["input[name=verify]"]],
			rule : "isDigit", 
			message : "请填写正确的验证码"
		});
	});
	
	//注册提交
	$("input.up[type='submit']").click(function(){
		alert("ok");
		 var res = $e("form[name='sing']").required([
          "input[name=username]", 
          "input[name=password]",
          "input[name=password1]",
          "input[name=verify]",
          ]).submit({					//该对象为jquery  ajax参数对象
        	  url:"http://passport.e01.ren?r=sing-up",
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
        	  }
          });
		  return false;
	});
	

	// 指定图表的配置项和数据
	var colors = ['#FFAE57', '#FF7853', '#EA5151', '#CC3F57', '#9A2555'];
	var bgColor = '#2E2733';
	
	var itemStyle = {
	    star5: {
	        color: colors[0]
	    },
	    star4: {
	        color: colors[1]
	    },
	    star3: {
	        color: colors[2]
	    },
	    star2: {
	        color: colors[3]
	    }
	};

	
	/**
	 * 以下为数据可以化数据对象
	 */
	var data = [{
	    name: '虚构',
	    itemStyle: {
	        normal: {
	            color: colors[1]
	        }
	    },
	    children: [{
	        name: '小说',
	        children: [{
	            name: '5☆',
	            children: [{
	                name: '疼'
	            }, {
	                name: '慈悲'
	            }, {
	                name: '楼下的房客'
	            }]
	        }, {
	            name: '4☆',
	            children: [{
	                name: '虚无的十字架'
	            }, {
	                name: '无声告白'
	            }, {
	                name: '童年的终结'
	            }]
	        }, {
	            name: '3☆',
	            children: [{
	                name: '疯癫老人日记'
	            }]
	        }]
	    }, {
	        name: '其他',
	        children: [{
	            name: '5☆',
	            children: [{
	                name: '纳博科夫短篇小说全集'
	            }]
	        }, {
	            name: '4☆',
	            children: [{
	                name: '安魂曲'
	            }, {
	                name: '人生拼图版'
	            }]
	        }, {
	            name: '3☆',
	            children: [{
	                name: '比起爱你，我更需要你'
	            }]
	        }]
	    }]
	}, {
	    name: '非虚构',
	    itemStyle: {
	        color: colors[2]
	    },
	    children: [{
	        name: '设计',
	        children: [{
	            name: '5☆',
	            children: [{
	                name: '无界面交互'
	            }]
	        }, {
	            name: '4☆',
	            children: [{
	                name: '数字绘图的光照与渲染技术'
	            }, {
	                name: '日本建筑解剖书'
	            }]
	        }, {
	            name: '3☆',
	            children: [{
	                name: '奇幻世界艺术\n&RPG地图绘制讲座'
	            }]
	        }]
	    }, {
	        name: '社科',
	        children: [{
	            name: '5☆',
	            children: [{
	                name: '痛点'
	            }]
	        }, {
	            name: '4☆',
	            children: [{
	                name: '卓有成效的管理者'
	            }, {
	                name: '进化'
	            }, {
	                name: '后物欲时代的来临',
	            }]
	        }, {
	            name: '3☆',
	            children: [{
	                name: '疯癫与文明'
	            }]
	        }]
	    }, {
	        name: '心理',
	        children: [{
	            name: '5☆',
	            children: [{
	                name: '我们时代的神经症人格'
	            }]
	        }, {
	            name: '4☆',
	            children: [{
	                name: '皮格马利翁效应'
	            }, {
	                name: '受伤的人'
	            }]
	        }, {
	            name: '3☆',
	        }, {
	            name: '2☆',
	            children: [{
	                name: '迷恋'
	            }]
	        }]
	    }, {
	        name: '居家',
	        children: [{
	            name: '4☆',
	            children: [{
	                name: '把房子住成家'
	            }, {
	                name: '只过必要生活'
	            }, {
	                name: '北欧简约风格'
	            }]
	        }]
	    }, {
	        name: '绘本',
	        children: [{
	            name: '5☆',
	            children: [{
	                name: '设计诗'
	            }]
	        }, {
	            name: '4☆',
	            children: [{
	                name: '假如生活糊弄了你'
	            }, {
	                name: '博物学家的神秘动物图鉴'
	            }]
	        }, {
	            name: '3☆',
	            children: [{
	                name: '方向'
	            }]
	        }]
	    }, {
	        name: '哲学',
	        children: [{
	            name: '4☆',
	            children: [{
	                name: '人生的智慧'
	            }]
	        }]
	    }, {
	        name: '技术',
	        children: [{
	            name: '5☆',
	            children: [{
	                name: '代码整洁之道'
	            }]
	        }, {
	            name: '4☆',
	            children: [{
	                name: 'Three.js 开发指南'
	            }]
	        }]
	    }]
	}];
	
	for (var j = 0; j < data.length; ++j) {
	    var level1 = data[j].children;
	    for (var i = 0; i < level1.length; ++i) {
	        var block = level1[i].children;
	        var bookScore = [];
	        var bookScoreId;
	        for (var star = 0; star < block.length; ++star) {
	            var style = (function (name) {
	                switch (name) {
	                    case '5☆':
	                        bookScoreId = 0;
	                        return itemStyle.star5;
	                    case '4☆':
	                        bookScoreId = 1;
	                        return itemStyle.star4;
	                    case '3☆':
	                        bookScoreId = 2;
	                        return itemStyle.star3;
	                    case '2☆':
	                        bookScoreId = 3;
	                        return itemStyle.star2;
	                }
	            })(block[star].name);
	
	            block[star].label = {
	                color: style.color,
	                downplay: {
	                    opacity: 0.5
	                }
	            };
	
	            if (block[star].children) {
	                style = {
	                    opacity: 1,
	                    color: style.color
	                };
	                block[star].children.forEach(function (book) {
	                    book.value = 1;
	                    book.itemStyle = style;
	
	                    book.label = {
	                        color: style.color
	                    };
	
	                    var value = 1;
	                    if (bookScoreId === 0 || bookScoreId === 3) {
	                        value = 5;
	                    }
	
	                    if (bookScore[bookScoreId]) {
	                        bookScore[bookScoreId].value += value;
	                    }
	                    else {
	                        bookScore[bookScoreId] = {
	                            color: colors[bookScoreId],
	                            value: value
	                        };
	                    }
	                });
	            }
	        }
	
	        level1[i].itemStyle = {
	            color: data[j].itemStyle.color
	        };
	    }
	}
	
	var optionChart1 = {
	    backgroundColor: bgColor,
	    color: colors,
	    series: [{
	        type: 'sunburst',
	        center: ['50%', '48%'],
	        data: data,
	        sort: function (a, b) {
	            if (a.depth === 1) {
	                return b.getValue() - a.getValue();
	            }
	            else {
	                return a.dataIndex - b.dataIndex;
	            }
	        },
	        label: {
	            rotate: 'radial',
	            color: bgColor
	        },
	        itemStyle: {
	            borderColor: bgColor,
	            borderWidth: 2
	        },
	        levels: [{}, {
	            r0: 0,
	            r: 40,
	            label: {
	                rotate: 0
	            }
	        }, {
	            r0: 40,
	            r: 105
	        }, {
	            r0: 115,
	            r: 140,
	            itemStyle: {
	                shadowBlur: 2,
	                shadowColor: colors[2],
	                color: 'transparent'
	            },
	            label: {
	                rotate: 'tangential',
	                fontSize: 10,
	                color: colors[0]
	            }
	        }, {
	            r0: 140,
	            r: 145,
	            itemStyle: {
	                shadowBlur: 80,
	                shadowColor: colors[0]
	            },
	            label: {
	                position: 'outside',
	                textShadowBlur: 5,
	                textShadowColor: '#333',
	            },
	            downplay: {
	                label: {
	                    opacity: 0.5
	                }
	            }
	        }]
	    }]
	};
	
	// 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('homeChart1'));
    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(optionChart1);
    
});