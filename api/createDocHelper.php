<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	echo "Access Denied!";
	die();
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$docName = $_POST["DocName"];
	$docDesc = $_POST["DocDesc"];	
	$docTypeToDocFlowId = $_POST["DocTypeToDocFlowId"];
	$userGroupId = $_POST["UserGroupId"];
	$docTemplateId = $_POST["DocTemplateId"];
	$docTypeId = $_POST["DocTypeId"];
	$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
	//create an instante of the SOAPClient (the API will be available)
	$dbSysSoapObj = new SoapClient(NULL, $options);
	
	$doc = json_decode($dbSysSoapObj->__SoapCall("insert",array("DocumentInstance",array("Name","Description","DocumentTemplateId"),array($docName,$docDesc,$docTemplateId),$_SESSION['UserId'],session_id())),true);
	$docId = $doc['res'];
	
	$dbSysSoapObj->__SoapCall("insert",array("DocumentInstanceToDocumentFlow",array("DocumentInstanceId","DocumentTypeToDocumentFlowId","CurrentUserGroupId","CurrentSeqNo"),array($docId,$docTypeToDocFlowId,$userGroupId,1),$_SESSION['UserId'],session_id()));

	$sql = "SELECT Max(SeqNo) as MaxSeqNo FROM DocumentTypeContents WHERE DocumentTypeId = ".$docTypeId; 
	$maxSeqNo = json_decode($dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id())),true);
	$maxSeqNo = $maxSeqNo['res'][0]['MaxSeqNo'];

	for($i=1;$i<=$maxSeqNo;$i++)
	{
		$dbSysSoapObj->__SoapCall("insert",array("DocumentInstanceContents",array("Value","Remarks","DocumentInstanceId","SeqNo"),array("","",$docId,$i),$_SESSION['UserId'],session_id()));
	}
	echo json_encode(array("status"=>"ok","res"=>$docId));
	//echo json_encode(array("status"=>"err","error"=>"Database Error!!"));	
}
?>