<?php
class Configuration
{	
	//Configuration for connecting the mysql database
	static $p= array(
		'host'=>'localhost',
		'user'=>'root',
		'pass'=>'1234',
		'database'=>'authsys_db');

	static function getParameters()
	{
		return (object)self::$p;
	}
}
?>
