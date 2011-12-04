<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once 'functions.inc';

say('************************************', 'blue');
say('********* WELCOME TO CRUDY *********', 'blue');
say('************************************', 'blue');
br();

$arguments = array();

array_shift($argv);

foreach($argv as $argument) {
	$argumentPieces = explode(':', $argument);
	$arguments[$argumentPieces[0]] = $argumentPieces[1];
	
	// Try to help the dau. h -> help
	$pos = strpos($argumentPieces[0], 'h');
	if ($pos !== false) {
		displayHelp();
	}
}

/** CHECK ARGUMENTS **/

if(isset($arguments['s'])) { $server = $arguments['s']; }
if(isset($arguments['u'])) { $user = $arguments['u']; }
if(isset($arguments['p'])) { $password = $arguments['p']; }
if(isset($arguments['d'])) { $database = $arguments['d']; }
if(isset($arguments['t'])) { $table = $arguments['t']; }
if(isset($arguments['t'])) { $table = $arguments['t']; }
if(isset($arguments['o'])) { $outputFile = $arguments['o']; } else { $outputFile = 'gen/$.php'; }
if(isset($arguments['tpl'])) { $templateFile = $arguments['tpl']; } else { $templateFile = 'templates/model.tpl'; }
if(isset($arguments['v'])) { $debug = $arguments['v'] == 'true' || $arguments['v'] == '1'; } else { $debug = false; }

// Get server credentials.
if(empty($server)) {
	$server = getUserInput('Please enter a valid server [localhost]: ', 'localhost');
}

if(empty($user)) {
	$user = getUserInput('Please enter a mysql user [root]: ', 'root');
}

if(empty($password)) {
	$password = getUserInput('Please enter a mysql user password []: ', '');
}

$db = new Database();

// Try to connect to the server.
try {
	br();
	say('Trying to connect to "'.$server.'" as "'.$user.'": ', null, false);
	$connected = $db->connect($server, $user, $password);
	say('Connected successfully', 'green');
	br();
	hr();
} catch(Exception $e) {
	abort('Could not connect: ' . $db->getErrorMessage());
}

// Get database.
if(empty($database)) {
	$database = getDatabaseFromUserInput($db);
}

try {
	$db->selectDatabase($database);
	say('Selected database "'.$database.'"', 'green');
	hr();
} catch(Exception $e) {
	 abort('Could not select database: ' . $db->getErrorMessage());
}

// Get tables.
$tables = array();
if(empty($table)) {
	$useAllTables = getUserInput('Do you want to use all tables? [y/n]: ', 'y');
	if($useAllTables == 'y') {
		$tables = $db->getTables();
		say('Selected all tables', 'green');
	} else {
		$userTable = getTableFromUserInput($db);
		array_push($tables, $userTable);
		say('Selected table "'.$userTable.'"', 'green');
	}
}
hr();

foreach($tables as $tableToGenerate) {
	// Get fields.
	$fields = $db->getTableFields($tableToGenerate);
	if($debug) {
		say('Fetched the follwing fields:', 'green');
		print_r($fields);
		hr();
	}
	
	// Going to generate the model.
	$className = ucfirst($tableToGenerate);
	$modelClass = generateModel($templateFile, $className, $fields);
	
	if($debug) {
		say('Generated the follwing code:', 'brown');
		echo "\n\n".$modelClass."\n\n";
	}
	
	br();
	
	$tableOutputFile = str_replace('$', $className, $outputFile);
	$bytes = file_put_contents($tableOutputFile, $modelClass, LOCK_EX);
	if($bytes === false) {
		abort('Error while writing model to: "'.$tableOutputFile.'". Please check folder permissions.', 'red');
	} else {
		say('Wrote generated model code to: "'.$tableOutputFile.'"', 'green');
	}
}

br();
say('/************ SUCCESS ************/', 'green');
br();

/**
 * Autoload function.
 */
function __autoload($className) {
	require_once('classes'.DIRECTORY_SEPARATOR.$className.'.php');
}