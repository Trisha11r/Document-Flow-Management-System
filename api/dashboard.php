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

$doc = array();
for ($i=0; $i < sizeof($_SESSION["UserGroupId"]); $i++) 
{
	$sql = "SELECT DI.Id as Id, DI.Name as Name, DI.Description as Description, UG.Name as UserGroupName FROM DocumentInstance as DI, DocumentInstanceToDocumentFlow as DITDF, UserGroup as UG where DI.Id = DITDF.DocumentInstanceId AND UG.Id = DITDF.CurrentUserGroupId AND DITDF.CurrentUserGroupId = '". $_SESSION["UserGroupId"][$i]."'";
	$docPart = json_decode($dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id())),true);
	$docPart = $docPart['res'];
	foreach ($docPart as $row) 
	{
		array_push($doc, $row);
	}
}
$docOptions = "<i class='fa fa-chevron-circle-left send-doc-back' title='Send Back'></i> <i class='fa fa-edit edit-doc' title='Edit'></i> <i class='fa fa-chevron-circle-right send-doc-forward' title='Send Forward'></i>";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard :: DFMS 2016</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/sidepane.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/dashboard.css">
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
			<h1 class="page-heading text-warning">Current Documents</h1>
			<hr>
			<?php
				if(sizeof($doc)==0)
				{
					echo "No Document to display!!";
				}
				else
				{
			?>
					<table class="table table-striped">
						<tr><th>Document Name</th><th>Document Description</th><th>Current User Group</th><th>Actions</th></tr>
						<tbody>
						<?php					
							for ($j=0; $j < sizeof($doc); $j++)
							{ 
								$row = $doc[$j];								
								echo "<tr id='".$row['Id']."'><td class='name'>".$row['Name']."</td><td class='description'>".$row['Description']."</td><td>".$row['UserGroupName']."</td><td>".$docOptions."</td></tr>";
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
<script type="text/javascript" src="./assets/js/dashboard.js"></script>
</html>
