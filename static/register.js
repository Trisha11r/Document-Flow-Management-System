$(function(){
	$("input[name='Username']").focusout(function(){
		var username = $("input[name='Username']").val();
		$.ajax('../../checkUsername.php', {
			method: 'POST',
	        data: {Username: username},
	        dataType: 'json'
	    }).done(function(data) {    	
	    	if( data.status != 'err' ) 
	        {
	            if(data.res=='used')
	            {
	            	$('input[name="Username"]').css('border-color', 'red');
	            	alert('Username not available!');
	            }
	            else
	            {
	            	$('input').css('border-color','');
	            }
	        }
	    });
	});
	$(".submit-form").click(function(e){
		e.preventDefault();
		$('input').css('border-color','');
		var firstName= $('input[name="FirstName"]').val();
		var middleName= $('input[name="MiddleName"]').val();
		var lastName= $('input[name="LastName"]').val();
		var username= $('input[name="Username"]').val();
		var password= $('input[name="Password"]').val();
		var confirmPassword= $('input[name="ConfirmPassword"]').val();
		var phoneNumber= $('input[name="PhoneNumber"]').val();
		var address1= $('input[name="Address1"]').val();
		var address2= $('input[name="Address2"]').val();
		var city= $('input[name="City"]').val();
		var state= $('input[name="State"]').val();
		var country= $('input[name="Country"]').val();
		var pincode= $('input[name="Pincode"]').val();
		var department= $('input[name="Department"]').val();
		var designation= $('input[name="Designation"]').val();
		var flag=false;

		if(firstName.length==0){
			$('input[name="FirstName"]').css('border-color', 'red');
			flag=true;
		} 
		if(lastName.length==0){
			$('input[name="LastName"]').css('border-color', 'red');
			flag=true;	
		}
		if(username.length==0){
			$('input[name="Username"]').css('border-color', 'red');
			flag=true;	
		}
		if(password.length==0){
			$('input[name="Password"]').css('border-color', 'red');
			flag=true;	
		}
		if(confirmPassword.length==0){
			$('input[name="ConfirmPassword"]').css('border-color', 'red');
			flag=true;	
		}
		if(phoneNumber.length==0){
			$('input[name="PhoneNumber"]').css('border-color', 'red');
			flag=true;	
		}
		if(address1.length==0){
			$('input[name="Address1"]').css('border-color', 'red');
			flag=true;	
		}
		if(city.length==0){
			$('input[name="City"]').css('border-color', 'red');
			flag=true;	
		}
		if(state.length==0){
			$('input[name="State"]').css('border-color', 'red');
			flag=true;	
		}
		if(country.length==0){
			$('input[name="Country"]').css('border-color', 'red');
			flag=true;	
		}
		if(pincode.length==0){
			$('input[name="Pincode"]').css('border-color', 'red');
			flag=true;	
		}
		if(department.length==0){
			$('input[name="Department"]').css('border-color', 'red');
			flag=true;	
		}
		if(designation.length==0){
			$('input[name="Designation"]').css('border-color', 'red');
			flag=true;	
		}
		if(flag==true){
			alert("Please fill the missing values(red bordered).");
		}
		else if(password!=confirmPassword){
			alert("Passwords given do not match");
		}
		else{
			$("#register").submit();
		}
	});
});
