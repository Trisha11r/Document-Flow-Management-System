<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	header('Location: index.php');
}
if($_SESSION['UserPerms'][2]!=1)
{
	header('Location: dashboard.php');	
}
$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
//create an instante of the SOAPClient (the API will be available)
$dbSysSoapObj = new SoapClient(NULL, $options);
$docTypes = json_decode($dbSysSoapObj->__SoapCall("query",array("DocumentType",array("*"),array(),array(),$_SESSION['UserId'],session_id())),true);
$docTypes = $docTypes['res'];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add a Document Flow :: DFMS 2016</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/sidepane.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/addDocFlow.css">
	<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<?php 	
		require_once('navbar.php');
		require_once('sidepane.php');
	?>
	<div class="main-body main-body-compressed">
		<div>
			<h1 class="page-heading text-warning">Add a Document Flow</h1>
			<hr>
			<form id="register">
				<label>Document Flow Name: </label>
				<input type="text" name="DocumentFlowName" placeholder="Document Flow Name">
				<br>
				<br>
				<label>Document Flow Description: </label>
				<input type="text" name="DocumentFlowDescription" placeholder="Document Flow Description">
				<br>
				<br>
				<label>Select a Document Type: </label>
				<select id="DocTypeSelect">
					<option selected disabled>Choose here</option>				
					<?php
						foreach ($docTypes as $row) {
							echo "<option value='".$row['Id']."'>".$row['Name']."</option>";
						}
					?>					
				</select>
				<hr>
				<div class="unit-doc-types"></div>
				<button type="Submit" class="btn btn-primary submit-form">Create</button>
			</form>
		</div>
	</div>
</body>
<script type="text/javascript" src="./assets/js/sidepane.js"></script>
<script type="text/javascript" src="./assets/js/addDocFlow.js"></script>
</html>
