<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	echo "Access Denied!";
	die();
}
if($_SESSION['UserPerms'][2]!=1)
{
	echo "Access Denied!";
	die();
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$docFlowName = $_POST["DocFlowName"];
	$docFlowDesc = $_POST["DocFlowDesc"];	
	$docTypeId = $_POST["DocTypeId"];
	$userGroups = $_POST["UserGroups"];
	$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
	//create an instante of the SOAPClient (the API will be available)
	$dbSysSoapObj = new SoapClient(NULL, $options);
	
	$docFlow = json_decode($dbSysSoapObj->__SoapCall("insert",array("DocumentFlow",array("Name","Description"),array($docFlowName,$docFlowDesc),$_SESSION['UserId'],session_id())),true);
	$docFlowId = $docFlow['res'];
	
	$docTypeToDocFlow = json_decode($dbSysSoapObj->__SoapCall("insert",array("DocumentTypeToDocumentFlow",array("DocumentTypeId","DocumentFlowId"),array($docTypeId,$docFlowId),$_SESSION['UserId'],session_id())),true);
	for ($i=1; $i <= sizeof($userGroups) ; $i++) 
	{ 
		$dbSysSoapObj->__SoapCall("insert",array("DocumentFlowContents",array("UserGroupId","DocumentFlowId","SeqNo"),array($userGroups[$i-1],$docFlowId,$i),$_SESSION['UserId'],session_id()));
	}
	echo json_encode(array("status"=>"ok","res"=>"Document Flow Created successfully!!"));
	//echo json_encode(array("status"=>"err","error"=>"Database Error!!"));	
}
?>