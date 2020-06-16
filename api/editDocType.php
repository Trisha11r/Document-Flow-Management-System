<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	header('Location: index.php');
}
if($_SESSION['UserPerms'][1]!=1)
{
	header("Location: dashboard.php");
}
if($_SERVER["REQUEST_METHOD"] != "POST" || $_POST['DocumentTypeId']=='')
{
	header("Location: dashboard.php");	
}

$docTypeId = $_POST['DocumentTypeId'];

$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
//create an instante of the SOAPClient (the API will be available)
$dbSysSoapObj = new SoapClient(NULL, $options);

$docType = json_decode($dbSysSoapObj->__SoapCall("query",array("DocumentType",array("Name","Description"),array('Id'),array($docTypeId),$_SESSION['UserId'],session_id())),true);
$docTypeName = $docType['res'][0]['Name'];
$docTypeDesc = $docType['res'][0]['Description'];

$sql = "SELECT UDT.Id as Id, UDT.Name as Name, UDT.Description as Description FROM DocumentTypeContents as DTC, UnitDocumentType as UDT WHERE UDT.Id = DTC.UnitDocumentTypeId AND DocumentTypeId = ".$docTypeId." ORDER BY SeqNo"; 
$udt = json_decode($dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id())),true);
$udt = $udt['res'];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit a Document Type :: DFMS 2016</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/sidepane.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/editDocType.css">
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
			<h1 class="page-heading text-warning">Edit a Document Type</h1>
			<hr>
			<form id="register">
				<input type="text" name="DocumentTypeId" hidden="hidden" value="<?=$docTypeId?>">
				<label>Document Type Name: </label>
				<input type="text" name="DocumentTypeName" placeholder="Document Type Name" value="<?=$docTypeName?>">
				<br>
				<br>								
				<label>Document Type Description: </label>
				<textarea type="text" name="DocumentTypeDescription" placeholder="Document Type Description" ><?=$docTypeDesc?></textarea>
				<br>
				<br>				
				<table class="table table-striped">
					<thead>
						<tr><th>Seq No</th><th>Unit Document Type</th><th>Actions</th></tr>
					</thead>
					<tbody class="unit-doc-types" counter="<?=sizeof($udt)?>">
						<?php
							for($i=1;$i<=sizeof($udt);$i++)
							{
						?>
							<tr class="unit-doc-type-row" seq="<?=$i?>" id="<?=$udt[$i-1]['Id']?>">
								<td><?=$i?></td>
								<td class="unit-doc-type-name"><?=($udt[$i-1]['Name']." (".$udt[$i-1]['Description'].")")?></td>
								<td>
									<button class="btn btn-info choose-existing"> <i class="fa fa-bars"></i>Choose from existing</button>
									<button class="btn btn-warning create-new"> <i class="fa fa-external-link-square"></i>Create a new one</button>			
								</td>
							</tr>
						<?php
							}
						?>
					</tbody>					
				</table>
				<br>
				<button class="btn btn-success add-another"> <i class="fa fa-plus-circle"></i>Add another Unit Document</button>
				<br>
				<br>
				
				<hr>
				<button type="submit" class="btn btn-primary submit-form">Save</button>
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
<script type="text/javascript" src="./assets/js/editDocType.js"></script>
</html>
