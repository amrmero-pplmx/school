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
		
		if (isset($_POST['id']) &&  isset($_POST['type']) && isset($_POST['target']) ) {
			
				$action_class->userID = $member['id'];
				$action_class->itemID = $_POST['id'];
				$action_class->itemType = $_POST['type'];
				$media_class->updateType = $_POST['type'];
				
			if ( $_POST['target'] == 'like') {
				
				$check = $action_class->checkLike();
				
				if($check == '0') {
					
					$DO = $action_class->itemLike();
						
					if($DO) {
						
						$media_class->mediaID = $_POST['id'];
						$media_class->updateSet = 'like';
						$add_like = $media_class->updateMediaLike();
						
						if($add_like) {
							echo NotePopup('Thanks for like', 1);
						}
						
					} else {	
						echo NotePopup('Error:'.$DO, 2);
					}
					
				} elseif ($check > '0') {
				
					$DO = $action_class->itemUnLike();
						
					if($DO) {
					
						$media_class->mediaID = $_POST['id'];
						$media_class->updateSet = 'unlike';
						$add_like = $media_class->updateMediaLike();
						
						if($add_like) {
							echo NotePopup('Unliked successfully', 1);
						}

					} else {	
						echo NotePopup('Error:', 2);
					}
				
				}
				
			} elseif  ( $_POST['target'] == 'del_playlist') {
				
				$DO = $action_class->userDeletePlaylist();
				
				if($DO == 1) {
					
					echo NotePopup('Playlist deleted successfully', 1);
					
				} else { echo NotePopup('Error. ', 2); }
				
			} elseif  ( $_POST['target'] == 'del_album') {
				
				$DO = $action_class->userDeleteAlbum();
				
				if($DO == 1) {
					
					echo NotePopup('Album deleted successfully', 1);
					
				} else { echo NotePopup('Error. ', 2); }
				
				
			} elseif  ( $_POST['target'] == 'add_album') {
				
				$url = strtolower(str_replace(' ', '_', $_POST['id']) . '_' . $member['name']);
				
				$action_class->itemType = $url;
				
				if (isset($_FILES['photo'])) {
				
					$cover = upload_media($_FILES['photo'], 6);
					$action_class->itemCover = $cover;
					$DO = $action_class->userAddAlbum();
				}
				
				if($DO == 1) {
					
					echo NotePopup('Album created successfully ', 1);
					
				} else { echo NotePopup('Error. Please upload valid cover.' .$_FILES['photo'], 2); }
				
				
				
			} elseif  ( $_POST['target'] == 'add_playlist') {
				
				$url = strtolower(str_replace(' ', '_', $_POST['id']) . '_' . $member['name']);
				
				$action_class->itemType = $url;
				
				$DO = $action_class->userAddPlaylist();
				
				if($DO == 1) {
					
					echo NotePopup('Playlist created successfully ', 1);
					
				} else { echo NotePopup('Error. ', 2); }
				
				
			} elseif  ( $_POST['target'] == 'add_to_playlist') {
			
				$DO = $action_class->userAddToPlaylist();
				
				if($DO == 1) {
					
					echo NotePopup('Added to playlist', 1);
					
				} else { echo NotePopup('Error. ' .$DO, 2); }
				
			} elseif  ( $_POST['target'] == 'del_from_playlist') {
			
				$DO = $action_class->userDelFromPlaylist();
				
				if($DO == 1) {
					
					echo NotePopup('Added to playlist', 1);
					
				} else { echo NotePopup('Error. ' .$DO, 2); }
				
			} elseif  ( $_POST['target'] == 'unfollow') {
			
				$DO = $action_class->userUnFollow();
				
				if($DO == 1) {
					
					echo NotePopup('Thanks for unfollow', 1);
					
				} else { echo NotePopup('Error. ' .$DO, 2); }
				
			} elseif  ( $_POST['target'] == 'follow') {
				
				$DO = $action_class->userFollow();
				
				if($DO == 1) {
					
					echo NotePopup('Thanks for follow', 1);
					
				} else { echo NotePopup('Error. ' .$DO, 2); }
				
			} elseif  ( $_POST['target'] == 'delete') {
				
				$DO = $action_class->itemDelete();
				
				if($DO) {
					
					echo NotePopup('Removed successfully', 1);
					
				}
				
			} elseif  ( $_POST['target'] == 'add_to_cart') {
				
				$DO = $action_class->itemAddToCart();
				
				if($DO == 1) {
				
					echo NotePopup('Added to cart', 1);
					
				} else { echo NotePopup('Failed: ' . $DO, 2); }
			
			} elseif  ( $_POST['target'] == 'remove_from_cart') {
				
				$DO = $action_class->itemRemoveFromCart();
				
				if($DO == 1) {
				
					echo NotePopup('Deleted', 1);
					
				}
				
			} elseif  ( $_POST['target'] == 'payment') {
				
				$DO = payment_process('main' , $_POST['type']);
				
				if($DO) {
				
					echo NotePopup('Wotking ' . $DO, 1);
					
				} else { echo NotePopup('Failed: ' . $DO, 2); }
			
			} elseif  ( $_POST['target'] == 'notes_seen') {
				
				$note_class->userID = $member['id'];
				$DO = $note_class->seen();
				
			} elseif  ( $_POST['target'] == 'msgs_seen') {
				
				$note_class->userID = $member['id'];
				$DO = $note_class->seen();
				
			} elseif  ( $_POST['target'] == 'search_members') {
				
				$search_class->searchTitle = $page_ID;
				$search_class->searchType = $page_TYPE;
				$DO = $search_class->searchUser();
				
				if($DO) {
				
					echo NotePopup('Wotking ' . $DO, 1);
					
				} else { echo NotePopup('Failed: ' . $DO, 2); }
			
			}
			
		} 
		
		
	} else {
	
		echo NotePopup('Please login first.', 2);
	
	}
	
		
?>