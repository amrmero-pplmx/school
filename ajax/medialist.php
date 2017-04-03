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
	
	//-----------------------
	// Detect required 
	//-----------------------
	require_once('../classes/configuration.php');
	require_once('../classes/functions.php');
	
	//-----------------------
	// Set Media class
	//-----------------------
	$Media = new media();
	$Media->db = $db;
	
	$result = '';
	
	if (isset($_GET['media']) && isset($_GET['type'])) {
		
		$output = array();
		$output_array = array();
			
		if ($_GET['type'] == 'playlist') {
			
			$Media->playlistID = $_GET['media'];
			$mediaList = $Media->getMediaPlaylistItems();
		
		} elseif ($_GET['type'] == 'album') {
			
			$Media->albumID = $_GET['media'];
			$mediaList = $Media->getAlbumItems();
		}	
		
		if (is_array($mediaList)) {
				
				foreach ($mediaList as $item) {
				
					if (is_array($item) && $item['frametype'] == 'local') {
						
						$link = $CONF['url'] . 'ajax/play.php?media='.$item['id'].'&type=music';
						
					} elseif ($item['frametype'] == 'soundcloud') {
					
						$link = 'http://api.soundcloud.com/tracks/'.$item['content'].'/stream?client_id=' . $Setting['soundcloud_key'];
					
					}
					
					$output['title'] = $item['title'];
					$output['artist'] = $item['author'];
					$output['mp3'] = $link;
					$output['oga'] = $link;
					$output['poster'] = $CONF['url'] . 'image.php?src='.$item['thumbs'].'&w=150&h=150&img=m3'; 
					
					array_push($output_array, $output);
				}
				
				$result = json_encode($output_array, JSON_UNESCAPED_SLASHES);
							
		} else {
			
			$error = array("error"=>NotePopup('No items at this Playlist', 2));
			$result = json_encode($error);
				
		}
	}
	
	echo $result; 