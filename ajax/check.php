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
	
	if (isset($member['id']) && isset($_POST['type']) && isset($_POST['id'])) {
		
		if ($_POST['type'] == 'notes') {
			
			$count = count_notification();
			$check = check_notification($_POST['id']);
				
			if ($check) {
				$arr = array('count' => $count, 'content' => $check);
				echo json_encode($arr);
			} 	
			
		} elseif ($_POST['type'] == 'chat') {
		
			$count = count_messages();
			$check = check_messages();
				
			if ($check) {
				$arr = array('count' => $count, 'content' => $check);
				echo json_encode($arr);
			} 	
			
		}	
	}
	
	
	/*elseif (isset($_POST['id']) && isset($_POST['link']) && $_POST['check'] == 'false') {
		
		$Do = playMedia($_POST['id']);
		echo $Do;
	
	}*/