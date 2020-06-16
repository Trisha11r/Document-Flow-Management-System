<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	header('Location: index.php');
}
if($_SESSION['UserPerms'][1]!=1)
{
	header('Location: dashboard.php');	
}
$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
//create an instante of the SOAPClient (the API will be available)
$dbSysSoapObj = new SoapClient(NULL, $options);
$docTypes = json_decode($dbSysSoapObj->__SoapCall("query",array("DocumentType",array("*"),array(),array())),true);
$docTypes = $docTypes['res'];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add a Document Type :: DFMS 2016</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/sidepane.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/addDocType.css">
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
			<h1 class="page-heading text-warning">Add a Document Type</h1>
			<hr>
			<form id="register">
				<label>Document Type Name: </label>
				<input type="text" name="DocumentTypeName" placeholder="Document Type Name">
				<br>
				<br>								
				<label>Document Type Description: </label>
				<textarea type="text" name="DocumentTypeDescription" placeholder="Document Type Description"></textarea>
				<br>
				<br>				
				<table class="table table-striped">
					<thead>
						<tr><th>Seq No</th><th>Unit Document Type</th><th>Actions</th></tr>
					</thead>
					<tbody class="unit-doc-types" counter="1">
						<tr class="unit-doc-type-row" seq="1">
							<td>1</td>
							<td class="unit-doc-type-name"></td>
							<td>
								<button class="btn btn-info choose-existing"> <i class="fa fa-bars"></i>Choose from existing</button>
								<button class="btn btn-warning create-new"> <i class="fa fa-external-link-square"></i>Create a new one</button>			
							</td>
						</tr>
					</tbody>					
				</table>
				<br>
				<button class="btn btn-success add-another"> <i class="fa fa-plus-circle"></i>Add another Unit Document</button>
				<br>
				<br>
				
				<hr>
				<button type="submit" class="btn btn-primary submit-form">Create</button>
			</form>
			<div class="window-background hidden"></div>			
			<div class="window hidden">
				<i class="fa fa-close close-window text-danger"></i>
				<div class="window-body"></div>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript" src="./assets/js/sidepane.js"></script>
<script type="text/javascript" src="./assets/js/addDocType.js"></script>
</html>

