$(function(){
	$(".edit-doc").click(function(){
		var docId = $(this).parent().parent().attr('Id');
		var form = '<form id="edit-this-doc" action="editDoc.php" method="POST"><input type="text" hidden="hidden" name="DocumentId"></form>';
		$(this).html(form);
		$("input[name='DocumentId']").val(docId);		
		$("#edit-this-doc").submit();
	});
	$(".send-doc-forward").click(function(){
		var docId = $(this).parent().parent().attr('Id');
		$.ajax('../../sendDocForward.php', {
		method: 'POST',
        data: {DocumentId: docId},
	    dataType: 'json'
	    }).done(function(result) {
	    	if(result.status=='err')
	    		alert(result.error);
	    	else
	    	{
	    		alert(result.res);
	    		window.location = "dashboard.php";
	    	}
	    });
	});
	$(".send-doc-back").click(function(){
		var docId = $(this).parent().parent().attr('Id');
		$.ajax('../../sendDocBackward.php', {
		method: 'POST',
        data: {DocumentId: docId},
	    dataType: 'json'
	    }).done(function(result) {
	    	if(result.status=='err')
	    		alert(result.error);
	    	else
	    	{
	    		alert(result.res);
	    		window.location = "dashboard.php";
	    	}
	    });
	});
});