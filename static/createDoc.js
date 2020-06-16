$(function(){
	function windowAppear()
	{
		$(".window").removeClass('hidden');
		$(".window-background").removeClass('hidden');		
	}
	function windowDisappear()
	{		
		$(".window").addClass('hidden');
		$(".window-background").addClass('hidden');		
	}
	$(".close-window").click(function(){
		windowDisappear();		
		$(".window-body").html('');
	});	
	
	$(".docTypeFlow").click(function(){
		$(".docTypeFlow").removeClass("bg-success");
		$(this).addClass("bg-success");
		var id = $(this).attr('id');
		$("input[name='DocTypeFlowId']").val(id);
		var docTypeId = $(this).attr('DocTypeId');
		windowAppear();
		var data = {Id: docTypeId};
		$.ajax('../../getDocTemplates.php', {
			method: 'POST',
			data: data,
	        dataType: 'json'
    	}).done(function(result) {
    		if(result.status == "ok")
    		{
    			var str  = '<div class="panel panel-danger"><div class="panel-heading">Choose a Document Template</div><table class="table table-bordered table-hover"><thead><tr><th>Name</th><th>Description</th></tr></thead><tbody>';
	    		var docTemplates = "";
	    		for(var i=0; i<result.res.length;i++)
	    		{
	    			docTemplates += '<tr class="select-doc-template" docTemplateId="' + result.res[i]['Id'] + '"><td>' + result.res[i]['Name'] + '</td><td>' + result.res[i]['Description'] + '</td>';
	    		}
	    		str += docTemplates + '</tbody></table></div>';
				$(".window-body").html(str);
    		}					
		});
	});

	$("body").on("click",".select-doc-template",function(){
		var id = $(this).attr('docTemplateId');
		var docTypeFlowId = $("input[name='DocTypeFlowId']").val();
		$("#"+docTypeFlowId).attr('docTemplateId',id);
		$(".close-window").click();
	});
	
	$(".submit-form").click(function(e){
		e.preventDefault();
		var docName = $("input[name='DocumentName']").val();
		var docDesc = $("textarea[name='DocumentDescription']").val();
		var docTypeFlowId = $("input[name='DocTypeFlowId']").val();
		
		if(docName == '' || docDesc == '')
			alert("All fields are required!");
		else if(docTypeFlowId == '')
			alert("Please a Document Type and Flow!");
		else if(docTemplateId == '')
			alert("Please select a Document Template!");
		else
		{
			var docTemplateId = $("#"+docTypeFlowId).attr("docTemplateId");
			var userGroupId = $("#"+docTypeFlowId).attr("userGroupId");
			var docTypeId = $("#"+docTypeFlowId).attr("docTypeId");
			var data = {DocName: docName, DocDesc: docDesc, DocTypeToDocFlowId: docTypeFlowId, DocTemplateId: docTemplateId, UserGroupId: userGroupId, DocTypeId: docTypeId};
			$.ajax('../../createDocHelper.php', {
				method: 'POST',
		        dataType: 'json',
		        data: data
		    }).done(function(result) { 
		    	if(result.status == "ok")
		    	{
		    		var docId = result.res;
					var form = '<form id="edit-this-doc" action="editDoc.php" method="POST"><input type="text" hidden="hidden" name="DocumentId"></form>';
					$("body").append(form);
					$("input[name='DocumentId']").val(docId);		
					$("#edit-this-doc").submit();
		    	}
		    	else
		    	{
		    		alert("Document could not be created! Please try again!");
		    	}
		    });	
		}
	})
});