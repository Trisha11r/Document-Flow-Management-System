<?php
class FileSys
{
	/* class FileSys manages the filesystem of the DFMS.It caters to the requests made by the user via the business logic module*/
	static $authSysSoapObj = NULL;
	/* The function createAuthConn is used to connect to the Authentication module.The Authentication Module checks permissions on the user and validates the request accordingly.*/
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
	static function readFile($filename, $options='r')
	{
		/* function readFile takes two parameters, the file name to be read (a string), and the options parameter (optional), which can take values as r, rb etc. The default value of the $options parameter has been set to r signifying read permission*/
		if(DbSys::verify($userId,$sessionId)==false)
		{
			return json_encode(array('status' => 'err', 'error' =>'No User Logged In!'));
			die();
		}
		$filePointer= fopen($filename,$options) or die("Unable to open file");			//open file and store pointer to the file in $filePointer
		$fileContents = fread($filePointer, filesize($filename));	//read the contents of the file 
		echo $fileContents;
		fclose($filePointer);		//close the file
	}
	static function writeFile($filename, $data, $options='w')
	{
		/* function writeFile takes two parameters, the file name to be written to (a string), and the options parameter (optional), which can values as w, wb etc. The default value of of teh $options parameter has been set to w signifying write permission*/
		createAuthConn();			//connect to the Authentication Module
		if(DbSys::verify($userId,$sessionId)==false)
		{
			return json_encode(array('status' => 'err', 'error' =>'No User Logged In!'));
			die();
		}		
		$filePointer= fopen($filename,$options) or die("Unable to open file");			//open the file and store the pointer to the file in $filePointer
		fwrite($filePointer, $data);	//write the contents to the file
		fclose($filePointer);			//close the file
	}	
}
//when in non-wsdl mode the uri option must be specified
$options=array('uri'=>'http://localhost/');
//create a new SOAP server
$server = new SoapServer(NULL,$options);
//attach the API class to the SOAP Server
$server->setClass('FileSys');
//start the SOAP requests handler
$server->handle();
?>