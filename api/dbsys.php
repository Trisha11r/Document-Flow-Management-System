<?php
class DbSys
{	
	/*
	Class DbSys is responsible for handling all requests made to the mysql database
	*/
	static $conn = NULL; //hold the connection
	static $authSysSoapObj = NULL;
	static function connect()
	{
		//Acquires the database connection
		require_once 'connection.php';
		self::$conn = Connection::getConnection();
	}
	static function disconnect()
	{
		//Disconnects the database connection
		self::$conn = NULL;
	}
	static function createAuthConn()
	{
		$options = array('location' => 'http://localhost/authmodule/authsys.php', 
                  'uri' => 'http://localhost/', 'trace'=>true);
		//create an instante of the SOAPClient (the API will be available)
		self::$authSysSoapObj = new SoapClient(NULL, $options);
	}
	static function verify($userId,$sessionId)
	{
		DbSys::createAuthConn();
		$res = json_decode(self::$authSysSoapObj->__SoapCall("verifyUser",array($userId,$sessionId)),true);
		if($res['status']=='ok'&&$res['res']=='verified')
			return true;
		return false;
	}
	static function insert($table,$columns,$data,$userId,$sessionId)
	{	
		/*
		This function can be used to insert records into a table in the database.
		$table - should contain the table name as String
		$columns - should contain the column names where data has to be inserted as array of Strings
		$data - should contain the corresponding data as array of Strings
		*/
		if(DbSys::verify($userId,$sessionId)==false)
		{
			return json_encode(array('status' => 'err', 'error' =>'No User Logged In!'));
			die();
		}
		if(sizeof($columns) != sizeof ($data))
		{
			return json_encode(array('status' => 'err', 'error' =>'Size of Column array and data array does not match!'));
			die();
		}
		try
		{
			DbSys::connect();
			$stmt = "INSERT INTO ".$table." (";
			//adding columns
			for ($i=0; $i < sizeof($columns) -1 ; $i++) 
			{ 
				$stmt = $stmt . $columns[$i]. " , ";	
			}
			$stmt = $stmt . $columns[$i]. " ) VALUES (";
			//adding data
			for ($i=0; $i < sizeof($data) -1 ; $i++) 
			{
				$stmt = $stmt . " :d" . (string)$i. " , ";	
			}
			$stmt = $stmt . " :d" . (string)$i. " );";
			
			$query  = self::$conn->prepare($stmt);			
			
			for ($i=0; $i < sizeof($data); $i++) 
			{
				$param = ":d".(string)$i;
				$query->bindValue($param,$data[$i]);
			}	
			$query->execute();
			$resultId = self::$conn->lastInsertId();
			DbSys::disconnect();
			return json_encode(array('status' => 'ok','res'=>$resultId));
		}
		catch(Exception $e)
		{
			return json_encode(array('status' => 'err', 'error' => $e->getMessage()));
        	die();
    	}
	}
	static function delete($table,$columns,$data,$userId,$sessionId)
	{
		/*
		This function can be used to delete records from a table in the database.
		$table - should contain the table name as String
		$columns - should contain the column names used to select the record as array of Strings
		$data - should contain the corresponding data as array of Strings
		*/
		if(DbSys::verify($userId,$sessionId)==false)
		{
			return json_encode(array('status' => 'err', 'error' =>'No User Logged In!'));
			die();
		}
		if(sizeof($columns) != sizeof ($data))
		{
			return json_encode(array('status' => 'err', 'error' =>'Size of Column array and data array does not match!'));
			die();
		}
		try
		{
			DbSys::connect();
			$stmt = "DELETE FROM ".$table." WHERE ";
			//adding columns & data
			$stmt = $stmt . $columns[0]. " = :d0";	
			for ($i=1; $i < sizeof($columns) ; $i++) 
			{ 
				$stmt = $stmt ." AND ". $columns[$i]. " = :d". (string)$i;	
			}
			
			$query  = self::$conn->prepare($stmt);			
			
			for ($i=0; $i < sizeof($data); $i++) 
			{
				$param = ":d".(string)$i;
				$query->bindValue($param,$data[$i]);
			}
			$query->execute();
			DbSys::disconnect();
			return json_encode(array('status' => 'ok'));
		}
		catch(Exception $e)
		{
			return json_encode(array('status' => 'err', 'error' => $e->getMessage()));
        	die();
    	}
	}
	static function update($table,$columns1,$data1,$columns2,$data2,$userId,$sessionId)
	{
		/*
		This function can be used to update records in a table in the database
		$table - should contain the table name as String
		$columns1 - should contain the column names whose data has to be set as array of Strings
		$data1 - should contain the corresponding data as array of Strings
		$columns2 - should contain the column names used to select the record as array of Strings
		$data2 - should contain the corresponding data as array of Strings
		*/
		if(DbSys::verify($userId,$sessionId)==false)
		{
			return json_encode(array('status' => 'err', 'error' =>'No User Logged In!'));
			die();
		}
		if(sizeof($columns1) != sizeof ($data1) OR sizeof($columns2) != sizeof ($data2) )
		{
			return json_encode(array('status' => 'err', 'error' =>'Size of Column array and data array does not match!'));
			die();
		}
		try
		{
			DbSys::connect();
			$stmt = "UPDATE ".$table." SET ";
			//adding columns & data
			for ($i=0; $i < sizeof($columns1) -1 ; $i++) 
			{ 
				$stmt = $stmt . $columns1[$i]. " = :1d". (string)$i .", ";	
			}
			$stmt = $stmt . $columns1[$i]. " = :1d". (string)$i;

			if(sizeof($columns2)!=0)
			{
				$stmt = $stmt . " WHERE ";
				$stmt = $stmt . $columns2[0]. " = :2d0";	
				for ($i=1; $i < sizeof($columns2) ; $i++) 
				{ 
					$stmt = $stmt ." AND ". $columns2[$i]. " = :2d". (string)$i;	
				}					
			}
			

			$query  = self::$conn->prepare($stmt);			
			
			for ($i=0; $i < sizeof($data1); $i++) 
			{
				$param = ":1d".(string)$i;
				$query->bindValue($param,$data1[$i]);
			}
			for ($i=0; $i < sizeof($data2); $i++) 
			{
				$param = ":2d".(string)$i;
				$query->bindValue($param,$data2[$i]);
			}
			$query->execute();
			DbSys::disconnect();
			return json_encode(array('status' => 'ok'));
		}
		catch(Exception $e)
		{
			return json_encode(array('status' => 'err', 'error' => $e->getMessage()));
        	die();
    	}
	}
	static function query($table,$columns1,$columns2,$data2,$userId,$sessionId)
	{
		/*
		This function can be used to query records in a table in the database
		$table - should contain the table name as String
		$columns1 - should contain the column names which are to be projected as array of Strings
		$columns2 - should contain the column names used to select the record as array of Strings
		$data2 - should contain the corresponding data as array of Strings
		*/
		if(DbSys::verify($userId,$sessionId)==false)
		{
			return json_encode(array('status' => 'err', 'error' =>'No User Logged In!'));
			die();
		}
		
		if(sizeof($columns2) != sizeof ($data2) )
		{
			return json_encode(array('status' => 'err', 'error' =>'Size of Column array and data array does not match!'));
			die();
		}
		try
		{
			DbSys::connect();
			$stmt = "SELECT ";
			//adding columns & data
			for ($i=0; $i < sizeof($columns1) - 1; $i++) 
			{ 
				$stmt = $stmt . $columns1[$i].", ";	
			}
			$stmt = $stmt . $columns1[$i]. " FROM ". $table;

			if(sizeof($columns2)!=0)
			{
				$stmt = $stmt . " WHERE ";
				$stmt = $stmt . $columns2[0]. " = :2d0";	
				for ($i=1; $i < sizeof($columns2) ; $i++) 
				{ 
					$stmt = $stmt ." AND ". $columns2[$i]. " = :2d". (string)$i;	
				}					
			}
			//echo $stmt;
			$query  = self::$conn->prepare($stmt);			
			
			for ($i=0; $i < sizeof($data2); $i++) 
			{
				$param = ":2d".(string)$i;
				$query->bindValue($param,$data2[$i]);
			}
			$query->execute();
			$result = $query->fetchAll();
			DbSys::disconnect();
			return json_encode(array('status' => 'ok','res'=> $result));
		}
		catch(Exception $e)
		{
			return json_encode(array('status' => 'err', 'error' => $e->getMessage()));
        	die();
    	}
	}
	static function genQuery($sql,$userId,$sessionId)
	{
		/*
		This function can be used to perform a general query/command 
		$sql - should contain the sql command as a String
		*/
		if(DbSys::verify($userId,$sessionId)==false)
		{
			return json_encode(array('status' => 'err', 'error' =>'No User Logged In!'));
			die();
		}
		try
		{
			DbSys::connect();
			$stmt = $sql;

			$query  = self::$conn->prepare($stmt);			
			
			$query->execute();
			$result = $query->fetchAll();
			DbSys::disconnect();
			return json_encode(array('status' => 'ok','res'=> $result));
		}
		catch(Exception $e)
		{
			return json_encode(array('status' => 'err', 'error' => $e->getMessage()));
        	die();
    	}
	}	
}
//when in non-wsdl mode the uri option must be specified
$options=array('uri'=>'http://localhost/');
//create a new SOAP server
$server = new SoapServer(NULL,$options);
//attach the API class to the SOAP Server
$server->setClass('DbSys');
//start the SOAP requests handler
$server->handle();

?>