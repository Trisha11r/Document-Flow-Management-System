<?php
class Connection
{
	//Class Connection responsible for connecting to the database
	static $conn;
	static function getConnection()
	{
		//this function connects to the database and returns a PDO object containing the connection
		require_once 'config.php';
		$parameters = Configuration::getParameters();
		try
		{
			self::$conn = new PDO("mysql:host=".$parameters->host.";dbname=".$parameters->database,$parameters->user,$parameters->pass);
			self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
		return self::$conn;
	}
}
?>
