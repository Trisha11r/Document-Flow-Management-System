<?php
session_start();
class AuthSys
{
	static $conn = NULL;
	static function connect()
	{
		//Acquires the database connection
		require_once 'connection.php';
		//if($conn != NULL)
			self::$conn = Connection::getConnection();
	}
	static function disconnect()
	{
		//Disconnects the database connection
		self::$conn = NULL;
	}
	static function setUser($username,$password,$sessionId) 
	{
		//sets the username and password to session variables
		try
		{
			AuthSys::connect();
			$stmt = "SELECT Id FROM User WHERE Username = :1 AND Password = :2";
			$query = self::$conn->prepare($stmt);
			$query->bindValue(":1",$username);
			$query->bindValue(":2",$password);
			$query->execute();
			$result = $query->fetchAll();			
			if(sizeof($result)==1)
			{
				$stmt1 = "UPDATE User SET SessionId = :1 WHERE Id = :2";
				$query1 = self::$conn->prepare($stmt1);
				$query1->bindValue(":1",$sessionId);
				$query1->bindValue(":2",$result[0]['Id']);
				$query1->execute();
				AuthSys::disconnect();
				return json_encode(array('status' => 'ok','res'=> $result));
			}
			AuthSys::disconnect();
			return json_encode(array('status' => 'err','error' => 'Can not determine user!'));				
		}
		catch(Exceptino $ex) 
		{
			AuthSys::disconnect();
			return json_encode(array('status' => 'err','error' => $ex));
		}
	}
	static function verifyUser($userId,$sessionId)
	{
		try
		{
			AuthSys::connect();
			$stmt = "SELECT * FROM User WHERE Id = :1 AND SessionId = :2";
			$query = self::$conn->prepare($stmt);
			$query->bindValue(":1",$userId);
			$query->bindValue(":2",$sessionId);
			$query->execute();
			$result = $query->fetchAll();			
			AuthSys::disconnect();
			if(sizeof($result)==1)
			{
				return json_encode(array('status' => 'ok','res'=> 'verified'));
			}			
			return json_encode(array('status' => 'err','error' => 'Can not determine user!'));				
		}
		catch(Exceptino $ex) 
		{
			AuthSys::disconnect();
			return json_encode(array('status' => 'err','error' => $ex));
		}
	}	
}
//when in non-wsdl mode the uri option must be specified
$options = array('uri'=>'http://localhost/');
//create a new SOAP server
$server = new SoapServer(NULL,$options);
//attach the API class to the SOAP Server
$server->setClass('AuthSys');
//start the SOAP requests handler
$server->handle();

?>