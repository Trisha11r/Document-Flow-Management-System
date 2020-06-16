<?php 
session_start();
if(!isset($_SESSION['UserId']))
{
	header('Location: index.php');
}
if($_SESSION['UserPerms'][0]!=1)
{
	header('Location: dashboard.php');	
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$firstName = $_POST["FirstName"];
	$middleName= $_POST["MiddleName"];
	$lastName= $_POST["LastName"];
	$username= $_POST["Username"];
	$password= $_POST["Password"];
	$phoneNumber= $_POST["PhoneNumber"];
	$address1= $_POST["Address1"];
	$address2= $_POST["Address2"];
	$city= $_POST["City"];
	$state= $_POST["State"];
	$country= $_POST["Country"];
	$pincode= $_POST["Pincode"];
	$department= $_POST["Department"];
	$designation= $_POST["Designation"];

	$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
	//create an instante of the SOAPClient (the API will be available)
	$dbSysSoapObj = new SoapClient(NULL, $options);
	$password = md5($password);

	$user = json_decode($dbSysSoapObj->__SoapCall("insert",array("User",array("FirstName","MiddleName","LastName","Username","Password","PhoneNumber","Address1","Address2","City","State","Country","PinCode","Department","Designation","Photograph","DateTimeAdded","DateTimeModified","IsVerified","VerifiedByUserGroupId","VerifiedDateTime"),array($firstName,$middleName,$lastName,$username,$password,$phoneNumber,$address1,$address2,$city,$state,$country,$pincode,$department,$designation,"","","","","",""))),true);

	if($user['status']=="ok")
	{
		echo "<script type='text/javascript'> alert('User successfully created');</script>";
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title> Register User :: DFMS2016 </title>
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="./assets/css/sidepane.css">
		<link rel="stylesheet" type="text/css" href="./assets/css/register.css">
	</head>
	<body>
		<?php 	
		require_once('navbar.php');
		require_once('sidepane.php');
		?>
		<div class="main-body main-body-compressed">
			<div>
				<h1 class="page-heading text-warning">Add a User</h1>
				<hr>
				<form id="register" method="post">
					<div class="row">
						<div class='col-md-6'>
							<label>FirstName</label>
								<input type='text' name='FirstName'> <br><br>
							<label>MiddleName</label>
								<input type='text' name='MiddleName'> <br><br>
							<label>LastName</label>
								<input type='text' name='LastName'> <br><br>
							<label>Username</label>
								<input type='text' name='Username'> <br><br>
							<label>Password</label>
								<input type='password' name='Password'> <br><br>
							<label>ConfirmPassword</label>
								<input type='password' name='ConfirmPassword'> <br><br>
							<label>PhoneNumber</label>
								<input type='number' name='PhoneNumber'> <br><br>
						</div>
						<div class='col-md-6'>
							<label>Address1</label>
								<input type='text' name='Address1'> <br><br>
							<label>Address2</label>
								<input type='text' name='Address2'> <br><br>
							<label>City</label>
								<input type='text' name='City'> <br><br>
							<label>State</label>
								<input type='text' name='State'> <br><br>
							<label>Country</label>
								<input type='text' name='Country'> <br><br>
							<label>Pincode</label>
								<input type='number' name='Pincode'> <br><br>
							<label>Department</label>
								<input type='text' name='Department'> <br><br>
							<label>Designation</label>
								<input type='text' name='Designation'> <br><br>
						</div>
					</div>									
					<button type="Submit" class="btn btn-primary submit-form">Create</button>
				</form>
			</div>
		</div>
	</body>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="./assets/js/register.js"></script>
	<script type="text/javascript" src="./assets/js/sidepane.js"></script>
</html>