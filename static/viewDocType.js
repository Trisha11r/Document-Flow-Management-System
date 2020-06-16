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
	$(".delete-doc-type").click(function(){
		var docTypeId = $(this).parent().parent().attr('id');
		var docTypeName = $(this).parent().parent().find('.name').text();
		var docTypeDescription = $(this).parent().parent().find('.description').text();
		var res = confirm('Do you really want to delete the Document Type "'+docTypeName+" ("+docTypeDescription+')"?');
		if(res == true)
		{
			$.ajax('../../deleteDocType.php', {
			method: 'POST',
	        data: {Id: docTypeId},
		    dataType: 'json'
		    }).done(function(data) {
		    	if(data.status=='err')
		    		alert("Document Type could not be deleted!");
		    	else
		    	{
		    		alert("Document Type deleted!");
		    		window.location = "viewDocType.php";
		    	}
		    });    	
		}		
	});
	$(".edit-doc-type").click(function(){
		var docTypeId = $(this).parent().parent().attr('Id');
		var form = '<form id="edit-this-doc-type" action="editDocType.php" method="POST"><input type="text" hidden="hidden" name="DocumentTypeId"></form>';
		$(this).html(form);
		$("input[name='DocumentTypeId']").val(docTypeId);		
		$("#edit-this-doc-type").submit();
	});
	$(".see-details").click(function(){
		var docTypeId = $(this).parent().parent().attr('id');
		var docTypeName = $(this).parent().parent().find('.name').text();
		var docTypeDescription = $(this).parent().parent().find('.description').text();
		windowAppear();
		$.ajax('../../getUnitDocs.php', {
			method: 'POST',
	        data: {Id: docTypeId},
	        dataType: 'json'
	    }).done(function(data) {    	
	    	if( data.status === 'err' ) 
	        {
	            alert('Unable to load Document Type.');
	        }
	        else
	        {
	        	var str = "<div class='panel panel-danger'><div class='panel-heading'>Choose a Unit Document Type</div><table class='table table-striped'><thead><tr><th>Sequence Number</th><th>Unit Document Type</th><th>Data Type</th></tr></thead><tbody>";
	        	for (var i = 0; i < data.res.length; i++) 
	        	{
	        		str += "<tr><td>" + data.res[i]["SeqNo"] + "</td><td>" + data.res[i]["Name"] + " (" + data.res[i]["Description"] + ") " + "</td><td>" + data.res[i]["DataType"]+ "</td></tr>";
	        	}
	        	str += "</tbody></table></div>";
	        	$(".window-body").html(str);
	        }
	    });
	});
	$(".close-window").click(function(){
		windowDisappear();		
		$(".window-body").html('');
	});		
});

