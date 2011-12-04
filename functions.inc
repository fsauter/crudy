<?php
/**
 * Functions.
 * 
 * @author Florian Sauter <floonweb@gmail.com>
 * @version 0.1
 * @package crudy
 */

/**
 * Returns the stdin input from the cli.
 * @param string $question
 * @param mixed $defaultValue
 * 
 * @return the user input or the passed default value if the user input was empty.
 */
function getUserInput($question, $defaultValue = '') {
	say($question, 'purple', false);
	$handle = fopen ("php://stdin","r");
	$line = fgets($handle);
	$input = trim($line);
	if(empty($input) && empty($defaultValue) === false) {
		return $defaultValue;
	} else {
		return $input;
	}
}

/**
 * Returns the a selected database name.
 * @param Database $db
 * @return the name of the database.
 */
function getDatabaseFromUserInput(Database $db) {
	$databases = $db->getDatabases();
	
	print_r($databases);
	
	$input = getUserInput('Please enter the index of the database you wish to use: ');
	
	if(!is_numeric($input) || $input < 0 || $input >= count($databases)) {
	   abort(); 
	}
	
	$database = $databases[$input];
	
	return $database;
}

/**
 * Returns the a selected table name.
 * @param Database $db
 * @return the name of the table.
 */
function getTableFromUserInput(Database $db) {
	$tables = $db->getTables();
	
	print_r($tables);
	
	$input = getUserInput('Please enter the index of the table you wish to use: ');
	
	if(!is_numeric($input) || $input < 0 || $input >= count($tables)) {
	   abort(); 
	}
	
	$table = $tables[$input];
	
	return $table;
	
}

/**
 * Prints a message.
 *
 * @param string $message
 * @param string $color
 * @param string $newLine
 */
function say($message, $color = null, $newLine = true) {
	echo ColorCLI::getColoredString($message, $color);
	if($newLine) {
		br();
	}
}

function br() {
	echo "\n";
}

function hr() {
	echo "--------------------------------------------------------\n";
}

function abort($message = "\nABORTING!") {
	say($message, 'red');
	exit;
}

function displayHelp() {
	say('Try something like:');
	br();
	say('./crudy');
	say('./crudy s:localhost');
	say('./crudy s:localhost u:root');
	say('./crudy s:localhost u:root p:root');
	say('./crudy s:localhost u:root p:root d:mydatabase');
	say('./crudy s:localhost u:root p:root d:mydatabase t:mytable');
	say('./crudy s:localhost u:root p:root d:mydatabase t:mytable tpl:path/to/a/templatefile');
	say('./crudy s:localhost u:root p:root d:mydatabase t:mytable tpl:path/to/a/templatefile o:folder/to/generate/the/model/');
	say('./crudy s:localhost u:root p:root d:mydatabase t:mytable tpl:path/to/a/templatefile o:folder/to/generate/the/model/$.php v:1');
	br();
	say('You can omit as many arguments as you desire.');
	br();
	say('s = server');
	say('u = user');
	say('p = password');
	say('d = database');
	say('t = table');
	say('tpl = template file to use [path/to/a/templatefile]');
	say('o = output (target) folder using [path/to/generate/the/my/folder]');
	say('v = verbose [1|true]');
	br();
	exit;
}

/**
 * Generates the model.

 * @param string $templateFile
 * @param string $className
 * @param string[] $fields
 * @return the generated model class string.
 */
function generateModel($templateFile, $className, $fields) {
	$fieldArray = array();
	foreach($fields as $fieldName) {
		$fieldArray[] = array('name' => $fieldName, 'uname' => ucfirst($fieldName));
	}
	
	$template = new Template($templateFile);
	$template->setVar('className', $className);
	$template->setVar('fields', $fieldArray);
	$template->setVar('getterFields', $fieldArray);
	$template->setVar('setterFields', $fieldArray);
	return $template->toString();
}