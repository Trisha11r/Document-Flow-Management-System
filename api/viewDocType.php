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

$docTypes = json_decode($dbSysSoapObj->__SoapCall("query",array("DocumentType",array("*"),array(),array(),$_SESSION['UserId'],session_id())),true);
$docTypes = $docTypes['res'];
$docTypeOptions = "<i class='fa fa-external-link see-details' title='See Details'></i> <i class='fa fa-edit edit-doc-type' title='Edit'></i> <i class='fa fa-close delete-doc-type' title='Delete'></i>";
?>
<!DOCTYPE html>
<html>
<head>
	<title>View Document Type :: DFMS 2016</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/sidepane.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/viewDocType.css">
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
			<h1 class="page-heading text-warning">Document Types</h1>
			<hr>
			<?php
				if(sizeof($docTypes)==0)
				{
					echo "No Document Types to display!!";
				}
				else
				{
			?>
					<table class="table table-striped table-hover">
						<thead>
							<tr><th>Document Type Name</th><th>Document Type Description</th><th>Actions</th></tr>	
						</thead>						
						<tbody>
						<?php					
							for ($j=0; $j < sizeof($docTypes); $j++)
							{ 
								$row = $docTypes[$j];								
								echo "<tr id='".$row['Id']."'><td class='name'>".$row['Name']."</td><td class='description'>".$row['Description']."</td><td>".$docTypeOptions."</td></tr>";
							}
						?>
						</tbody>				
					</table>
			<?php
				}
			?>
			<div class="window-background hidden"></div>			
			<div class="window hidden">
				<i class="fa fa-close close-window text-danger"></i>
				<div class="window-body"></div>
			</div>			
		</div>		
	</div>
</body>
<script type="text/javascript" src="./assets/js/sidepane.js"></script>
<script type="text/javascript" src="./assets/js/viewDocType.js"></script>
</html>
