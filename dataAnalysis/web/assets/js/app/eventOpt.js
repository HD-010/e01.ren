/**
 * 
 */
require.config({
	paths : {
		jquery : "http://cdn.e01.ren/common/lib/jquery",
		common : "http://cdn.e01.ren/common/f/common",
		easyForm : "http://cdn.e01.ren/form/lib/easyform",
		echarts : "http://cdn.e01.ren/charts/echarts/echarts",
	},
	shim:{
		echarts :{exports:"echarts"},
	}

});

require(["jquery","common","easyForm","echarts"],function($,common,$e,echarts){
	/*
	 * 点击+号添加对应项的内容
	 * */
	/**
	 * 事件名称的属性设置
	 */
	$(".addtionOpt").click(function(){
		//添加项内容
		var newEventOpt ="<li>和</li><li>"  + 
		$(this).parent().prev().html() + 
		"</li><li><span class='bt delOpt'>✕</span></li>";
		$(newEventOpt).insertBefore(this);
		
		//所有select对象
		var selectObj = $(this).parent().parent().find('select');
		//添加项内容时绑定删除项事件
		$(".delOpt").on('click',function(){
			$(this).parent().prev().remove();
			$(this).parent().prev().remove();
			$(this).parent().remove();
		});
		
		$("select[name='eventOpt[]']").on('click',function(){
			setOptionDisabled(selectObj);
			setOptionRemove(selectObj)
		});
		
		setOptionRemove(selectObj)
		setOptionDisabled(selectObj)
	});
	
	/**
	 * 移出前面select中已经选择的项
	 */
	function setOptionRemove(optDom){
		for(var i = 1; i < optDom.length; i ++){
			//删除前向元素已经选择的项名称
			for(var j = i - 1; j > 0; j--){
				var delVal = $(optDom[j]).val();
				$(optDom[optDom.length-1]).children().remove("[value='"+delVal+"']");
			}
		}
	}
	
	/**
	 * 在当前select中设置后面select中已经选择的项不可用
	 */
	function setOptionDisabled(optDom){
		$(optDom).children().attr("disabled",false);
		for(var i = 1; i < optDom.length; i++){
			//标记后向元素已经选择的项名称
			var selectVal = $(optDom[i]).val();
			for(var j = i - 1; j > 0; j --){
				$(optDom[j]).children("[value='"+selectVal+"']").attr("disabled",true)
			}
		}
	}
	
	/**
	 * 设置按事件的多个属性来查看
	 */
	$(".addtionAttr").click(function(){
		//添加项内容
		var newEventOpt ="<div><li>按</li><li>"  + 
		$(this).parent().prev().html() + 
		"</li><li>查看<span class='bt delOpt'>✕</span></li></div>";
		$(this).parent().parent().append(newEventOpt);
		
		//所有select对象
		var selectObj = $(this).parent().parent().find('select');
		//添加项内容时绑定删除项事件
		$(".delOpt").on('click',function(){
			$(this).parent().prev().remove();
			$(this).parent().prev().remove();
			$(this).parent().remove();
		});
		
		$("select[name='eventOpt[]']").on('click',function(){
			setOptionDisabled(selectObj);
			setOptionRemove(selectObj)
		});
		
		setOptionRemove(selectObj)
		setOptionDisabled(selectObj)
	});
	
	/**
	 * 添加筛选条件
	 */
	$(".addtion").click(function(){
		//添加项内容
		var newEventOpt ="<div name='addAddtion'>" +
		'<li>'+
			'<select name="addtionRelation[]">'+
					'<option value="and">且</option>'+
					'<option value="or">或</option>'+
			'</select>'+
		'</li>'+
		'<li>'+
			'<select name="AddtionAttr[]">'+
				'<option value="all">总体</option>'+
			'</select>'+
		'</li>'+
		'<li>'+
			'<select name="AddtionOper[]">'+
					'<option value="eq">等于</option>'+
					'<option value="neq">不等于</option>'+
					'<option value="con">包含</option>'+
					'<option value="ncon">不包含</option>'+
					'<option value="val">有值</option>'+
					'<option value="nval">没值</option>'+
					'<option value="nu">为空</option>'+
					'<option value="nnu">不为空</option>'+
					'<option value="mac">正则匹配</option>'+
					'<option value="nmac">正则不匹配</option>'+
			'</select>'+
		'</li>'+
		'<li>'+
			//'<select name="AddtionValue[]">'+
			//		'<option value="all">总体</option>'+
			//'</select>'+
			'<input type=text name="AddtionValue[]" value="" />'+
		'</li>'+
		"<li><span class='bt delOpt'>✕</span></li></div>";
		$(newEventOpt).insertBefore($(this).parent().parent());
		
		//判断或且关系要不要显示
		showHideAddAddtion();
		
		//添加项内容时绑定删除项事件
		$(".delOpt").on('click',function(){
			$(this).parent().parent().remove();
			//判断或且关系要不要显示
			showHideAddAddtion();
		});
		
		//添加项内容时绑定选择项事件
		$("div[name='addAddtion'] select").on('change',function(){
			//没有值的类型
			var noValue = ["val","nval","nu","nnu"];
			var val = $(this).val();
			$(this).parent().next().show();
			if(common.inArray(val,noValue) != -1){
				//删除第三选项
				$(this).parent().next().hide();
			}
		});
		
		//添加项内容时绑定改变项名称的事件
		$('select[name="AddtionAttr[]"]').on('change',function(){
			//setAddtionValue();
		})
		
		//添加筛选项的事件属性
		var optionHtml = $('select[name="eventAttr[]"]').eq(0).html();
		$('select[name="AddtionAttr[]"]').html(optionHtml);
		
	});
	
	/*
	 * 判断事件属性关系的显示方式
	 * */
	function showHideAddAddtion(){
		$("div[name='addAddtion']").eq(0).children().eq(0).show();
		if($("div[name='addAddtion']").length > 1){
			$("div[name='addAddtion']").eq(0).children().eq(0).css("visibility","hidden");
		}else{
			$("div[name='addAddtion']").eq(0).children().eq(0).hide();
		}
	}
	
	/**
	 * 日期设置控制
	 */
	var startDate = $("input[name='startData']").val();
	var endDate = $("input[name='endData']").val();
	$("input[name='startData']").on('change',function(){
		var startDate = $(this).val();
		var endDate = $("input[name='endData']").val();
		if(startDate > endDate){
			$("input[name='endData']").val(startDate);
		}
	})
	$("input[name='endData']").on('change',function(){
		var endDate = $(this).val();
		var startDate = $("input[name='startData']").val();
		if(startDate > endDate){
			$("input[name='startData']").val(endDate);
		}
	})
	
	
	
	/**
	 * 提交查询
	 */
	$('button[name="eventQuery"]').on("click",function(){
		var data = $e('form[name="eventQuery"]').sialize();
		$.ajax({
			url:"",
			data : data,
			type : 'post',
			dataType : "json",
			success : function(data){
				console.log(data)
			},
			error : function(data){
				console.log(data)
			}
		});
		return false;
	});
	
	
});
