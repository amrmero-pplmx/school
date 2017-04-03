<?php

//======================================================================\\

// CMS			                        								\\

//----------------------------------------------------------------------\\

// Copyright (c) 2015 HiMero.net 					    				\\

//----------------------------------------------------------------------\\

// All rights reserved.						 		    				\\

//----------------------------------------------------------------------\\

// http://www.HiMero.net/               								\\

//======================================================================\\

	require_once('../classes/configuration.php');
	require_once('../classes/functions.php');

	if(isset($_SESSION['membername']) && !empty($_SESSION['membername'])) {
		
		if (isset($_POST['comment']) && !empty($_POST['comment'])) {
			
			$comment = addNewComment($_POST, $_SESSION['membername']);
			
			if ($comment == 1) {
			
				$output =  NotePopup('Your comment has been added.' , 1);
			
			} else {
			
				$output =  NotePopup('Please try again later. ' , 2);
			
			}
			
		} else {
			
			$output = NotePopup('You can not add empty comment' , 2);
		
		}
		
	} else {
	
		$output = NotePopup('Please login .' , 2);
	
	}
	
	echo $output;
	
	
	