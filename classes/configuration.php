<?php
require_once('dbaser.php');
//======================================================================\\

// Social manager script			                        			\\

//----------------------------------------------------------------------\\

// Copyright (c) 2015 HiMero.net 					    				\\

//----------------------------------------------------------------------\\

// All rights reserved.						 		    				\\

//----------------------------------------------------------------------\\

// http://www.HiMero.net/               								\\

//======================================================================\\

	
	session_start();
	
	/* //////////////////////////////////
	@Params MYSQL data
	///////////////////////////////////*/
	$CONF['host'] = 'localhost';
	$CONF['user'] = 'root';
	$CONF['pass'] = 'root';
	$CONF['name'] = 'school';
	
	// The Installation URL
	//$CONF['url'] = 'http://192.168.1.109/social/';
	$CONF['url'] = 'http://localhost:8080/school/';
	
	// The main path (if installed at root type '/' else type '/[foldername]/' )
	$CONF['path'] = '/school/';
	
	// The Full path 
	$CONF['full_path'] = $_SERVER['DOCUMENT_ROOT'] . $CONF['path'];
	
	// The plugins path 
	$CONF['plugins'] = './extensions/layout/';
	
	
	$mysqli = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
	} else {
		$db = $mysqli;
		$dbaser = new MysqliDb ($mysqli);
	}
	$db->set_charset("utf8");
	
	/* //////////////////////////////////
	@Params Errors display
	///////////////////////////////////*/
	#error_reporting(0);
	error_reporting(E_ALL);

?>