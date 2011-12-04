crudy
=====

Generate PHP entities from a mysql database.

* Author: Florian Sauter

Requirements
------------

 * PHP 5.2.0

Download & Run crudy
--------------------

Download the latest source code from https://github.com/fsauter/crudy Download page. Current  release is 0.1. 
Move the source to your download folder and extract the archive.

Example 1:

    cd /your/path/to/crudy/
    mkdir target
    chmod 777 target
    chmod +x crudy
    ./crudy

Example 2:

    cd /your/path/to/crudy/
    php crudy.php t:/home/user/generated-files/crudy

Arguments
---------

    ./crudy help

Example
-------

### Console command:
    
    ./crudy s:localhost u:root p:root d:mydatabase t:category

### Console output

    ************************************
    ********* WELCOME TO CRUDY *********
    ************************************


    Trying to connect to "localhost" as "root": Connected successfully

    --------------------------------------------------------
    Selected database "formatportal"
    --------------------------------------------------------
    Processing table "category"
    --------------------------------------------------------

    Wrote generated model code to: "target/Category.php"

/************ SUCCESS ************/

### Generated code:

    <?php
    
    class Category {
    	
    	private $id;
    	private $name;
    	private $shortDescription;
    	
    	public function __construct() {}
    	
    	
    	public function getId() {
    		return $this->id;
    	}
    	
    	public function getName() {
    		return $this->name;
    	}
	
    	public function getShortDescription() {
    		return $this->shortDescription;
    	}
    	
    	
    	public function setId($id) {
    		$this->id = id; 
    	}
	
    	public function setName($name) {
    		$this->name = name; 
    	}
	
    	public function setShortDescription($shortDescription) {
    		$this->shortDescription = shortDescription; 
    	}
    	
    }
