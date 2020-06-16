<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	header('Location: index.php');
}
$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
//create an instante of the SOAPClient (the API will be available)
$dbSysSoapObj = new SoapClient(NULL, $options);

$docTypeFlow = array();
for ($i=0; $i < sizeof($_SESSION["UserGroupId"]); $i++) 
{ 
	$sql = "SELECT DT.Id as DocTypeId, DT.Name as DocTypeName, DT.Description as DocTypeDesc, DF.Id as DocFlowId, DF.Name as DocFlowName, DF.Description as DocFlowDesc, DTTDF.Id as DocTypeToDocFlowId, DFC.UserGroupId as UserGroupId, DFC.SeqNo as SeqNo FROM DocumentType as DT, DocumentFlow as DF, DocumentFlowContents as DFC, DocumentTypeToDocumentFlow as DTTDF WHERE DFC.DocumentFlowId = DTTDF.DocumentFlowId AND DTTDF.DocumentFlowId = DF.Id AND DTTDF.DocumentTypeId = DT.Id AND DFC.SeqNo = 1 AND DFC.UserGroupId = '". $_SESSION["UserGroupId"][$i]."'";
	$docTypeFlowPart = json_decode($dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id())),true);
	$docTypeFlowPart = $docTypeFlowPart['res'];
	foreach ($docTypeFlowPart as $row) 
	{
		array_push($docTypeFlow, $row);
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Create Document :: DFMS 2016</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/sidepane.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/createDoc.css">
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
			<h1 class="page-heading text-warning">Create Document</h1>
			<hr>
			<form id="register">
				<label>Document Name: </label>
				<input type="text" name="DocumentName" placeholder="Document Name">
				<br>
				<br>
				<label>Document Description: </label>
				<textarea name="DocumentDescription" placeholder="Document Description"></textarea>
				<br>
				<br>
				<label>Select Document Type and Flow: </label>
				<input type="text" name="DocTypeFlowId" hidden = "hidden">
				<table class="table table-hover">
					<thead>
						<tr><th>Document Type Name</th><th>Document Type Description</th><th>Document Flow Name</th><th>Document Flow Description</th></tr>	
					</thead>					
					<tbody>
					<?php
						for ($i= 0; $i< sizeof($docTypeFlow); $i++) 
						{
							$row = $docTypeFlow[$i];
							echo "<tr id='".$row['DocTypeToDocFlowId']."' class='docTypeFlow' docTypeId='".$row['DocTypeId']."' docFlowId='".$row['DocFlowId']."' userGroupId='".$row['UserGroupId']."' docTemplateId=''><td>".$row['DocTypeName']."</td><td>".$row['DocTypeDesc']."</td><td>".$row['DocFlowName']."</td><td>".$row['DocFlowDesc']."</td></tr>";
						}
					?>
					</tbody>				
				</table>
				<hr>
				<div class="unit-doc-types"></div>
				<button type="Submit" class="btn btn-primary submit-form">Create</button>
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
<script type="text/javascript" src="./assets/js/createDoc.js"></script>
</html>
