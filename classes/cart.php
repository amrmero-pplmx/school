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

class cart 
{	

	public $db;
	public $userID;
	public $userName;
	public $itemID;
	public $itemType;
	public $itemName;
	
	
	
	
	//***********************
	// Likes functions
	//***********************
	function checkPaid()
	{	
		
		$query = sprintf("SELECT * FROM `cart` WHERE `type` = '%s' AND `type_id` = '%s' AND `user` = '%s' AND `state` = 1 ", $this->db->real_escape_string($this->itemType), $this->db->real_escape_string($this->itemID), $this->db->real_escape_string($this->userID));
		
		$result = $this->db->query($query);
		
		if(isset($result->num_rows))	
		{
			$return = $result->num_rows;
		
		} else {
			
			$return = '0';
		
		}
		
		return $return;
	}
	
	function checkCart()
	{	
		
		$query = sprintf("SELECT * FROM `cart` WHERE `user` = '%s' AND `state` = 0 ", $this->db->real_escape_string($this->userID));
			
		$result = $this->db->query($query); $return = '';
		
		if(isset($result->num_rows))	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
			
		} else {$return = '2';}
		
		return $return;
		
	}
	
	function checkDownloads()
	{	
		
		$query = sprintf("SELECT * FROM `cart` WHERE `user` = '%s' AND `state` = 1 ", $this->db->real_escape_string($this->userID));
			
		$result = $this->db->query($query); $return = '';
		
		if(isset($result->num_rows))	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
			
		} else {$return = '2';}
		
		return $return;
		
	}
	
}



















