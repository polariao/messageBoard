

$(function(){
	
	//回复
	$(".re-show").click(function(){
		$(this).hide();
		$(this).next(".re-hide").show();
		$(this).parents("div").next(".reply").show();
		$(this).parents("div").prev("table").show();//显示回复列表
		})	
	$(".re-hide").click(function(){
		$(this).hide();
		$(this).prev(".re-show").show();
		$(this).parents("div").next(".reply").hide();
		$(this).parents("div").prev("table").hide();//隐藏回复列
		})		
	
	})