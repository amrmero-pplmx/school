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
	
	
	require_once('../classes/configuration.php');
	require_once('../classes/functions.php');
	
	if(isset($member['id']) && !empty($member['id']) )
	{
			
		if ($_POST['form_type'] == 'edit_user') {
		
			if (isset($_FILES['user_pic']) && $_FILES['user_pic']['size'] > 0) {
				
				$pic_name = upload_media($_FILES['user_pic'], 4);
				$_POST['user_pic'] = $pic_name;
				
			} else {$pic_name = '';}
			
			
			$_POST['user_id'] = $member['id'];
			
			$user_class->userData = $_POST;
			
			$edit = $user_class->editUser();
			
		} elseif ($_POST['form_type'] == 'edit_media') {
			
			$media_class->userID = $member['id'];
			$media_class->mediaData = $_POST;
			
			$edit = $media_class->updateMedia();
		
		}
		
		if($edit == 1) {
				
			echo NotePopup('Updated sueccessfully ', 1);
				
		}  else {
				
			echo NotePopup('Error : '. $edit, 2);
			
		}
			
	} else {
		
		echo NotePopup('Please login first', '2');
		
	}
	