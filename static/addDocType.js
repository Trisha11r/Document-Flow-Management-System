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
	$(".add-another").click(function(e){
		e.preventDefault();
		var counter = parseInt($(".unit-doc-types").attr('counter')) + 1;
		$(".unit-doc-types").attr('counter',counter);
		var str = '<tr class="unit-doc-type-row" seq="' + counter + '"><td>' + counter + '</td><td class="unit-doc-type-name"></td><td><button class="btn btn-info choose-existing"> <i class="fa fa-bars"></i>Choose from existing</button><button class="btn btn-warning create-new"> <i class="fa fa-external-link-square"></i>Create a new one</button></td></tr>';
		$('.unit-doc-types').append(str);
	});
	$(".close-window").click(function(){
		windowDisappear();		
		$(".window-body").html('');
	});	
	$("body").on("click",".choose-existing",function(e){
		var seq = $(this).parent().parent().attr('seq');
		e.preventDefault();
		windowAppear();
		$.ajax('../../getAllUnitDocTypes.php', {
			method: 'POST',
	        dataType: 'json'
	    }).done(function(data) {
	    	if(data.status == 'ok')
	    	{
	    		var str  = '<div class="panel panel-danger" seq="' + seq + '"><div class="panel-heading">Choose a Unit Document Type</div><table class="table table-bordered table-hover"><thead><tr><th>Name</th><th>Description</th><th>Data Type</th></tr></thead><tbody>';
	    		var udoct = "";
	    		for(var i=0; i<data.res.length;i++)
	    		{
	    			udoct += '<tr class="select-unit-doc-type" id="' + data.res[i]['Id'] + '"><td class="udt-name">' + data.res[i]['Name'] + '</td><td class="udt-description">' + data.res[i]['Description'] + '</td><td class="udt-datatype">' + data.res[i]['DataType'] + '</td>';
	    		}
	    		str += udoct + '</tbody></table></div>';
				$(".window-body").html(str);
	    	}
	    });   		
	});
	$("body").on("click",".select-unit-doc-type",function(){
		$(".select-unit-doc-type").removeClass("success");
		$(this).addClass("success");
		var id =$(this).attr('id');
		var name = $(this).find(".udt-name").text() + " (" + $(this).find(".udt-description").text() + ")"; 
		var seq = $(this).parent().parent().parent().attr('seq');
		$("tr[seq='" + seq + "']").attr('id',id);
		$("tr[seq='" + seq + "']").find(".unit-doc-type-name").text(name);
	});
	$("body").on("click",".create-new",function(e){
		e.preventDefault();
		windowAppear();
		var seq = $(this).parent().parent().attr('seq');		
		$.ajax('../../getAllUnitDataTypes.php', {
			method: 'POST',
	        dataType: 'json'
	    }).done(function(data) {
	    	if(data.status == 'ok')
	    	{
	    		var str  = '<div class="panel panel-danger" seq="' + seq + '"><div class="panel-heading">Create a new Unit Document Type</div><div class="panel-body"><label>Name: </label><input type="text" name="UnitDocumentTypeName" placeholder="Unit Document Type Name"><br><br><label>Description: </label><textarea name="UnitDocumentTypeDescription" placeholder="Unit Document Type Description"></textarea><br><br><label>Data Type: </label><select id="unit-data-type-select"><option selected disabled>Choose Here</option>';
	    		var udoct = "";
	    		for(var i=0; i<data.res.length;i++)
	    		{
	    			udoct += '<option value="' + data.res[i]['Id'] + '">' + data.res[i]['Name'] + '</option>';
	    		}
	    		str += udoct + '</select><br><br><button type="submit" class="btn btn-primary create-unit-doc-type">Create</button></div></div>';
				$(".window-body").html(str);
	    	}
	    });
	});
	$("body").on("click",".create-unit-doc-type",function(){
		var unitDocTypeName = $("input[name='UnitDocumentTypeName']").val();
		var unitDocTypeDesc = $("textarea[name='UnitDocumentTypeDescription']").val();
		var unitDataType = $("#unit-data-type-select").val();
		if(unitDocTypeName==''||unitDocTypeDesc==''||unitDataType=='')
			alert('All fields are required!');
		else
		{
			var data = {UnitDocTypeName: unitDocTypeName, UnitDocTypeDesc: unitDocTypeDesc, UnitDataType: unitDataType};
			$.ajax('../../createUnitDocTypeHelper.php', {
				method: 'POST',
				data: data,
		        dataType: 'json'
	    	}).done(function(result) {
	    		if(result.status == "ok")
	    		{
	    			var name = unitDocTypeName + " (" + unitDocTypeDesc + ")"; 
					var seq = $(".panel").attr('seq');
					$("tr[seq='" + seq + "']").attr('id',result.res);
					$("tr[seq='" + seq + "']").find(".unit-doc-type-name").text(name);
	    			$(".close-window").click();
	    		}					
			});
		}		
	});
	$(".submit-form").click(function(e){
		e.preventDefault();
		var docTypeName = $("input[name='DocumentTypeName']").val();
		var docTypeDesc = $("textarea[name='DocumentTypeDescription']").val();
		var flag = false;
		var rows = document.getElementsByClassName("unit-doc-type-row");
		for(var i=0;i<rows.length;i++)
		{
			if($(rows[i]).attr('id') == null)
			{
				flag = true;
				break;
			}
		}
		if(flag || docTypeName == '' || docTypeDesc == '')
			alert("All fields are required!");
		else
		{
			var udt = new Array(rows.length);
			for(var i=0;i<rows.length;i++)
			{
				udt[i] = $(rows[i]).attr('id');
			}
			var data = {DocTypeName: docTypeName, DocTypeDesc: docTypeDesc, UnitDocTypes: udt};
			$.ajax('../../addDocTypeHelper.php', {
				method: 'POST',
		        dataType: 'json',
		        data: data
		    }).done(function(data) { 
		    	alert(data.res);
		    	window.location = "addDocType.php"; 
		    });	
		}
	})
});