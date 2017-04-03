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
	
	$action_class = new action(); $media_class = new media;  $user_class = new user;   $post_class = new post; 
	$action_class->db = $db;   $media_class->db = $db;   $user_class->db = $db;   $post_class->db = $db;
	
	// Check If requested more items are Featured
	if (isset($_POST['id']) &&  isset($_POST['type']) && isset($_POST['target']) && isset($_POST['page'])) {
		
		if ($_POST['target'] == 'user-media' || $_POST['target'] == 'media_cat' || $_POST['target'] == 'load-media1' || $_POST['target'] == 'load-media2') {
		
			if ($_POST['type'] == '1' || $_POST['type'] == '2' || $_POST['type'] == '3') {
			
				if ($_POST['target'] == 'media_cat' || $_POST['target'] == 'load-media1' || $_POST['target'] == 'load-media2') {
				
					if ($_POST['type'] == '1') {$media_tpl = $media_music_item;}
					if ($_POST['type'] == '2') {$media_tpl = $media_video_item;}
					if ($_POST['type'] == '3') {$media_tpl = $media_photo_item;}
				
				}	elseif ($_POST['target'] == 'user-media') {
					$media_tpl = $user_music_tpl;
				}
				
				$media_class->userID = $media_class->mediaCat = $_POST['page'];
				$media_class->mediaOrder = $_POST['target'];
				$media_class->mediaID = $_POST['id'];
				$media_class->mediaType = $_POST['type'];
				
				$Do = $media_class->getMoreMedia();
					
				if($Do || isset($Do)) {
				
					$result = query_media_items($Do, $media_tpl, $_POST['target']);	
					
					echo $result;
						
				} 
			} 
			
		} elseif ($_POST['target'] == 'follow_media' && isset($member['id'])) {
		
			if ($_POST['type'] == '1' || $_POST['type'] == '2' || $_POST['type'] == '3') {
			
				if ($_POST['type'] == '1') {$media_tpl = $media_music_item;}
				if ($_POST['type'] == '2') {$media_tpl = $media_video_item;}
				if ($_POST['type'] == '3') {$media_tpl = $media_photo_item;}
				
				$media_class->userID = $member['id'];
				$media_class->mediaID = $_POST['id'];
				$media_class->mediaType = $_POST['type'];
				
				$Do = $media_class->getMoreFolMedia();
					
				if($Do || isset($Do)) {
				
					$result = query_media_items($Do, $media_tpl, $_POST['target']);	
					
					echo $result;
						
				} 
			} 
			
		} elseif ($_POST['target'] == 'blog') {
			
			$category_posts_items_tpl  = new Template("../templates/".$Setting['template']."/layouts/category_posts_items.tpl");
			
			$post_class->postID = $_POST['id'];
			$post_class->postsNum = '6';
			
			$Do = $post_class->getMorePosts();
				
			if($Do || isset($Do)) {
				
				$result = load_beats_posts($Do, $category_posts_items_tpl, 'blog');	
				
				echo $result;
					
			} 
			
		} elseif ($_POST['target'] == 'category') {
			
			$category_posts_items_tpl  = new Template("../templates/".$Setting['template']."/layouts/category_posts_items.tpl");
			
			
			$post_class->postID = $_POST['id'];
			$post_class->postCat = $_POST['page'];
			$post_class->postsNum = '2';
			
			$Do = $post_class->getMorePosts();
				
			if($Do || isset($Do)) {
				
				$result = load_beats_posts($Do, $category_posts_items_tpl, $_POST['target']);	
				
				echo $result;
					
			} 
		}
	}
			
