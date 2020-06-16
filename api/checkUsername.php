<?php
session_start();
if(!isset($_SESSION['UserId']))
{
	echo "Access Denied!";
	die();
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$username = $_POST["Username"];
	$options = array('location' => 'http://localhost/dbmodule/dbsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
	//create an instante of the SOAPClient (the API will be available)
	$dbSysSoapObj = new SoapClient(NULL, $options);
	$user = json_decode($dbSysSoapObj->__SoapCall("query",array("User",array("*"),array("Username"),array($username),$_SESSION['UserId'],session_id())),true);
	if(sizeof($user['res'])>0)
	{
		echo json_encode(array("status"=>"ok","res"=>"used"));
	}
	else
	{
		echo json_encode(array("status"=>"ok","res"=>"not used"));	
	}
}
?>