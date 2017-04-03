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

	if(isset($member['id']) ) {
		
		if (isset($_POST['message']) AND isset($_POST['user']) AND isset($_POST['type'])) {
		
			$mytpl = new Template("../templates/".$Setting['template']."/layouts/chat/chat_my_msg.tpl");
			$usertpl = new Template("../templates/".$Setting['template']."/layouts/chat/chat_user_msg.tpl");
				
			if ($_POST['type'] == 'send-message') {
			
					
				$chat_class->senderID = $member['id'];
				$chat_class->userID = $_POST['user'];
				$chat_class->message = $_POST['message'];
				$Do = $chat_class->add();
				
				if($Do == '1') {
					
					$message = $chat_class->lastSentMessage();
					echo query_chat_list($message , $usertpl, $mytpl);
					
				} else {
				
					echo NotePopup('You can not send messages to this user', 2);
				
				}
				
				//echo getChatForm($_GET['to']);
			} elseif ($_POST['type'] == 'get-message') {
				
				$chat_class->userID = $member['id'];
				$chat_class->senderID = $_POST['user'];
				$Do = $chat_class->check_old_chat();
				
				if(is_array($Do)) {
					echo query_chat_list($Do , $usertpl, $mytpl);
				}	
				
			} elseif ($_POST['type'] == 'see-message') {
				
				$chat_class->userID = $member['id'];
				$chat_class->senderID = $_POST['user'];
				$chat_class->set_message_seen();
							
			}
			
		} elseif ($_GET['message'] AND $_GET['to'] AND $_GET['type'] == 2) {
			
			$Do = sendMessage($_GET['to'], $_GET['message']);
			
			if($Do == '1') {
				
				echo lastChatMessage(null);
				
			} elseif ($Do == '2') {
			
				echo NotePopup('You can not send messages to this user');
			
			}
			
		}
		/*
		if ($_GET['type'] == 'check' AND isset($_GET['number'])) {
			
			echo checkNewMessage(null, $_GET['number']);
			
		}
		
		if ($_GET['type'] == 'read' AND isset($_GET['number'])) {
			
			echo readMessage($_GET['number']);
			
		}
		
		if ($_GET['type'] == 'getajax') {
			
			echo chatList();
			
		}
		
		if ($_GET['type'] == 'get' AND isset($_GET['number'])) {
			
			echo getNewMessage($_GET['number']);
			
		}
		*/
		
	} else {
	
		echo NotePopup('Please login first.');
	
	}
	
		
?>