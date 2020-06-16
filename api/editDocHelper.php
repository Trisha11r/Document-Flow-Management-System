<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	echo "Access Denied!";
	die();
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$remarks = $_POST["Remarks"];
	$value = $_POST["Value"];
	$seqNo = $_POST["SeqNo"];
	$documentInstanceId = $_POST["DocumentInstanceId"];
	$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
	//create an instante of the SOAPClient (the API will be available)
	$dbSysSoapObj = new SoapClient(NULL, $options);
	
	if($value == '')
	{
		$sql = "UPDATE DocumentInstanceContents SET Remarks = '".$remarks."' WHERE DocumentInstanceId = '".$documentInstanceId."' AND SeqNo = ".$seqNo;
		$dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id()));
	}	
	else
	{
		$sql = "UPDATE DocumentInstanceContents SET Value = '".$value."' WHERE DocumentInstanceId = '".$documentInstanceId."' AND SeqNo = ".$seqNo;
		$dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id()));
	}
	echo json_encode(array("status"=>"ok","res"=>"Saved"));
	//echo json_encode(array("status"=>"err","error"=>"Database Error!!"));	
}
?>