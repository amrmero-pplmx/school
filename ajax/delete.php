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

	session_start();
	
	require_once('../classes/configuration.php');
	require_once('../classes/functions.php');
	
	if(isset($_SESSION['nameAdmin']) && $_SESSION['nameAdmin']) {
			 
		$admin = new FullAdmin();
			
		$admin->db = $db;
			
		if ($_POST['type'] == 'del_admin') {
			
			$admin->adminId = $_POST['id'];
			
			$delete = $admin->DeleteAdmin();
			
			if($delete) {
				
				echo NotePopup('Deleted successfully!' , 1);
				
			}  else {
			
				echo NotePopup('Error: ' . $delete , 2);
			
			}
		}	
			
		if ($_POST['type'] == 'del_level') {
			
			$delete = $admin->DeleteLevel($_POST['id']);
			
			if($delete) {
				
				echo NotePopup('Deleted successfully!' , 1);
				
			}  else {
			
				echo NotePopup('Error: ' . $delete , 2);
			
			}
		}		
		
		if ($_POST['type'] == 'del_page') {
			
			$delete = $admin->DeletePage($_POST['id']);
			
			if($delete) {
				
				echo NotePopup('Deleted successfully!' , 1);
				
			}  else {
			
				echo NotePopup('Error: ' . $delete , 2);
			
			}
		}		
		
		if ($_POST['type'] == 'del_cat') {
			
			$delete = $admin->DeleteCategory($_POST['id']);
			
			if($delete) {
				
				echo NotePopup('Deleted successfully!' , 1);
				
			}  else {
			
				echo NotePopup('Error: ' . $delete , 2);
			
			}
		}		
		
		if ($_POST['type'] == 'del_post') {
			
			$delete = $admin->DeletePost($_POST['id']);
			
			if($delete) {
				
				echo NotePopup('Deleted successfully!' , 1);
				
			}  else {
			
				echo NotePopup('Error: ' . $delete , 2);
			
			}
		}			
		
		if ($_POST['type'] == 'del_hook') {
			
			$delete = $admin->DeleteHook($_POST['id']);
			
			if($delete) {
				
				echo NotePopup('Deleted successfully!' , 1);
				
			}  else {
			
				echo NotePopup('Error: ' . $delete , 2);
			
			}
		}		
		
	} else {
		
		echo NotePopup('Please login first', '2');
		
	}
?>