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

class comments 
{	

	public $db;
	public $userID;
	public $userName;
	public $commentID;
	public $commentType;
	public $commentTypeID;
	
	
	
	//***********************
	// Queries functions
	//***********************
	function getComment()
	{	
		
		$query = sprintf("SELECT * FROM `comments` WHERE `id` = '%s' ", $this->db->real_escape_string($this->commentID));
		
		$result = $this->db->query($query);
		
		$row = $result->fetch_array();
			
		if($row !== null)	
		{
			return $row;
		
		} else {
			
			return '2';
		
		}
		
	}
	
	function getItemComments()
	{	
		
		$query = sprintf("SELECT * FROM `comments` WHERE `type_id` = '%s' AND  `type` = '%s' ", $this->db->real_escape_string($this->commentTypeID), $this->db->real_escape_string($this->commentType));
			
		$result = $this->db->query($query);
		
		$return = null;
		
		if(isset($result->num_rows))	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
			
		} else {
			
			$return = '2';
		
		}
		
		return $return;
		
	}
	
	function getUserComments()
	{	
		
		$query = sprintf("SELECT * FROM `comments` WHERE `user` = '%s' AND `type_id` = '%s' AND `type` = '%s' ", $this->userID, $this->db->real_escape_string($this->commentTypeID), $this->db->real_escape_string($this->commentType));
			
		$result = $this->db->query($query);
		
		$return = '';
		
		if(isset($result->num_rows))	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
			
		} else {
			
			$return = '2';
		
		}
		
		return $return;
		
	}
	
}	
	
	
function update_comment($id, $comment) {
	
	global $db;
	
	$id = $db->real_escape_string($id);
	
	$comment = filter_html_tags($db->real_escape_string($comment));
	
	$status = get_comment($id, $user);
	
	if ($status['publish'] == 0) {$set = 1;} else {$set = 0;}
	
	$query = sprintf("UPDATE `comments` SET `comment` = '%s' WHERE `id` = '%s' ", $comment, $id );
	
	$do = $db->query($query);
	
	if ($do) {return 1;} 
		
}

function get_comment($id, $user = null) {
	
	global $db;
	
	$query = $db->query(sprintf("SELECT * FROM `comments` WHERE `id` = '%s' LIMIT 1", $db->real_escape_string($id)));
	
	$result = $query->fetch_assoc();
	
	return $result;
	
}

function get_last_comment($type = null, $type_id = null) {
	
	global $db;
	
	$query = $db->query(sprintf("SELECT * FROM `comments` WHERE `type` = '%s' AND `type_id` = '%s' LIMIT 1", $db->real_escape_string($type), $db->real_escape_string($type_id)));
	
	$result = $query->fetch_assoc();
	
	return $result;
	
}

function get_page_comments($type_id = null, $type = null) {
	
	global $db;
	
	$rows ='';
	
	$query = $db->query(sprintf("SELECT * FROM `comments` WHERE `type` = '%s' AND `type_id` =  '%s' ", $db->real_escape_string($type), $db->real_escape_string($type_id)));
	
	while ($result = $query->fetch_assoc()) {
		
		$rows[] = $result;
		
	}
	
	return $rows;
	
}

function view_comments_form($tpl = null, $type_id, $type) {
	
	global $db, $CONF, $Setting;
	
	if (isset($_SESSION['membername']))
	{
		
	}
	
	if ($Setting['comments'] == 1) 
	{
		
		//-----------------------------------------
		// Set custom form TPL or get default form
		//-----------------------------------------
		if ( isset($tpl)) {
			
			$layout = $tpl;
		
		} else {
			
			$layout = new Template("./assets/_tpl/comment_form.tpl");
		}
		
		if (isset($_SESSION['membername']))
		{
			$user = GetMember($_SESSION['membername']);
			
			$layout->set('userlink', $CONF['url'] . 'user/'. $user['name']);
			$layout->set('pic', $CONF['url'] . 'image.php?src='. $user['pic'] .'&w=80&h=80&img=ch');
			$layout->set('disabled', '');
		
		} else {
				
			$layout->set('pic', $CONF['url'] . 'image.php?src=default.png&w=80&h=80&img=ch');
			$layout->set('disabled', 'disabled');
		
		}
		
		$layout->set('type_id', $type_id);
		$layout->set('type', $type);
		
		return $layout->output();
		
	}	else {
	
		return ViewMessage('Comments disabled by administrator');
	}
}



function view_comments_list($tpl = null, $id, $type) {
	
	global $db, $CONF, $Setting;
	
	
	$user_class = new user();
	$user_class->db = $db;
	
	$output = '';
	
	if ($Setting['comments'] == 1) {
		
		//--------------------------------------------------
		// Set custom form TPL or get default comments list
		//--------------------------------------------------
		if ( isset($tpl)) {
			$layout = $tpl;
		} else {
			$layout = new Template("./assets/_tpl/comments_list.tpl");
		}
		
		$query = get_page_comments($id, $type);
		
		if (is_array($query) && !empty($query)) {	
			
			$layout->set('comments_count', count($query));
			
			foreach($query as $row)	 {
				
				$user_class->userID = $row['user'];
				$user = $user_class->getUser_ID();
				
				
				
				$layout->set('url', $CONF['url']);
				$layout->set('comment', $row['comment']);
				$layout->set('user_name', $user['name']);
				$layout->set('user_realname', $user['realname']);
				$layout->set('comment_date', ago(strtotime($row['time'])));
				$layout->set('user_img', $CONF['url'].'image.php?src='.$user['pic'].'&w=150&h=150&img=ch');
				$output .= $layout->output();
			}
		
		}	else {
		
			$output = '<p>No comments yet</p>';
		}
		
		return $output;
		
	}	else {
	
		return '';
	}
	
}


function get_comments_count($post = null) {
	
	global $db;
	
	$query = $db->query(sprintf("SELECT count(*) as total FROM `comments` WHERE `type_id` = '{$post}' "));
	
	$row = $query->fetch_assoc();
		
	return  $row['total'];
}
