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
	require_once('../classes/soundcloud.php');
	
	if(isset($_SESSION['membername'])) {$member = GetMember($_SESSION['membername']);} else {$member = '0';}
	
	if(is_array($member) && $member !== '0' ) {
		
		$add = '';
			
		if (isset($_POST['sc_public'])) {$public = $_POST['sc_public'];} else {$public = '0';}
			
		$upload = new upload();
						
		$upload->db = $db;
		$upload->userID = $member['id'];
				
		$sound_class = new SoundCloud($Setting['soundcloud_key']);
		$sound_class->db = $db;
	
		if ($_POST['form_type'] == 'sc_search') {
		
			$result = $sound_class->search_tracks($_POST['search']);
			
			echo $result;
		
		} elseif ($_POST['form_type'] == 'sc_track') {
			
			$result = $sound_class->Resolve($_POST['url']);
			

			// Set media path
			$upload->mediaPath = 'audio/thumbs';
			
			// Check if single track URL
			if (isset($result['kind']) && $result['kind'] == 'track') {
				
				$track = $result;
				
				$hq_res_img = $track['artwork_url'];
				$cover = str_replace('large', 't500x500', $hq_res_img);
				$upload->mediaURL = $cover;
				$upload->mediaType = MediaNameToNumber('music');
				$upload->mediaDuration = $track['duration'];
				$upload->mediaName =  $db->real_escape_string($track['title']);
				$upload->mediaDesc =  $db->real_escape_string($track['description']);
				$upload->mediaCat =  $db->real_escape_string($_POST['sc_cat']);
				$upload->mediaCover =  $upload->downloadFileToServer();
				$upload->mediaFile =  $track['id'];
				$upload->mediaFrametype =  'soundcloud';
				$upload->mediaAllow =  $public;
				$upload->mediaPublish =  '1';
				
				if (!empty($upload->mediaCover)) {	
					$add = $upload->uploadMediaFree();
				}
					
			// Check if User URL
			} elseif (isset($result[0]['kind']) && $result[0]['kind'] == 'track') {
				

				foreach ($result as $track) {
				
					$hq_res_img = $track['artwork_url'];
					$cover = str_replace('large', 't500x500', $hq_res_img);
					$upload->mediaURL = $cover;
					$upload->mediaType = MediaNameToNumber('music');
					$upload->mediaDuration = $track['duration'];
					$upload->mediaName =  $db->real_escape_string($track['title']);
					$upload->mediaDesc =  $db->real_escape_string($track['description']);
					$upload->mediaCat =  $db->real_escape_string($_POST['sc_cat']);
					$upload->mediaCover =  $upload->downloadFileToServer();
					$upload->mediaFile =  $track['id'];
					$upload->mediaFrametype =  'soundcloud';
					$upload->mediaAllow =  $public;
					$upload->mediaPublish =  '1';
					
					if (!empty($upload->mediaCover)) {	
						$add = $upload->uploadMediaFree();
					}
					
				}
			
			
			// Check if Playlist URL
			} elseif (isset($result['tracks']) && is_array($result['tracks'])) {
				
				foreach ($result['tracks'] as $track) {

				
					$hq_res_img = $track['artwork_url'];
					$cover = str_replace('large', 't500x500', $hq_res_img);
					$upload->mediaURL = $cover;
					$upload->mediaType = MediaNameToNumber('music');
					$upload->mediaDuration = $track['duration'];
					$upload->mediaName =  $db->real_escape_string($track['title']);
					$upload->mediaDesc =  $db->real_escape_string($track['description']);
					$upload->mediaCat =  $db->real_escape_string($_POST['sc_cat']);
					$upload->mediaCover =  $upload->downloadFileToServer();
					$upload->mediaFile =  $track['id'];
					$upload->mediaFrametype =  'soundcloud';
					$upload->mediaAllow =  $public;
					$upload->mediaPublish =  '1';
					
					if (!empty($upload->mediaCover)) {	
						$add = $upload->uploadMediaFree();
					}

					
				}

			
			} else {
				
				$add = $result;
				
			}
				
			if($add == '1') {
					
				echo NotePopup('Uploaded sueccessfully ', 1);
					
			}  else {
					
				echo NotePopup('Error! : ' . $add, 2);
				
			}
			
			
		}	else {
			
			echo NotePopup('Not defined', '2');
		}
		
			
	} else {
		
		echo NotePopup('Please login first', '2');
		
	}
	