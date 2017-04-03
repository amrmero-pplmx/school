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

class chat 
{	

	public $db;
	public $userID;
	public $senderID;
	public $message;
	public $messageID;
	public $messageType;
	public $messageState;
	
	
	
	
	//***********************
	// Likes functions
	//***********************
	function counts()
	{	
		
		$query = sprintf("SELECT * FROM `chat` WHERE `user` = '%s' AND `seen` = '0' ", $this->userID);
		
		$result = $this->db->query($query);
		
		if(isset($result->num_rows))	
		{
			$return = $result->num_rows;
		
		} else { $return = '0'; }
		
		return $return;
	}
	
	function check_block()
	{	
		
		$query = sprintf("SELECT * FROM `block` WHERE `blocker` = '%s' AND `blocked` = '%s' ", $this->userID, $this->senderID);
		
		$result = $this->db->query($query);
		
		if(isset($result->num_rows))	
		{
			$return = $result->num_rows;
		
		} else { $return = ''; }
		
		return $return;
	}
	
	function check()
	{	
		
		$query = sprintf("SELECT * FROM `chat` WHERE `user` = '%s' AND `seen` = '0' ", $this->userID);
		
		$result = $this->db->query($query);
		
		$return = '';
		
		if(isset($result->num_rows))	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
		
		} else { $return = '0'; }
		
		return $return;
	}
	
	function lastMessage()
	{	
		
		$query = sprintf("SELECT * FROM `chat` WHERE `user` = '%s' AND `sender` = '%s' OR `sender` = '%s' AND `user` = '%s'  ORDER BY `date` DESC LIMIT 1 "         					
		, $this->userID,  $this->senderID, $this->userID, $this->senderID);
		
		$result = $this->db->query($query);
		
		if(isset($result->num_rows) && $result->num_rows > 0)	
		{	
			$return = $result->fetch_assoc();
		
		} else { $return = '0'; }
		
		return $return;
	}
	
	
	function lastSentMessage()
	{	
		
		$query = sprintf("SELECT * FROM `chat` WHERE `user` = '%s' AND `sender` = '%s'  ORDER BY `date` DESC LIMIT 1 ", $this->userID, $this->senderID);        					
		
		$result = $this->db->query($query);
		
		if(isset($result->num_rows) && $result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
		
		} else { $return = '0'; }
		
		return $return;
	}
	
	
	function check_old_chat()
	{	
		
		$query = sprintf("SELECT * FROM `chat` WHERE `user` = '%s' AND `sender` = '%s'  OR `sender` = '%s' AND `user` = '%s' ORDER BY `id` ASC ", $this->userID, $this->senderID, $this->userID, $this->senderID);        					
		
		$result = $this->db->query($query);
		
		if(isset($result->num_rows) && $result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
		
		} else { $return = '0'; }
		
		return $return;
	}
	
	
	function check_old_users()
	{	
		
		$query = sprintf("SELECT `members`.* FROM `chat`,`members` WHERE `chat`.`user` = '%s' AND `chat`.`user` = `members`.`id` AND `members`.`id` != '$this->userID'
		OR `chat`.`sender` = '%s' AND `chat`.`user` = `members`.`id`  AND `members`.`id` != '$this->userID' group by `members`.`id` ORDER BY `chat`.`id` DESC LIMIT 10",
		$this->userID, $this->userID);        					
		
		$result = $this->db->query($query);
		
		$return = '';
		
		if(isset($result->num_rows))	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
		
		} else { $return = '0'; }
		
		return $return;
	}
	
	function check_my_messages()
	{	
		
		$query = sprintf("SELECT `members`.* FROM `chat`,`members` WHERE `chat`.`user` = '%s' AND `chat`.`sender` = `members`.`id` AND `members`.`id` != '$this->userID'
		OR `chat`.`sender` = '%s' AND `chat`.`user` = `members`.`id`  AND `members`.`id` != '$this->userID' group by `members`.`id` ORDER BY `chat`.`id` DESC LIMIT 10",
		$this->userID, $this->userID);        					
		
		$result = $this->db->query($query);
		
		$return = '';
		
		if(isset($result->num_rows))	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
		
		} else { $return = '0'; }
		
		return $return;
	}
	
	function add()
	{	
		$check = $this->check_block();
		
		if (empty($check)) {
			
			$query = $this->db->query(sprintf("INSERT INTO `chat` (`message`,`user`,`sender`) VALUES ('%s','%s','%s') ", $this->db->real_escape_string($this->message), $this->userID, $this->senderID));
			
			if($query){ $return = 1;} else {$return = '0';}
			
		} else {$return = '0';}
		
		return $return;
	}
	
	function set_message_seen()
	{	
		
		$query = sprintf("UPDATE `chat` SET `seen` = '1', `date` = `date` WHERE `user` = '%s' AND `sender` = '%s' ", $this->userID, $this->senderID);        					
		
		$result = $this->db->query($query);
		
		if($result)	
		{	
			$return = '1';
		
		} else { $return = '0'; }
		
		return $return;
	}
	
	
	function display_msg()
	{	
		global $CONF;
		
		if ($this->itemSeen == '0') {
			$seen = ' btn-dark ';
		} else {$seen = '';}
		
		$user = $this->userID;
		$text = substr($this->message, 0, 30);
		
		$return = '
				  <a href="'.$CONF['url'].'chat/' . $user['name'] . '" data-title="'.$user['name'].'" data-ajax="true">
                  <div class="media list-group-item '.$seen.'">
                    <span class="pull-left thumb-sm">
                      <img src="'.$CONF['url'].'image.php?src='.$user['pic'].'&w=100&h=100&img=ch" alt="..." class="img-circle">
                    </span>
                    <span class="media-body block m-b-none">
                      '.$user['realname'].'<br>
                      <small class="text-muted">'.$text.'</small>
                    </span>
                  </div></a>';
				  
		return $return;
	
	}
}



















