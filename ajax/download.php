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
	
	if(isset($member['id']) || !empty($member['id'])) {
		
		$member = GetMember($_SESSION['membername']);
		
		if (isset($_GET['id']) &&  isset($_GET['type']) && isset($_GET['target']) ) {
			
			$action_class->userID = $member['id'];
			$action_class->itemID = $_GET['id'];
			$action_class->itemType = $_GET['type'];
			$media_class->updateType = $_GET['type'];
			
			if  ( $_GET['target'] == 'download_media') {
				
				$media = getMedia($_GET['id']);
				
				if (is_array($media)) {
					
					if ($media['type'] == '1') {$folder = 'media/audio/';} 
					if ($media['type'] == '2') {$folder = 'media/videos/';} 
					if ($media['type'] == '3') {$folder = 'media/photos/';} 
							
							
					if ($media['frametype'] == 'local' && $media['allow'] == '1' && $media['paid'] == '0') {
						
						$link = $CONF['url'].$folder.$media['content'];
								
						$ext = explode('.', $media['content']);
								
						header("Cache-Control: public");
						header("Content-Description: File Transfer");
						header("Content-Disposition: attachment; filename=".$media['title'] .'_' . $Setting['sitename'].'.'.end($ext));
						header("Content-Transfer-Encoding: binary");
						header("Content-Type: binary/octet-stream");
						
						readfile($link);
						
						
					} elseif ($media['frametype'] == 'local' && $media['paid'] == '1') {
						
						$check_paid = $action_class->checkPaid();
						
						if ($check_paid == 1) {
							
							$link = $CONF['url'].$folder.'premium/'.$media['link'];
									
							$ext = explode('.', $media['content']);
									
							header("Cache-Control: public");
							header("Content-Description: File Transfer");
							header("Content-Disposition: attachment; filename=".$media['title'] .'_' . $Setting['sitename'].'.'.end($ext));
							header("Content-Transfer-Encoding: binary");
							header("Content-Type: binary/octet-stream");
							
							readfile($link);
						
						} else {
							header("Location: ".$CONF['url'] . "media/".$media['id']." ");
						}
						
					//} elseif ($media['frametype'] !== 'local' && $media['allow'] == '1' && $media['paid'] == '0') {
						
						
						
					} else {
					
						header("Location: ".$CONF['url'] . "media/".$media['id']." ");
						
					}
				}
			}
		} 
		
		
	} else {
	
		echo NotePopup('Please login first.', 2);
	
	}
	
		
?>