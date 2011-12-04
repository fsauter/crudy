<?php

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

function say($message, $color = null, $newLine = true) {
	$colors = new Colors();
	echo $colors->getColoredString($message, $color);
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
	say('./crudy s:localhost u:root p:root d:mydatabase t:mytable tpl:path/to/a/templatefile o:path/to/generate/the/model/$.php');
	say('./crudy s:localhost u:root p:root d:mydatabase t:mytable tpl:path/to/a/templatefile o:path/to/generate/the/model/$.php v:1');
	br();
	say('You can omit as many arguments as you desire.');
	br();
	say('s = server');
	say('u = user');
	say('p = password');
	say('d = database');
	say('t = table');
	say('tpl = template file to use [path/to/a/templatefile]');
	say('o = output file using $ as placeholder for the model name [path/to/generate/the/model/$.php]');
	say('v = verbose [1|true]');
	br();
	exit;
}

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