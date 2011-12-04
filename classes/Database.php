<?php
/**
 * Holds the Database class.
 * 
 * @author Florian Sauter <floonweb@gmail.com>
 * @version 0.1
 * @package crudy
 */

/**
 * Database helper class.
 * 
 * @author Florian Sauter <floonweb@gmail.com>
 * @package crudy
 */
class Database {
	
	private $connection;
	private $database;
	
	public function connect($server, $user, $password) {
		$this->connection = mysql_connect($server, $user, $password);
		if($this->connection === false) {
			throw new Exception($this->getErrorMessage());
		}
	}
	
	public function getDatabases() {
		$databases = array();
		$result = mysql_query('SHOW DATABASES', $this->connection);
		while ($row = mysql_fetch_assoc($result)) {
		    array_push($databases, $row['Database']);
		}
		return $databases;
	}
	
	public function getErrorMessage() {
		return mysql_error($this->connection);
	}
	
	public function getTables() {
		$tables = array();
		$result = mysql_query('SHOW TABLES FROM '.$this->database, $this->connection);
		while ($row = mysql_fetch_assoc($result)) {
		    array_push($tables, $row['Tables_in_'.$this->database]);
		}
		return $tables;
	}
	
	public function getTableFields($table) {
		$fields = array();
		$result = mysql_query('SHOW COLUMNS FROM '.$table, $this->connection);
		while ($row = mysql_fetch_assoc($result)) {
		    array_push($fields, $row['Field']);
		}
		return $fields;
	}
	
	public function selectDatabase($database) {
		$this->database = $database;
		$selected = mysql_select_db($database, $this->connection);
		if($selected === false) {
			throw new Exception($this->getErrorMessage());
		}
	}
	
}