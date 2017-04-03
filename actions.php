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

class action 
{	

	public $db;
	public $userID;
	public $userName;
	public $itemID;
	public $itemType;
	public $itemName;
	public $itemCover;
	
	
	
	
	//***********************
	// Likes functions
	//***********************
	function checkLike()
	{	
		
		$query = sprintf("SELECT * FROM `like` WHERE `type` = '%s' AND `type_id` = '%s' AND `user` = '%s' ", $this->db->real_escape_string($this->itemType), $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID));
		
		$result = $this->db->query($query);
		
		if(isset($result->num_rows))	
		{
			$return = $result->num_rows;
		
		} else {
			
			$return = '0';
		
		}
		
		return $return;
	}
	
	function itemLike()
	{	
	
		$check = $this->db->query(sprintf("SELECT * FROM %s WHERE `id` = '%s' ", $this->db->real_escape_string($this->itemType), $this->db->real_escape_string($this->itemID)));
		$check_result = $check->fetch_assoc();
		if ($this->db->real_escape_string($this->itemType) == 'media') {$user = $check_result['author'];} else {$user = $check_result['user'];}
		
		$query = $this->db->query(sprintf("INSERT INTO `like` (`type`,`type_id`,`user`) VALUES ('%s','%s','%s') ", $this->db->real_escape_string($this->itemType), $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID)));
		
		if($query)	
		{
			
			add_notification($user, '1', $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->itemType));
				
			$return = 1;
		
		} else {
			
			$return = '0';
		
		}
		
		return $return;
	}
	
	
	function itemUnLike()
	{	
		
		$query = $this->db->query(sprintf("DELETE FROM `like` WHERE `type` = '%s' AND `type_id` = '%s' AND `user`  = '%s' ", $this->db->real_escape_string($this->itemType), $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID)));
		
		if($query){ $return = 1;} else {$return = '0';}
		
		return $return;
	}
	
	function itemDelete()
	{	
		$query = $this->db->query(sprintf("SELECT * FROM %s WHERE `id` = '%s' AND `user`  = '%s' ", $this->db->real_escape_string($this->itemType), $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID)));
		
		$result = $query->fetch_assoc();
			
		if(isset($result['id'])) { 
			
			$query = $this->db->query(sprintf("DELETE FROM %s WHERE `id` = '%s' ", $this->db->real_escape_string($this->itemType), $result['id']));
			
			$set = updateTblVal($result['type'], $result['type_id'], $this->db->real_escape_string($this->itemType) . ' = ' . $this->db->real_escape_string($this->itemType) . '  - 1 ');
			$id = $result['id'];	
			deleteTblParams('notifications', " `item` = '$this->itemType' AND `type_id` = '$id' ");
			deleteTblParams('comments', " `type` = '$this->itemType' AND `type_id` = '$id' ");
			
			if ($set) {$return = 1;} else { $return = '0';}
			
		} else {$return = '0';}
		
		return $return;
	}
	
	
	//***********************
	// Follow functions
	//***********************
	function checkFollow()
	{	
		
		$query = sprintf("SELECT * FROM `follow` WHERE `target` = '%s' AND `user` = '%s' ", $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID));
		
		$result = $this->db->query($query);
		
		if(isset($result->num_rows))	
		{
			$return = $result->num_rows;
		
		} else {
			
			$return = '0';
		
		}
		
		return $return;
	}
	
	function userFollow()
	{	
		$query = $this->db->query(sprintf("SELECT * FROM `members` WHERE `id` = '%s'", $this->db->real_escape_string($this->itemID)));
		
		$result = $query->fetch_assoc();
			
		if(isset($result['id'])) { 
				
			$query = $this->db->query(sprintf("INSERT INTO `follow` (`target`,`user`) VALUES ('%s', '%s') ", $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID)));
			
			if($query) { 
			
				add_notification($this->db->real_escape_string($this->itemID), '3', '0', 'follow');
								
				$return = '1';
				
			} else {$return = '0';}
			
		} else {$return = 'User not found';}
		
		return $return;
	}
	
	
	function userUnFollow()
	{	
		$query = $this->db->query(sprintf("SELECT * FROM `follow` WHERE `target` = '%s' AND `user` = '%s'", $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID)));
		
		$result = $query->fetch_assoc();
			
		if(isset($result['id'])) { 
				
			$query = $this->db->query(sprintf("DELETE FROM `follow` WHERE `target` = '%s' AND  `user` = '%s'  ", $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID)));
			
			if($query) { 
				
				$return = '1';
				
			} else {$return = '0';}
			
		} else {$return = 'User not found';}
		
		return $return;
	}
	
	
	//***********************
	// Cart functions
	//***********************
	function checkPaid()
	{	
		$query = $this->db->query(sprintf("SELECT * FROM `cart` WHERE `type` = '%s' AND `type_id` = '%s' AND `state` = '1' AND `user` = '%s'", $this->db->real_escape_string($this->itemType), $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID)));
		
		$result = $query->fetch_assoc();
			
		if(isset($result['id'])) { 
				
			$return = '1';
			
		} else {
			
			$return = '0';
		
		}
		return $return;
	}
	
	function itemAddToCart()
	{	
		$query = $this->db->query(sprintf("SELECT * FROM `cart` WHERE `type` = '%s' AND `type_id` = '%s' AND `user` = '%s'", $this->db->real_escape_string($this->itemType), $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID)));
		
		$result = $query->fetch_assoc();
			
		if(isset($result['id'])) { 
				
			$return = 'You have this item at your cart';
			
		} else {
		
			$query = $this->db->query(sprintf("INSERT INTO `cart` (`type`,`type_id`,`user`) VALUES ('%s', '%s', '%s') ", $this->db->real_escape_string($this->itemType), $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID)));
			
			if($query) { 
				
				$return = '1';
				
			} else {$return = '0';}
		}
		
		return $return;
	}
	
	function itemRemoveFromCart()
	{	
		
		$query = $this->db->query(sprintf("DELETE FROM `cart` WHERE `type` = '%s' AND  `type_id` = '%s' AND `user` = '%s' ", $this->db->real_escape_string($this->itemType), $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID)));
			
		if($query) {  return '1'; } else {return  '0';}
		
	}
	
	function addCredit()
	{	
		$query = $this->db->query(sprintf("INSERT INTO `payment` (`value`,`method`,`user`) VALUES ('%s', '%s', '%s') ", $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->itemType), $this->db->real_escape_string($this->userID)));
			
		if($query) { 
				
			$return = '1';
				
		} else {$return = '0';}
			
		return $return;
	}
	
	
	//***********************
	// Playlist functions
	//***********************
	function userAddToPlaylist()
	{	
		$query = $this->db->query(sprintf("SELECT * FROM `playlist` WHERE `user` = '%s'", $this->db->real_escape_string($this->userID)));
		
		$result = $query->fetch_assoc();
			
		if(isset($result['id'])) { 
				
			$query = $this->db->query(sprintf("INSERT INTO `playlist_items` (`playlist`,`user`,`media`) VALUES ('%s', '%s', '%s') ", $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID), $this->db->real_escape_string($this->itemType)));
			
			if($query) { 
				
				$return = '1';
				
			} else {$return = '0';}
			
		} else {$return = 'User not found';}
		
		return $return;
	}
	
	function userAddPlaylist()
	{	
		$url = $this->db->real_escape_string($this->itemType);
		
		$query = $this->db->query(sprintf("SELECT * FROM `playlist` WHERE `url` = '%s' ", $this->db->real_escape_string($this->itemType)));
		
		$result = $query->fetch_assoc();
		
		$return = '';
			
		if(isset($result['id'])) { 
				
			$url = $this->db->real_escape_string($this->itemType) . '_' . rand(10, 99);
			
			$query = $this->db->query(sprintf("INSERT INTO `playlist` (`title`,`user`,`url`, `public`) VALUES ('%s', '%s', '%s', 1) ", $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID), $url));
			
			if($query) { 
				
				$return = '1';
				
			} else {$return = '0';}
			
		} else {
			$query = $this->db->query(sprintf("INSERT INTO `playlist` (`title`,`user`,`url`, `public`) VALUES ('%s', '%s', '%s', 1) ", $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID), $url));
			
			if($query) { 
				
				$return = '1';
				
			} else {$return = '0';}
		}
		
		return $return;
	}
	
	
	function userAddAlbum()
	{	
		$url = $this->db->real_escape_string($this->itemType);
		
		$query = $this->db->query(sprintf("SELECT * FROM `albums` WHERE `url` = '%s' ", $this->db->real_escape_string($this->itemType)));
		
		$result = $query->fetch_assoc();
		
		$return = '';
			
		if(isset($result['id'])) { 
				
			$url = $this->db->real_escape_string($this->itemType) . '_' . rand(10, 99);
			
			$query = $this->db->query(sprintf("INSERT INTO `albums` (`title`,`user`,`url`, `cover`) VALUES ('%s', '%s', '%s', '%s') ", $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID), $url, $this->itemCover));
			
			if($query) { 
				
				$return = '1';
				
			} else {$return = '0';}
			
		} else {
			$query = $this->db->query(sprintf("INSERT INTO `albums` (`title`,`user`,`url`, `cover`) VALUES ('%s', '%s', '%s', '%s') ", $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID), $url, $this->itemCover));
			
			if($query) { 
				
				$return = '1';
				
			} else {$return = '0';}
		}
		
		return $return;
	}
	
	
	
	function userDelFromPlaylist()
	{	
		
		$query = $this->db->query(sprintf("DELETE FROM `playlist_items` WHERE  `playlist` = '%s' AND `user` = '%s' ", $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID)));
			
		if($query) { 
			
			$return = '1';
				
		} else {$return = '0';}
		
		return $return;
	}
	
	
	
	function userDeletePlaylist()
	{	
		
		$query = $this->db->query(sprintf("DELETE FROM `playlist` WHERE `id` = '%s' AND `user` = '%s' ", $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID)));
			
		if($query) { 
			
			$this->db->query(sprintf("DELETE FROM `playlist_items` WHERE  `playlist` = '%s' AND `user` = '%s' ", $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID)));
			
			deleteTblParams('notifications', " `item` = 'playlist' AND `type_id` = '$this->itemID' ");
			deleteTblParams('comments', " `type` = 'playlist' AND `type_id` = '$this->itemID' ");
			
			$return = '1';
				
		} else {$return = '0';}
		
		return $return;
	}
	
	
	
	function userDeleteAlbum()
	{	
		
		$query = $this->db->query(sprintf("DELETE FROM `Albums` WHERE `id` = '%s' AND `user` = '%s' ", $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID)));
			
		if($query) { 
			
			deleteTblParams('notifications', " `item` = 'albums' AND `type_id` = '$this->itemID' ");
			deleteTblParams('comments', " `type` = 'albums' AND `type_id` = '$this->itemID' ");
			
			$return = '1';
				
		} else {$return = '0';}
		
		return $return;
	}
	
	
	
	
	function getUserMedia()
	{	
		
		$query = sprintf("SELECT * FROM `media` WHERE `author` = '%s' ", $this->db->real_escape_string($this->userID));
			
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
	
	
	//***********************
	// Download functions
	//***********************
	function downloadMedia()
	{	
		
		$query = sprintf("SELECT * FROM `media` WHERE `author` = '%s', `id` =  ", $this->db->real_escape_string($this->userID));
			
		$download = downloadFile($user, $media, $type);
		
		if ($download !== '0')  {
						
			
		} else {
			header("Location: ". $CONF['url']);	
		}
		
		
	}
	
	
}



















