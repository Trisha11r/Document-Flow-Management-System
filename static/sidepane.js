$(function(){
	$(".main-menu > li > p").click(function(){
		var li = $(this).parent();
		if(li.find(".fa").hasClass("fa-caret-up"))
		{
			li.find(".fa").removeClass("fa-caret-up").addClass("fa-caret-down");
			li.find("ul").addClass("hidden");			
		}
		else
		{
			$(".main-menu > li").find(".fa").removeClass("fa-caret-up").addClass("fa-caret-down");
			$(".main-menu > li").find("ul").addClass("hidden");
			li.find(".fa").removeClass("fa-caret-down").addClass("fa-caret-up");
			li.find("ul").removeClass("hidden");			
		}		
	});
	$(".toggle-side-pane").click(function(){
		if($(this).hasClass("close-side-pane-button"))
		{
			$(this).removeClass("fa-caret-square-o-left").addClass("fa-caret-square-o-right");
			$(this).removeClass("close-side-pane-button").addClass("open-side-pane-button");
			$(".main-body").removeClass("main-body-compressed").addClass("main-body-enlarged");
			$(".side-pane").removeClass("opened-side-pane").addClass("closed-side-pane");
		}		
		else
		{
			$(this).removeClass("fa-caret-square-o-right").addClass("fa-caret-square-o-left");
			$(this).removeClass("open-side-pane-button").addClass("close-side-pane-button");
			$(".main-body").removeClass("main-body-enlarged").addClass("main-body-compressed");
			$(".side-pane").removeClass("closed-side-pane").addClass("opened-side-pane");
		}
	});
});