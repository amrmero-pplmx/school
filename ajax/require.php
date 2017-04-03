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
	
	if(isset($_SESSION['membername']) && $_SESSION['membername'] ) {
	
		
		// Get setting meta
		$Setting = $FullAdmin->siteSetting();
		
		$albums = '';
		
		$media = new media();
		$media->db = $db;
		
		if (isset($_GET['require'])  && isset($member['id'])) {
			
			if ($_GET['require'] == '/upload_music') {
				
				// Album
				$media->userID = $member['id'];
				$albums = list_options_active('', $media->getUserAlbum());
				
				$required = 'upload/music';
				$media->mediaCatType = '1';
				$cats = $media->getMediaCats();
		
			} elseif ($_GET['require'] == '/upload_video') {
				$required = 'upload/video';
				$media->mediaCatType = '2';
				$cats = $media->getMediaCats();
				
			} elseif ($_GET['require'] == '/upload_photo') {
				$required = 'upload/photo';
				$media->mediaCatType = '3';
				$cats = $media->getMediaCats();
				
			} elseif ($_GET['require'] == '/sell_music') {
				$required = 'sell/music';
				$media->mediaCatType = '1';
				$cats = $media->getMediaCats();
				
			} elseif ($_GET['require'] == '/sell_video') {
				$required = 'sell/video';
				$media->mediaCatType = '2';
				$cats = $media->getMediaCats();
				
			} elseif ($_GET['require'] == '/sell_photo') {
				$required = 'sell/photo';
				$media->mediaCatType = '3';
				$cats = $media->getMediaCats();
				
			} elseif ($_GET['require'] == '/grab_sc_track') {
				$required = 'grab_sc/track';
				$media->mediaCatType = '1';
				$cats = $media->getMediaCats();
				
			} elseif ($_GET['require'] == '/grab_sc_user') {
				$required = 'grab_sc/user';
				$media->mediaCatType = '1';
				$cats = $media->getMediaCats();
				
			} elseif ($_GET['require'] == '/grab_sc_playlist') {
				$required = 'grab_sc/playlist';
				$media->mediaCatType = '1';
				$cats = $media->getMediaCats();
				
			} elseif ($_GET['require'] == '/grab_video') {
				$required = 'grab_youtube/video';
				$media->mediaCatType = '2';
				$cats = $media->getMediaCats();
				
			} elseif ($_GET['require'] == '/grab_channel') {
				$required = 'grab_youtube/channel';
				$media->mediaCatType = '2';
				$cats = $media->getMediaCats();
				
			} elseif ($_GET['require'] == '/grab_playlist') {
				$required = 'grab_youtube/playlist';
				$media->mediaCatType = '2';
				$cats = $media->getMediaCats();
				
			}
			
			//-----------------------
			// Require template file
			//-----------------------
			$profile = new Template("../templates/".$Setting['template']."/layouts/".$required.".tpl");
		}
			
	
		$profile->set("url", $CONF['url']);
		$profile->set('cats', list_options_active(0, $cats));
		$profile->set('albums', $albums);
		
		
		
		//------------------------------------------------------------
		// Loads our layout template, settings its title and content.
		//------------------------------------------------------------
		echo $profile->output();
		
	} else {
		
		echo 'Login first';
		
	}
	
	
?>