<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	header('Location: index.php');
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$docTypeId = $_POST["Id"];
	
	$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
	//create an instante of the SOAPClient (the API will be available)
	$dbSysSoapObj = new SoapClient(NULL, $options);
	
	$dbSysSoapObj->__SoapCall("delete",array("DocumentTypeContents",array("DocumentTypeId"),array($docTypeId),$_SESSION['UserId'],session_id()));

	$dbSysSoapObj->__SoapCall("delete",array("DocumentType",array("Id"),array($docTypeId),$_SESSION['UserId'],session_id()));
		
	echo json_encode(array("status"=>"ok","res"=>"Document Type Deleted successfully!!"));
}
?>