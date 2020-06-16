<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	header('Location: index.php');
}
if($_SESSION['UserPerms'][2]!=1)
{
	header("Location: dashboard.php");
}
if($_SERVER["REQUEST_METHOD"] != "POST" || $_POST['DocumentFlowId']=='')
{
	header("Location: dashboard.php");	
}

$docFlowId = $_POST['DocumentFlowId'];

$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
//create an instante of the SOAPClient (the API will be available)
$dbSysSoapObj = new SoapClient(NULL, $options);

$docFlow = json_decode($dbSysSoapObj->__SoapCall("query",array("DocumentFlow",array("Name","Description"),array('Id'),array($docFlowId),$_SESSION['UserId'],session_id())),true);
$docFlowName = $docFlow['res'][0]['Name'];
$docFlowDesc = $docFlow['res'][0]['Description'];

$docTypes = json_decode($dbSysSoapObj->__SoapCall("query",array("DocumentType",array("*"),array(),array(),$_SESSION['UserId'],session_id())),true);
$docTypes = $docTypes['res'];

$docTypeIdSelected = json_decode($dbSysSoapObj->__SoapCall("query",array("DocumentTypeToDocumentFlow",array("DocumentTypeId"),array("DOcumentFlowId"),array($docFlowId),$_SESSION['UserId'],session_id())),true);
$docTypeIdSelected = $docTypeIdSelected['res'][0]['DocumentTypeId'];


$sql = "SELECT DTC.Id as Id, DTC.SeqNo as SeqNo, UDocT.Name as Name, UDocT.Description as Description, UDataT.Name as DataType FROM DocumentTypeContents as DTC,UnitDocumentType as UDocT, UnitDataType as UDataT WHERE DTC.UnitDocumentTypeId = UDocT.Id AND UDocT.UnitDataTypeId = UDataT.Id AND DTC.DocumentTypeId = ".$docTypeIdSelected." ORDER BY SeqNo"; 
$unitDocTypes = json_decode($dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id())),true);
$unitDocTypes = $unitDocTypes['res'];

$ug = json_decode($dbSysSoapObj->__SoapCall("query",array("UserGroup",array("Id","Name","Description"),array(),array(),$_SESSION['UserId'],session_id())),true);
$ug = $ug['res'];

$sql = "SELECT * FROM DocumentFlowContents WHERE DocumentFlowId = ".$docFlowId." ORDER BY SeqNo"; 
$ugSelected = json_decode($dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id())),true);
$ugSelected = $ugSelected['res'];

?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit a Document Flow :: DFMS 2016</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/sidepane.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/editDocFlow.css">
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
			<h1 class="page-heading text-warning">Edit a Document Flow</h1>
			<hr>
			<form id="register">
				<input type="text" name="DocumentFlowId" hidden="hidden" value="<?=$docFlowId?>">
				<label>Document Flow Name: </label>
				<input type="text" name="DocumentFlowName" placeholder="Document Flow Name" value="<?=$docFlowName?>">
				<br>
				<br>
				<label>Document Flow Description: </label>
				<input type="text" name="DocumentFlowDescription" placeholder="Document Flow Description" value="<?=$docFlowDesc?>">
				<br>
				<br>
				<label>Select a Document Type: </label>
				<select id="DocTypeSelect">
					<option disabled>Choose here</option>				
					<?php
						foreach ($docTypes as $row) {
							if($row['Id']==$docTypeIdSelected)
								echo "<option selected value='".$row['Id']."'>".$row['Name']."</option>";
							else
								echo "<option value='".$row['Id']."'>".$row['Name']."</option>";
						}
					?>					
				</select>
				<hr>
				<div class="unit-doc-types">
					<table class='table table-striped'>
						<tr><th>Sequence Number</th><th>Unit Document Type</th><th>Data Type</th><th>User Group</th></tr>
						<tbody>
							<?php
								for ($i=0; $i < sizeof($unitDocTypes); $i++)
								{ 
									$ugstr="<select><option disabled>Choose here</option>";
									for($j=0; $j < sizeof($ug); $j++)
									{
										if($ugSelected[$i]['UserGroupId'] == $ug[$j]['Id'])
											$ugstr =  $ugstr . "<option selected value='" . $ug[$j]['Id'] . "'>" . $ug[$j]['Name'] . " (" . $ug[$j]["Description"]. ") " . "</option>";
										else
											$ugstr =  $ugstr . "<option value='" . $ug[$j]['Id'] . "'>" . $ug[$j]['Name'] . " (" . $ug[$j]["Description"]. ") " . "</option>";
									}
									$ugstr = $ugstr . "</select>";
									echo "<tr class='user-group-select'><td>" . $unitDocTypes[$i]['SeqNo'] . "</td><td>" . $unitDocTypes[$i]['Name'] . " (" . $unitDocTypes[$i]['Description'] . ") " . "</td><td>" . $unitDocTypes[$i]['DataType']. "</td><td>" . $ugstr . "</td></tr>";
								}
							?>
						</tbody>
					</table>
				</div>
				<button type="Submit" class="btn btn-primary submit-form">Save</button>
			</form>
		</div>
	</div>
</body>
<script type="text/javascript" src="./assets/js/sidepane.js"></script>
<script type="text/javascript" src="./assets/js/editDocFlow.js"></script>
</html>
