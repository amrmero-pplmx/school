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
		
		if (isset($_POST['publish'])) {$publish = $_POST['publish'];} else {$publish = '0';}
		if (isset($_FILES['photo'])) {$file = $_FILES['photo'];} else {$file = '1';}
		
		
		$admin = new FullAdmin();
			
		$admin->db = $db;
			
		if ($_POST['form_type'] == 'edit_admin') {
			
			$admin->adminId = $_POST['id'];
			
			$admin->adminFullName = $_POST['fullname'];
			
			$admin->adminName = $_POST['name'];
			
			$admin->adminPass = $_POST['password'];
			
			$admin->adminEmail = $_POST['email'];
			
			$admin->adminLevel = $_POST['level'];
			
			$admin->adminPublish = $_POST['publish'];
			
			$editAdmin = $admin->editAdmin();
			
			if($editAdmin == 1) {
				
				echo NotePopup('You successfully updated <b>'.$admin->adminName.'</b> account.', 1);
				
			}  else {
			
				echo NotePopup('Error! : '.$editAdmin, 2);
			
			}
			
		}	
	
		if ($_POST['form_type'] == 'edit_level') {
			
			$editlevel = $admin->editLevel($_POST['id'], $_POST['title'], $_POST['acc_view'], $_POST['acc_edit'], $_POST['set_edit'], $_POST['bulk_msg']);
			
			if($editlevel == 1) {
				
				echo NotePopup('Successfully updated <b>'.$_POST['title'].'</b> level.', 1);
				
			}  else {
			
				echo NotePopup('Error! : '.$editlevel, 2);
			
			}
			
		}	
	
		if ($_POST['form_type'] == 'edit_setting') {
			
			$editSetting = $admin->editSettings($_POST['sitename'], $_POST['desc'], $_POST['keywords'], $_POST['allow_reg'], $_POST['cost'], $_POST['paypal'], $_POST['limit_accounts'], $_POST['limit_pages'], $_POST['limit_groups'], $_POST['limit_events'], $_POST['limit_posts'], $_POST['allow_schedule'], $_POST['allow_stream']);
			
			if($editSetting == 1) {
				
				echo NotePopup('Successfully updated.', 1);
				
			}  else {
			
				echo NotePopup('Error! : ', 2);
			
			}
			
		}	
	
		if ($_POST['form_type'] == 'edit_page') {
			
			$editPage = $admin->editPage($_POST['id'], $_POST['title'], $_POST['prefix'], $_POST['template'], $_POST['layout'], $_POST['content'], $_POST['keywords'], $_POST['desc'], $publish);
			
			if($editPage == 1) {
				
				echo NotePopup('Successfully updated.', 1);
				
			}  else {
			
				echo NotePopup('Error! : ', 2);
			
			}
			
		}	
	
		if ($_POST['form_type'] == 'edit_cat') {
			
			$editPage = $admin->editCategory($_POST['id'], $_POST['title'], $_POST['mother'], $publish);
			
			if($editPage == 1) {
				
				echo NotePopup('Successfully updated.', 1);
				
			}  else {
			
				echo NotePopup('Error! : ', 2);
			
			}
			
		}	
	
		if ($_POST['form_type'] == 'add_post') {
			 
			$add = $admin->editPost($_POST['id'], $_POST['title'], $_POST['cat'], $_POST['short'], $_POST['full'], $publish, $file);
			
			if($add == 1) {
				
				echo $add;
				
			}  else {
			
				echo NotePopup('Error! : '.$add, 2);
			
			}
			
		}	
	
		
		if ($_POST['form_type'] == 'edit_hook') {
			
			if (isset($_POST['hook_title']) && isset($_POST['hook_position']) && isset($_POST['hook_pages'])) {
				
				$media = '';
				
				$add = $admin->update_hook($_POST);
				
				if($add == 1) {
					
					echo $add;
					
				}  else {
				
					echo NotePopup('Error! : '.$add, 2);
				
				}
				
			} else {
			
				echo NotePopup('Error! : Please fill all fields', 2);

			}			
		}	
	
		
	} else {
		
		echo NotePopup('Please login first', '2');
		
	}
	
		
?>