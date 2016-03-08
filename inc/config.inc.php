<?php

class Config {
	
	static private $db = array(
		'host'=>'localhost',
		'port'=>'3306',
		'dbname'=>'dbname',
		'user'=>'dbuser',
		'pass'=>'dbpass',
		'type'=>'mysql'
		);

    static public $base_url = 'http://localhost/';
    static public $theme_url = '';



    public static function getDBproperty($key) {
		return isset(self::$db[$key]) ? self::$db[$key] : false;
	}
	
	public static function setDBproperty($key, $value) {
		if (isset(self::$db[$key])) { self::$db[$key] = $value; }
	}
	
}

