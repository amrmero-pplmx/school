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
	require_once("../classes/youtube.php");
	
	
	if (isset($_GET['media']) && isset($_GET['type'])) {
		
		if (isset($_GET['type'])) {
			
			$output = array();
			$output_array = array();
			
			$media = getMedia($_GET['media']);
			
			if(isset($member['id']) && isset($media['id'])) {
				updateTblVal('media', $media['id'], ' `views` = views + 1 ');
			}
			
			if (is_array($media) && $media['frametype'] == 'local' ) {
				
				if ($media['type'] == '1' ) {
					
					$folder = 'media/audio/';
					$file = $CONF['path'].$folder.$media['content'];
					$link = $CONF['url'].$folder.$media['content'];
							
					header("Location: ".$link);
					
				} elseif ($media['type'] == '2' ) {
					
					$folder = 'media/videos/';
					$link = $CONF['url'].$folder.$media['content'];
					$output['title'] = $media['title'];
					$output['artist'] = $media['author'];
					$output['mp4'] = $link;
					$output['oga'] = $link;
					$output['poster'] = $CONF['url'] . 'image.php?src='.$media['thumbs'].'&w=800&h=600&img=m1'; 
					
					array_push($output_array, $output);
					
					$result = json_encode($output_array, JSON_UNESCAPED_SLASHES);
					
					echo $result;
				}
				
			} elseif (is_array($media) && $media['frametype'] == 'youtube') {
			
				//$youtube_class = new YouTube($Setting['youtube_key']);

				//$query = $youtube_class->download_video($media['content']);
				/*
				if (isset($query[0]) && @file_get_contents($query[1]['url'])) {
						
						$link = $query[1]['url'];
						$output['type'] = 'api';
						$output['title'] = $media['title'];
						$output['artist'] = $media['author'];
						//$output['mp4'] = "https://www.youtube.com/watch?v=cfLob5IYMp8";
						$output['mp4'] = $link;
						//$output['oga'] = "https://www.youtube.com/watch?v=cfLob5IYMp8";
						$output['oga'] = $link;
						$output['poster'] = $CONF['url'] . 'image.php?src='.$media['thumbs'].'&w=800&h=600&img=m1'; 
						
						array_push($output_array, $output);
						
						$result = json_encode($output_array, JSON_UNESCAPED_SLASHES);
					
					
				} else {
					*/
					$output['frame'] = 'iframe';
					$output['content'] = $media['content'];
					array_push($output_array, $output);
					$result = json_encode($output_array, JSON_UNESCAPED_SLASHES);
				//}
					
					//echo $result;
					echo $result;
					
					//print_r($query);
			
			} elseif (is_array($media) && $media['frametype'] == 'soundcloud') {
				
				$link = 'http://api.soundcloud.com/tracks/'.$media['content'].'/stream?client_id=' . $Setting['soundcloud_key'];
			
				header("Location: ".$link);
			}
		}
	}
	