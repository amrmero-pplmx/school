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
	
	$action_class = new action(); $media_class = new media;  $user_class = new user; 
	$action_class->db = $db;   $media_class->db = $db;   $user_class->db = $db;
	
	// Check If requested more items are Featured
	if (isset($_POST['type']) && isset($_POST['target']) && isset($_POST['page'])) {
		
		if ($_POST['target'] == 'user-media' || $_POST['target'] == 'user-playlists' || $_POST['target'] == 'user-albums') {
				
			$user_music_tpl  = new Template("../templates/".$Setting['template']."/layouts/user_music.tpl");
			
			$media_class->userID = $_POST['page'];
			
			if ($_POST['type'] == 1 || $_POST['type'] == 2 || $_POST['type'] == 3 ) {
				
				$media_class->mediaType = $_POST['type'];				
				$Do = $media_class->getUserMedia();
				
				if($Do || isset($Do)) {
				
					$result = query_media_items($Do, $user_music_tpl, 'user-media');	
				}
				
			} elseif ($_POST['type'] == 4) {
				
				$Do = $media_class->getUserAlbum();
			
				if($Do || isset($Do)) {
				
					$result = query_albums_list($Do, $search_album_tpl, 'user-media');	
				}
	
			} elseif ($_POST['type'] == 5) {
				
				$Do = $media_class->getUserPlaylists();
			
				if($Do || isset($Do)) {
				
					$result = query_playlists_list($Do, $search_playlist_tpl, 'user-media');	
				}
				
			}
				
			if(isset($Do) && !empty($result)) {
			
				echo $result;
					
			} 
		}
	}
						
