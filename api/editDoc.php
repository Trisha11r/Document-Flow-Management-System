<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	header('Location: index.php');
}
if($_SERVER["REQUEST_METHOD"] != "POST" || $_POST['DocumentId']=='')
{
	header("Location: dashboard.php");	
}

$docId = $_POST['DocumentId'];

$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
//create an instante of the SOAPClient (the API will be available)
$dbSysSoapObj = new SoapClient(NULL, $options);

$docDetails = json_decode($dbSysSoapObj->__SoapCall("query",array("DocumentInstance",array("Name","Description","DocumentTemplateId"),array('Id'),array($docId),$_SESSION['UserId'],session_id())),true);
$docName = $docDetails['res'][0]['Name'];
$docDesc = $docDetails['res'][0]['Description'];
$docTemplateId = $docDetails['res'][0]['DocumentTemplateId'];

$docType = json_decode($dbSysSoapObj->__SoapCall("query",array("DocumentTemplate",array("DocumentTypeId"),array('Id'),array($docTemplateId),$_SESSION['UserId'],session_id())),true);
$docTypeId = $docType['res'][0]['DocumentTypeId'];

$currentDocDetails = json_decode($dbSysSoapObj->__SoapCall("query",array("DocumentInstanceToDocumentFlow",array("CurrentUserGroupId","CurrentSeqNo"),array('DocumentInstanceId'),array($docId),$_SESSION['UserId'],session_id())),true);
$currentSeqNo = $currentDocDetails['res'][0]['CurrentSeqNo'];
$currentUserGroupId = $currentDocDetails['res'][0]['CurrentUserGroupId'];

$currentUserGroupName = json_decode($dbSysSoapObj->__SoapCall("query",array("UserGroup",array("Name"),array('Id'),array($currentUserGroupId),$_SESSION['UserId'],session_id())),true);
$currentUserGroupName = $currentUserGroupName['res'][0]['Name'];

$sql = "SELECT * FROM DocumentInstanceContents WHERE DocumentInstanceId = ".$docId." ORDER BY SeqNo"; 
$docContents = json_decode($dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id())),true);
$docContents = $docContents['res'];

$sql = "SELECT DTC.Id as Id, DTC.SeqNo as SeqNo, UDocT.Name as Name, UDocT.Description as Description, UDataT.Name as DataType FROM DocumentTypeContents as DTC,UnitDocumentType as UDocT, UnitDataType as UDataT WHERE DTC.UnitDocumentTypeId = UDocT.Id AND UDocT.UnitDataTypeId = UDataT.Id AND DTC.DocumentTypeId = ".$docTypeId." ORDER BY SeqNo"; 

$docTypeContents = json_decode($dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id())),true);
$docTypeContents = $docTypeContents['res'];

?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit a Document :: DFMS 2016</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/sidepane.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/editDoc.css">
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
			<h1 class="page-heading text-warning">Edit a Document</h1>
			<hr>
			<div class="row">
				<div class="col-md-6">
					<h4><strong>Document Name:</strong> <?php echo $docName?></h4>
					<h4><strong>Document Description:</strong> <?php echo $docDesc?></h4>			
					<h4><strong>Current User Group Name:</strong> <?php echo $currentUserGroupName?></h4>
				</div>
				<div class="col-md-6">
					<button type="Submit" class="btn btn-primary save-doc">Save</button>
					<button type="Submit" class="btn btn-danger close-doc">Close</button>
				</div>
			</div>
			<hr>
			<input type="text" name="CurrentSeqNo" hidden="hidden" value="<?php echo $currentSeqNo ?>">
			<input type="text" name="DocumentInstanceId" hidden="hidden" value="<?php echo $docId ?>">
			<?php
				for($i=1; $i<$currentSeqNo; $i++)
				{
					$str = "<div id='".$i."' class='row'><div class='col-md-8'>";
					$str = $str . "<label>". $docTypeContents[$i-1]['Name'] ."</label><br>";
					$str = $str . "<p>". $docContents[$i-1]['Value'] ."</p>";
					$str = $str . "</div><div class='col-md-2'>";
					$str = $str . "<label>Remarks</label><br>";
					$str = $str . "<textarea class='remarks'>". $docContents[$i-1]['Remarks'] ."</textarea>";
					$str = $str . "</div></div>";
					echo $str;
				}
				$str = "<div id='".$i."' class='row'><div class='col-md-8'>";
				$str = $str . "<label>". $docTypeContents[$i-1]['Name'] ."</label><br>";
				$str = $str . "<textarea class='value'>". $docContents[$i-1]['Value'] ."</textarea>";
				$str = $str . "</div><div class='col-md-2'>";
				$str = $str . "<label>Remarks</label><br>";
				$str = $str . "<textarea class='remarks'>". $docContents[$i-1]['Remarks'] ."</textarea>";
				$str = $str . "</div></div>";
				echo $str;
			?>
		</div>
	</div>
</body>
<script type="text/javascript" src="./assets/js/sidepane.js"></script>
<script type="text/javascript" src="./assets/js/editDoc.js"></script>
</html>
