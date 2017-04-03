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
	
	if(isset($_SESSION['membername'])) {$member = $_SESSION['membername'];} else {$member = '0';}
	
	if(isset($member) && $member !== '0' ) {
		
		if (isset($_POST['publish'])) {$publish = $_POST['publish'];} else {$publish = '0';}
			
		if (isset($_FILES['photo'])) {$file = $_FILES['photo'];} else {$file = '1';}
		
		$admin = new FullAdmin();
				
		$admin->db = $db;
			
		if ($_POST['form_type'] == 'add_post') {
			 
			$add = $admin->AddPost_by_member($_POST['title'], $_POST['cat'], $_POST['short'], $_POST['full'], $publish, $member, $file);
			
			if($add == 1) {
				
				echo $add;
				
			}  else {
			
				echo NotePopup('Error! : '.$add, 2);
			
			}
			
		}	
		
	} else {
		
		echo NotePopup('Please login first', '2');
		
	}

	
	
	
	
	