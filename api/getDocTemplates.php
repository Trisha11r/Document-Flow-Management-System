<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	echo "Access Denied!";
	die();
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$docTypeId = $_POST["Id"];
	$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
	//create an instante of the SOAPClient (the API will be available)
	$dbSysSoapObj = new SoapClient(NULL, $options);
	echo $dbSysSoapObj->__SoapCall("query",array("DocumentTemplate",array("Id","Name","Description"),array("DocumentTypeId"),array($docTypeId),$_SESSION['UserId'],session_id()));
}
?>