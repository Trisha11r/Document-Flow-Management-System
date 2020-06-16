$(function(){
	$(".close-doc").click(function(){
		window.location="dashboard.php";
	});
	$(".save-doc").click(function(){
		var currentSeqNo = $("input[name='CurrentSeqNo']").val();
		var documentInstanceId= $("input[name='DocumentInstanceId']").val();
		for (var i = 1; i <= currentSeqNo; i++)
		{
			var remarks = $("#"+i).find(".remarks").val();
			var data = {Remarks: remarks, SeqNo: i, DocumentInstanceId: documentInstanceId};
			$.ajax('../../editDocHelper.php', {
				method: 'POST',
		        dataType: 'json',
		        data: data
		    }).done(function(data) { 
		    	
		    });
		}
		var value = $("#"+currentSeqNo).find(".value").val();
		var data = {Value: value, SeqNo: currentSeqNo, DocumentInstanceId: documentInstanceId};
		$.ajax('../../editDocHelper.php', {
			method: 'POST',
	        dataType: 'json',
	        data: data
	    }).done(function(data) { 
	    	alert("Saved!");
	    });	    
	});	
});