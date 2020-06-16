<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	echo "Access Denied!";
	die();
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$docId = $_POST["DocumentId"];
	$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
	//create an instante of the SOAPClient (the API will be available)
	$dbSysSoapObj = new SoapClient(NULL, $options);
	$sql = "SELECT DITDF.CurrentSeqNo as SeqNo, DTTDF.DocumentFlowId as DocumentFlowId FROM DocumentInstance as DI, DocumentInstanceToDocumentFlow as DITDF, DocumentTypeToDocumentFlow as DTTDF WHERE DITDF.DocumentTypeToDocumentFlowId = DTTDF.Id AND DITDF.DocumentInstanceId = DI.Id AND DI.Id = '".$docId."'";
	$doc = json_decode($dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id())),true);
	$doc = $doc['res'][0];
	$nextSeqNo = intval($doc['SeqNo']);
	$nextSeqNo = $nextSeqNo -1;
	$nextUserGroupId = json_decode($dbSysSoapObj->__SoapCall("query",array("DocumentFlowContents",array("UserGroupId"),array("DocumentFlowId","SeqNo"),array($doc['DocumentFlowId'],$nextSeqNo),$_SESSION['UserId'],session_id())),true);
	$nextUserGroupId = $nextUserGroupId['res'];
	if(sizeof($nextUserGroupId)!=1)
	{
		echo json_encode(array('status' => 'err', 'error' =>'Cannot Send Backward!'));
	}
	else	
	{
		$sql = "UPDATE DocumentInstanceToDocumentFlow SET CurrentUserGroupId = ".$nextUserGroupId[0]['UserGroupId'].", CurrentSeqNo = ".$nextSeqNo." WHERE DocumentInstanceId = ".$docId;
		$dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id()));
		echo json_encode(array('status' => 'ok', 'res' =>'Done'));
	}
}
?>