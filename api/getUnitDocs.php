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
	$sql = "SELECT DTC.Id as Id, DTC.SeqNo as SeqNo, UDocT.Name as Name, UDocT.Description as Description, UDataT.Name as DataType FROM DocumentTypeContents as DTC,UnitDocumentType as UDocT, UnitDataType as UDataT WHERE DTC.UnitDocumentTypeId = UDocT.Id AND UDocT.UnitDataTypeId = UDataT.Id AND DTC.DocumentTypeId = ".$docTypeId." ORDER BY SeqNo"; 
	echo $dbSysSoapObj->__SoapCall("genQuery",array($sql,$_SESSION['UserId'],session_id()));
}
?>