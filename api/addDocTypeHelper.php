<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	echo "Access Denied!";
	die();
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$docTypeName = $_POST["DocTypeName"];
	$docTypeDesc = $_POST["DocTypeDesc"];	
	$udt = $_POST["UnitDocTypes"];
	$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
	//create an instante of the SOAPClient (the API will be available)
	$dbSysSoapObj = new SoapClient(NULL, $options);
	
	$docType = json_decode($dbSysSoapObj->__SoapCall("insert",array("DocumentType",array("Name","Description"),array($docTypeName,$docTypeDesc),$_SESSION['UserId'],session_id())),true);
	$docTypeId = $docType['res'];
	
	$dbSysSoapObj->__SoapCall("insert",array("DocumentTemplate",array("Name","Description","DocumentTypeId"),array("Default", "Default Template", $docTypeId),$_SESSION['UserId'],session_id()));

	for ($i=1; $i <= sizeof($udt) ; $i++) 
	{ 
		$dbSysSoapObj->__SoapCall("insert",array("DocumentTypeContents",array("UnitDocumentTypeId","DocumentTypeId","SeqNo"),array($udt[$i-1],$docTypeId,$i),$_SESSION['UserId'],session_id()));
	}
	echo json_encode(array("status"=>"ok","res"=>"Document Type Created successfully!!"));
	//echo json_encode(array("status"=>"err","error"=>"Database Error!!"));	
}
?>