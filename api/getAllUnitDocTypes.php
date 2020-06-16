<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	echo "Access Denied!";
	die();
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
	//create an instante of the SOAPClient (the API will be available)
	$dbSysSoapObj = new SoapClient(NULL, $options);
	$sql = "SELECT UDocT.Id as Id, UDocT.Name as Name, UDocT.Description as Description, UDataT.Name as DataType FROM UnitDocumentType as UDocT, UnitDataType as UDataT WHERE UDocT.UnitDataTypeId = UDataT.Id";
	echo $dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id()));
}
?>