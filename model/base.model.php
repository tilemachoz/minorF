<?php

class Base {
	
	protected $db; // Main Database variable
	public $dbType;
	public $host;
	private $user;
	private $pass;
	
	/*** End of DB details ***/
	
	private function init() {
		$this->dbType = Config::getDBproperty('type'); // Gets DB type
		$this->host = $this->dbType.':host='.Config::getDBproperty('host').';dbname='.Config::getDBproperty('dbname');
		$this->user = Config::getDBproperty('user');
		$this->pass = Config::getDBproperty('pass');
	}

	/**
	 *
     */
	public function __construct() {
		$this->init();
		$this->dbConnect();
	}

	public function __destruct() {
		$this->db = null;
	}

	/**
	 *
     */
	private function dbConnect() {
		$options = array(
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		);
		try {
			$this->db = new PDO($this->host,$this->user,$this->pass, $options);	
		} catch (PDOException $e) {die ($e->getMessage());}
	}

	/**
	 * @param $sql
	 * @param array $params
	 * @return mixed
     */
	public function query($sql, $params = array()) {
		$query = $this->db->prepare($sql);
		try {
    		if(!empty($params)){$query->execute($params);}
    		else {$query->execute();}
	    	return $query->fetchAll();
	    } catch(PDOException $e) {return False;}
	}

	/**
	 * @param $sql
	 * @param array $params
	 * @return mixed
     */
	public function nonquery($sql, $params = array()) {
		$query = $this->db->prepare($sql);
		try {
    		if(!empty($params)){return $query->execute($params);}		
    		return $query->execute();		
		} catch(PDOException $e) {return False;}
	}
	
}

