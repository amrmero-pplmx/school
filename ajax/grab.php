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
	require_once('../classes/youtube.php');
	
	if(isset($_SESSION['membername'])) {$member = GetMember($_SESSION['membername']);} else {$member = '0';}
	
	if(is_array($member) && $member !== '0' ) {
			
		if (isset($_POST['video_public'])) {$public = $_POST['video_public'];} else {$public = '0';}
			
		$upload = new upload();
						
		$upload->db = $db;
		$upload->userID = $member['id'];
				
		$youtube_class = new YouTube($Setting['youtube_key']);

	
		if ($_POST['form_type'] == 'video') {
			
			$result = $youtube_class->video_info($_POST['video_id']);
			
			// Set media path
			$upload->mediaPath = 'videos/thumbs';
				
			$uploaded_type = $_POST['form_type'];
				
			if (is_array($result)) {
				
				$video = $result['items'][0];
				$max_res_img = $video['snippet']['thumbnails']['maxres']['url'];
				$hq_res_img = $video['snippet']['thumbnails']['high']['url'];
				$video_cover = isset($max_res_img) ? $max_res_img : $hq_res_img;
				$upload->mediaURL = $video_cover;
				$upload->mediaType = MediaNameToNumber('video');
				$upload->mediaName =  $db->real_escape_string($video['snippet']['title']);
				$upload->mediaDesc =  $db->real_escape_string($video['snippet']['description']);
				$upload->mediaCat =  $db->real_escape_string($_POST['video_cat']);
				$upload->mediaCover =  $upload->downloadFileToServer();
				$upload->mediaFile =  $video['id'];
				$upload->mediaFrametype =  'youtube';
				$upload->mediaAllow =  $public;
				$upload->mediaPublish =  '1';
				
				if (!empty($upload->mediaCover)) {	
					$add = $upload->uploadMediaFree();
				}	
			}
			
		} elseif ($_POST['form_type'] == 'channel') {
			
			// Set media path
			$upload->mediaPath = 'videos/thumbs';
			
			if ($_POST['grab_by'] == '1') {
				$channel_id = $youtube_class->channelID_byUsername($_POST['channel_id']);
			} elseif ($_POST['grab_by'] == '2') {
				$channel_id = $_POST['channel_id'];
			}
			
			$result = $youtube_class->channel_videos($channel_id, $_POST['number']);
				
			if (is_array($result)) {
				
				
				foreach ($result['items'] as $video) {
					
					$max_res_img = $video['snippet']['thumbnails']['maxres']['url'];
					$hq_res_img = $video['snippet']['thumbnails']['high']['url'];
					$video_cover = isset($max_res_img) ? $max_res_img : $hq_res_img;
					$upload->mediaURL = $video_cover;
					$upload->mediaType = MediaNameToNumber('video');
					$upload->mediaName =  $db->real_escape_string($video['snippet']['title']);
					$upload->mediaDesc =  $db->real_escape_string($video['snippet']['description']);
					$upload->mediaCat =  $db->real_escape_string($_POST['video_cat']);
					$upload->mediaCover =  $upload->downloadFileToServer();
					$upload->mediaFile =  $video['snippet']['resourceId']['videoId'];
					$upload->mediaFrametype =  'youtube';
					$upload->mediaAllow =  $public;
					$upload->mediaPublish =  '1';
						
					if (!empty($upload->mediaCover)) {	
						$add = $upload->uploadMediaFree();
					}
					
				}	
				
			}	else {
			
				$add = 'Please enter valid channel ID or Username';
			}
			
		} elseif ($_POST['form_type'] == 'playlist') {
			
			// Set media path
			$upload->mediaPath = 'videos/thumbs';
			
			$result = $youtube_class->playlist_videos($_POST['playlist_id'], $_POST['number']);
				
			if (is_array($result)) {
				
				foreach ($result['items'] as $video) {
					
					$max_res_img = $video['snippet']['thumbnails']['maxres']['url'];
					$hq_res_img = $video['snippet']['thumbnails']['high']['url'];
					$video_cover = isset($max_res_img) ? $max_res_img : $hq_res_img;
					$upload->mediaURL = $video_cover;
					$upload->mediaType = MediaNameToNumber('video');
					$upload->mediaName =  $db->real_escape_string($video['snippet']['title']);
					$upload->mediaDesc =  $db->real_escape_string($video['snippet']['description']);
					$upload->mediaCat =  $db->real_escape_string($_POST['video_cat']);
					$upload->mediaCover =  $upload->downloadFileToServer();
					$upload->mediaFile =  $video['snippet']['resourceId']['videoId'];
					$upload->mediaFrametype =  'youtube';
					$upload->mediaAllow =  $public;
					$upload->mediaPublish =  '1';
						
					if (!empty($upload->mediaCover)) {	
						$add = $upload->uploadMediaFree();
					}
				}	
				
			}	else {
			
				$add = 'Please enter valid Playlist ID ';
			}
		}	
		
			
		if($add == '1') {
				
			echo NotePopup('Uploaded sueccessfully ', 1);
				
		}  else {
				
			echo NotePopup('Error! : '.$add, 2);
			
		}
	
	} else {
		
		echo NotePopup('Please login first', '2');
		
	}
	
	