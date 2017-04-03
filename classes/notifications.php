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

class notifications 
{	

	public $db;
	public $userID;
	public $senderID;
	public $itemID;
	public $itemType;
	public $itemName;
	public $message;
	public $itemSeen;
	public $itemLast;
	
	
	
	
	//***********************
	// Notifications functions
	//***********************
	function counts()
	{	
		
		$query = sprintf("SELECT * FROM `notifications` WHERE `user` = '%s' AND `seen` = '0' ", $this->userID);
		
		$result = $this->db->query($query);
		
		if(isset($result->num_rows))	
		{
			$return = $result->num_rows;
		
		} else { $return = '0'; }
		
		return $return;
	}
	
	
	function check_latest()
	{	
		
		$query = sprintf("SELECT * FROM `notifications` WHERE `user` = '%s' ORDER BY id DESC LIMIT 10 ", $this->userID);
		
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
	
	
	function check()
	{	
		
		if (!empty($this->itemLast)) {$param = " AND `id` > '$this->db->real_escape_string($this->itemLast)' ";} else { $param = ' '; }
		
		$query = sprintf("SELECT * FROM `notifications` WHERE `user` = '%s' AND `seen` = '0' %s ORDER BY id DESC ", $this->userID, $param);
		
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
	
	function check_old()
	{	
		
		$query = sprintf("SELECT * FROM `notifications` WHERE `user` = '%s' AND `sender` = '%s' AND `type` = '%s' AND `type_id` = '%s' AND `item` = '%s' ",
		$this->db->real_escape_string($this->userID), $this->db->real_escape_string($this->senderID), $this->db->real_escape_string($this->itemType), $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->itemName));        					
		
		$result = $this->db->query($query);
		
		if(isset($result->num_rows))	
		{
			$return = $result->num_rows;
		
		} else { $return = '0'; }
		
		return $return;
	}
	
	function add()
	{	
		$check = $this->check_old();
		
		if (empty($check)) {
			
			$query = $this->db->query(sprintf("INSERT INTO `notifications` (`type`,`type_id`,`item`,`user`,`sender`) VALUES ('%s','%s','%s','%s','%s') ",
			$this->db->real_escape_string($this->itemType), $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->itemName), $this->db->real_escape_string($this->userID), $this->db->real_escape_string($this->senderID)));
			
			if($query){ $return = 1;} else {$return = '0';}
			
			return $return;
		}
	}
	
	function seen()
	{	
		
		$query = $this->db->query(sprintf("UPDATE `notifications` SET `seen` = 1 WHERE `user` = '%s' ", $this->db->real_escape_string($this->userID)));
			
		if($query){ $return = 1;} else {$return = '0';}
			
		return $return;
		
	}
	
	function display()
	{	
		global $CONF;
			
			$query = queryTbl($this->itemName, $this->itemID);
			$sender = $this->senderID;
			$url = $this->itemID;
			$time = '';
		
		$user_url = '<a data-ajax="true" href="'.$CONF['url'].'user/' . $this->senderID['name'] . '"> ' . $this->senderID['realname'] . '</a>';
		
		$note_name = $this->itemName;
		
		if ($this->itemSeen == '0') {
			$seen = ' btn-dark ';
		} else {$seen = '';}
		
		if ($this->itemName == 'media') {
			$path = $query['id'];
		} elseif ($this->itemName == 'playlist') {
			$path = $this->userID['name'] . '/' . $query['url'];
		} elseif ($this->itemName == 'albums') {
			$note_name = 'album';
			$path = $this->userID['name'] . '/' . $query['url'];
		} else {
			$path = '';
		}
			
		if ($this->itemType == 1) {
			
			$url = $CONF['url'] . $this->itemName . '/' . $path;
			$text = $user_url . ' liked your ' . $note_name . ' <a data-ajax="true" href="'.$CONF['url']. $this->itemName . '/' . $path . '">' . $query['title'] . '</a>' ;
			
		} elseif ($this->itemType == 2) {
			$url = $CONF['url'] . $this->itemName . '/' . $path;
			$text = $user_url . ' commented on your ' . $note_name . '  <a data-ajax="true" href="'.$CONF['url']. $this->itemName . '/' . $path . '">' . $query['title'] . '</a>' ;
			
		} elseif ($this->itemType == 3) {
			$url = $CONF['url'] . $this->itemName . '/' . $path;
			$text = $user_url . ' is following you now ' ;
		}
		
		$return = '
                  <div class="media list-group-item '.$seen.'">
                    <span class="pull-left thumb-sm">
                      <img src="'.$CONF['url'].'image.php?src='.$sender['pic'].'&w=100&h=100&img=ch" alt="..." class="img-circle">
                    </span>
                    <span class="media-body block m-b-none ">
                      '.$text.'<br>
                      <small class="text-muted">'.$time.'</small>
                    </span>
                  </div>';
				  
		return $return;
	}
	
}



















