<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	header('Location: index.php');
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$docFlowId = $_POST["Id"];
	
	$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
	//create an instante of the SOAPClient (the API will be available)
	$dbSysSoapObj = new SoapClient(NULL, $options);
	
	$dbSysSoapObj->__SoapCall("delete",array("DocumentFlowContents",array("DocumentFlowId"),array($docFlowId),$_SESSION['UserId'],session_id()));

	$dbSysSoapObj->__SoapCall("delete",array("DocumentTypeToDocumentFlow",array("DocumentFlowId"),array($docFlowId),$_SESSION['UserId'],session_id()));
	
	$dbSysSoapObj->__SoapCall("delete",array("DocumentFlow",array("Id"),array($docFlowId),$_SESSION['UserId'],session_id()));
		
	echo json_encode(array("status"=>"ok","res"=>"Document Flow Deleted successfully!!"));
}
?>