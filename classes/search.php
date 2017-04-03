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

class search 
{	

	public $db;
	public $searchTitle;
	public $searchType;
	
	
	function searchMusic()
	{	
		$query = sprintf("SELECT * FROM media WHERE `title` LIKE '%s' AND `type` = '1' AND `publish` = '1' LIMIT 10", '%'.$this->db->real_escape_string($this->searchTitle).'%');
		
		$result = $this->db->query($query); $return = '';
		
		if(isset($result->num_rows) && $result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) { $return[] = $row; }
			
		} else {
			$return = null;
		}
		
		return $return;
		
	}
	
	function searchVideo()
	{	
		$query = sprintf("SELECT * FROM media WHERE `title` LIKE '%s' AND `type` = '2' AND `publish` = '1' LIMIT 10", '%'.$this->db->real_escape_string($this->searchTitle).'%');
		
		$result = $this->db->query($query); $return = '';
		
		if(isset($result->num_rows) && $result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) { $return[] = $row; }
			
		} else {
			$return = null;
		}
		
		return $return;
		
	}
	
	function searchPhoto()
	{	
		$query = sprintf("SELECT * FROM media WHERE `title` LIKE '%s' AND `type` = '3' AND `publish` = '1' LIMIT 10", '%'.$this->db->real_escape_string($this->searchTitle).'%');
		
		$result = $this->db->query($query); $return = '';
		
		if(isset($result->num_rows) && $result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) { $return[] = $row; }
			
		} else {
			$return = null;
		}
		
		return $return;
		
	}
	
	function searchPlaylist()
	{	
		
		$query = sprintf("SELECT * FROM `playlist` WHERE `title` LIKE '%s' AND `public` = '1'  ", '%'.$this->db->real_escape_string($this->searchTitle).'%');
			
		$result = $this->db->query($query); $return = '';
		
		if(isset($result->num_rows) && $result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) { $return[] = $row; }
			
		} else {
			$return = null;
		}
		
		return $return;
		
	}
	
	function searchAlbum()
	{	
		
		$query = sprintf("SELECT * FROM `albums` WHERE `title` LIKE '%s' ", '%'.$this->db->real_escape_string($this->searchTitle).'%');
			
		$result = $this->db->query($query); $return = '';
		
		if(isset($result->num_rows) && $result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) { $return[] = $row; }
			
		} else {
			$return = null;
		}
		
		return $return;
		
	}
	
	function searchUser()
	{	
		
		$query = sprintf("SELECT * FROM `members` WHERE `name` LIKE '%s' OR `realname` LIKE '%s' ", '%'.$this->db->real_escape_string($this->searchTitle).'%', '%'.$this->db->real_escape_string($this->searchTitle).'%');
			
		$result = $this->db->query($query); $return = '';
		
		if(isset($result->num_rows) && $result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) { $return[] = $row; }
			
		} else {
			$return = null;
		}
		
		return $return;
		
	}
	
	function searchPost()
	{	
		
		$query = sprintf("SELECT * FROM `posts` WHERE `title` LIKE '%s' OR `content` LIKE '%s' ", '%'.$this->db->real_escape_string($this->searchTitle).'%', '%'.$this->db->real_escape_string($this->searchTitle).'%');
			
		$result = $this->db->query($query); $return = '';
		
		if(isset($result->num_rows) && $result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) { $return[] = $row; }
			
		} else {
			$return = null;
		}
		
		return $return;
		
	}
	
	
	
}












