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

$sql = "SELECT DF.Id as Id, DF.Name as Name, DF.Description as Description, DT.Name as DocumentTypeName FROM DocumentFlow as DF, DocumentTypeToDocumentFlow as DTTDF, DocumentType as DT where DF.Id = DTTDF.DocumentFlowId AND DTTDF.DocumentTypeId = DT.Id";

$docFlows = json_decode($dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id())),true);
$docFlows = $docFlows['res'];
$docFlowOptions = "<i class='fa fa-edit edit-doc-flow' title='Edit'></i> <i class='fa fa-close delete-doc-flow' title='Delete'></i>";
?>
<!DOCTYPE html>
<html>
<head>
	<title>View Document Flow :: DFMS 2016</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/sidepane.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/viewDocFlow.css">
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
			<h1 class="page-heading text-warning">Document Flows</h1>
			<hr>
			<?php
				if(sizeof($docFlows)==0)
				{
					echo "No Document Flows to display!!";
				}
				else
				{
			?>
					<table class="table table-striped">
						<tr><th>Document Flow Name</th><th>Document Flow Description</th><th>Document Type</th><th>Document Flow Path</th><th>Actions</th></tr>
						<tbody>
						<?php					
							for ($j=0; $j < sizeof($docFlows); $j++)
							{ 
								$row = $docFlows[$j];
								
								$sql = "SELECT UG.Name as Name FROM DocumentFlowContents as DFC, UserGroup as UG where DFC.UserGroupId = UG.Id AND DFC.DocumentFlowId = ".$row['Id']." ORDER BY DFC.SeqNo";
								$userGroups = json_decode($dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id())),true);
								$userGroups = $userGroups['res'];
								$path = "";						
								for ($i=0; $i < sizeof($userGroups) - 1; $i++)
								{ 
									$path = $path . $userGroups[$i]['Name'] . " -> ";	
								}
								$path = $path . $userGroups[$i]['Name'];
								echo "<tr id='".$row['Id']."'><td class='name'>".$row['Name']."</td><td class='description'>".$row['Description']."</td><td>".$row['DocumentTypeName']."</td><td>".$path."</td><td>".$docFlowOptions."</td></tr>";
							}
						?>
						</tbody>				
					</table>
			<?php
				}
			?>			
		</div>
	</div>
</body>
<script type="text/javascript" src="./assets/js/sidepane.js"></script>
<script type="text/javascript" src="./assets/js/viewDocFlow.js"></script>
</html>
