$(function(){
	$("#DocTypeSelect").change(function(){
		$.ajax('../../getUnitDocs.php', {
			method: 'POST',
	        data: {Id: $(this).val()},
	        dataType: 'json'
	    }).done(function(data) {    	
	    	if( data.status === 'err' ) 
	        {
	            alert('Unable to load Document Type.');
	        }
	        else
	        {
	    		$.ajax('../../getUserGroups.php', {
					method: 'POST',
			        dataType: 'json'
			    }).done(function(ugdata) {    	
			    	if(ugdata.status === 'err')
			    	{
			 			alert('Unable to load User Group Information.');   		
			    	}
			    	else
			    	{
			    		var ugstr = "<select><option selected disabled>Choose here</option>";
			    		for (var i = 0; i < ugdata.res.length; i++) 
			        	{
			        		ugstr += "<option value='" + ugdata.res[i]['Id'] + "'>" + ugdata.res[i]['Name'] + " (" + ugdata.res[i]["Description"]+ ") " + "</option>";
			        	}
			        	ugstr += "</select>";
			    		var str = "<table class='table table-striped'><tr><th>Sequence Number</th><th>Unit Document Type</th><th>Data Type</th><th>User Group</th></tr><tbody>";
			        	for (var i = 0; i < data.res.length; i++) 
			        	{
			        		str += "<tr class='user-group-select'><td>" + data.res[i]["SeqNo"] + "</td><td>" + data.res[i]["Name"] + " (" + data.res[i]["Description"] + ") " + "</td><td>" + data.res[i]["DataType"]+ "</td><td>" + ugstr + "</td></tr>";
			        	}
			        	str += "</tbody></table>";
			        	$(".unit-doc-types").html(str);
			    	}
			    });
	        }
	    });
	});
	$(".submit-form").click(function(e){
		e.preventDefault();
		if($('input[name="DocumentFlowName"]').val()==null)
		{
			alert("Add a Document Flow Name.");
		}
		else if($('input[name="DocumentFlowDescription"]').val()==null)
		{
			alert("Add a Document Flow Description.");
		}
		else if($("#DocTypeSelect").val()==null)
		{
			alert("Select a Document Type.");
		}
		else
		{
			var flag = false;
			var rows = document.getElementsByClassName("user-group-select");
			for(var i=0;i<rows.length;i++)
			{
				if($(rows[i]).find("select").val() == null)
				{
					flag = true;
					break;
				}
			}
			if(flag)
				alert("Select User Group for each Unit Document Type.");
			else
			{
				var docFlowName = $('input[name="DocumentFlowName"]').val();
				var docFlowDesc = $('input[name="DocumentFlowDescription"]').val();
				var docTypeId = $("#DocTypeSelect").val();
				var ug = new Array(rows.length);
				for(var i=0;i<rows.length;i++)
				{
					ug[i] = $(rows[i]).find("select").val();
				}
				var data = {DocFlowName: docFlowName, DocFlowDesc: docFlowDesc, DocTypeId: docTypeId, UserGroups: ug};
				$.ajax('../../addDocFlowHelper.php', {
					method: 'POST',
			        dataType: 'json',
			        data: data
			    }).done(function(data) { 
			    	alert(data.res);
			    	window.location = "addDocFlow.php"; 
			    });
			}
		}
	})	
});