/**
 * 系统属性设置
 */

require(["jquery","common"],function($,common){
	common.append.push([["autoFormate","loginState","friendsState"],"showOnChang","disabled|enlabled"]);
	
	//设置type=text项初始值
	var lines = common.getCookies("showLines") || 5;
	$("input[name=showLines]").val(lines);
	
	//设置type=checkbox项初始值
	var checkBox = $("input[type=checkbox]");
	//根据父级元素的状态，设置子级元素的显示状态。如果父级未选种则子级所有元素灰色不可操作，反之可操作
	for(var o in checkBox){
		//选择框属性名称
		var attName = checkBox[o].name;
		//选择框是否被选中的状态值（这里可能有三种值： string true | string false | null ''）
		var checkStats = common.getCookies(attName);
		//选择框是否被选中的状态值统一为boolen true|false
		checkStats = (checkStats == 'true') ? true : false;
		//设置各个元素的初始状态，表现为 已选中 或 没有选中
		checkBox[o].checked=checkStats;
		
		//获取父元素的初始状态
		var initVal = $("input[name=showOnChang]").is(":checked")
		//根据父元素的状态已选中(boolen true)
		if(initVal){
			//设置以下元素的初始状态
			//$("input[name=showOnChang]").parent().siblings("dd input").removeAttr("disabled");
			
			$("input[name=autoFormate]").removeAttr("disabled");
			$("input[name=loginState]").removeAttr("disabled");
			$("input[name=loginState]").removeAttr("disabled");
			$ ("input[name=friendsState]").removeAttr("disabled");
		}
	}
});