<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	echo "Access Denied!";
	die();
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$unitDocTypeName = $_POST["UnitDocTypeName"];
	$unitDocTypeDesc = $_POST["UnitDocTypeDesc"];	
	$unitDataTypeId = $_POST["UnitDataType"];
	$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
	//create an instante of the SOAPClient (the API will be available)
	$dbSysSoapObj = new SoapClient(NULL, $options);
	
	$unitDocType = json_decode($dbSysSoapObj->__SoapCall("insert",array("UnitDocumentType",array("Name","Description","UnitDataTypeId"),array($unitDocTypeName,$unitDocTypeDesc,$unitDataTypeId),$_SESSION['UserId'],session_id())),true);
	$unitDocTypeId = $unitDocType['res'];
	
	echo json_encode(array("status"=>"ok","res"=>$unitDocTypeId));
	//echo json_encode(array("status"=>"err","error"=>"Database Error!!"));	
}
?>