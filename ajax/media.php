<?php

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
	
	require_once('../classes/configuration.php');
	require_once('../classes/functions.php');
	
	if(isset($_SESSION['nameAdmin']) && $_SESSION['nameAdmin']) {
	
		//--------------------
		// Set the admin class 
		//--------------------
		$FullAdmin = new FullAdmin();
		$FullAdmin->db = $db;
		$Setting = $FullAdmin->siteSetting();
		
		
	
	} else {
		
		echo NotePopup('Error! : Not allowed', 2);
		
	}


?>