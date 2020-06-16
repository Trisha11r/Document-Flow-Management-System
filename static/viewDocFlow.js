$(function(){
	$(".delete-doc-flow").click(function(){
		var docFlowId = $(this).parent().parent().attr('Id');
		var docFlowDescription = $(this).parent().parent().find('.description').text();
		var res = confirm('Do you really want to delete the Document Flow "'+docFlowDescription+'"?');
		if(res == true)
		{
			$.ajax('../../deleteDocFlow.php', {
			method: 'POST',
	        data: {Id: docFlowId},
		    dataType: 'json'
		    }).done(function(data) {
		    	if(data.status=='err')
		    		alert("Document Flow could not be deleted!");
		    	else
		    	{
		    		alert("Document Flow deleted!");
		    		window.location = "viewDocFlow.php";
		    	}
		    });    	
		}		
	});
	$(".edit-doc-flow").click(function(){
		var docFlowId = $(this).parent().parent().attr('Id');
		var form = '<form id="edit-this-doc-flow" action="editDocFlow.php" method="POST"><input type="text" hidden="hidden" name="DocumentFlowId"></form>';
		$(this).html(form);
		$("input[name='DocumentFlowId']").val(docFlowId);		
		$("#edit-this-doc-flow").submit();
	});
});

