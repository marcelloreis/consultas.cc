<?php
class DATABASE_CONFIG {

	public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'nz-dados',
		'password' => 'D@d05-2013',
		'database' => 'naszaco_pessoas',
		'prefix' => '',
		'encoding' => 'utf8',
	);

	public $natt = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => '62792',
		'database' => 'NATT',
		'prefix' => '',
		'encoding' => 'utf8',
	);	

	public $cel2010 = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => '62792',
		'database' => 'CEL2010',
		'prefix' => '',
		'encoding' => 'utf8',
	);	

//	public $default = array(
//		'datasource' => 'Database/Mysql',
//		'persistent' => false,
//		'host' => 'localhost',
//		'login' => 'naszaco',
//		'password' => 'Azs62792n',
//		'database' => 'naszaco_seng',
//		'prefix' => '',
//		'encoding' => 'utf8',
//	);
}
