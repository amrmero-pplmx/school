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

class post
{	

	public $db;
	public $postID;
	public $postsNum;
	public $postCat;
	public $catPrefix;
	public $userID;
	public $userName;
	public $userNum;
	
	function getPosts()
	{	
		
		$post_num = $this->db->real_escape_string($this->postsNum);
		
		if (!empty($this->postsNum)) {$num = " LIMIT $post_num ";} else {$num = '';}
		
		$query = sprintf("SELECT * FROM `posts` WHERE `publish` = '1' ORDER BY `id` DESC %s ", $num);
		
		$result = $this->db->query($query);
		
		$return = '';
		
		if($result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
			
		} else {
			
			$return = '2';
		
		}
		
		return $return;
		
	}

	
	function getMorePosts()
	{	
		if (!empty($this->postsNum)) {$num = " LIMIT $this->postsNum ";} else {$num = '';}
		if (!empty($this->postID)) {$id = " AND `id` < $this->postID ";} else {$id = '';}
		if (!empty($this->postCat)) {$cat = " AND `cat` = $this->postCat ";} else {$cat = '';}
		
		$query = sprintf("SELECT * FROM `posts` WHERE `publish` = '1' %s %s ORDER BY `id` DESC %s ", $this->db->real_escape_string($cat), $this->db->real_escape_string($id), $this->db->real_escape_string($num));
		
		$result = $this->db->query($query);
		
		$return = '';
		
		if($result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
			
		} else {
			
			$return = '2';
		
		}
		
		return $return;
		
	}

	function getPost()
	{	
		
		$query = sprintf("SELECT * FROM `posts` WHERE `id` = '%s' ", $this->db->real_escape_string($this->postID));
		
		$result = $this->db->query($query);
		
		$row = $result->fetch_array();
			
		if($row !== null)	
		{
			return $row;
		
		} else {
			
			return '2';
		
		}
		
	}
	
	function getUserPosts()
	{	
		
		if (!empty($this->postsNum)) {$num = " LIMIT $this->postsNum ";} else {$num = '';}
		
		$query = sprintf("SELECT * FROM `posts` WHERE `author` = '%s' `publish` = '1' ORDER BY `id` DESC %s ", $this->db->real_escape_string($this->userID), $this->db->real_escape_string($num));
		
		$result = $this->db->query($query);
		
		$return = '';
		
		if($result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
			
		} else {
			
			$return = '2';
		
		}
		
		return $return;
	}
	
	function getLatestUserPosts()
	{	
		if (!empty($this->postsNum)) {$num = " LIMIT $this->postsNum ";} else {$num = '';}
		
		$query = sprintf("SELECT * FROM `posts` WHERE `publish` = '1' AND `author` = '%s' ORDER BY `id` DESC %s ", $this->db->real_escape_string($this->userID), $this->db->real_escape_string($num));
		
		$result = $this->db->query($query);
		
		$return = '';
		
		if($result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
			
		} else {
			
			$return = '2';
		
		}
		
		return $return;
		
	}
	
	function getPostsCategories()
	{	
		
		$query = sprintf("SELECT * FROM `cats` WHERE `publish` = '1' AND `type` = '0' ");
		
		$result = $this->db->query($query);
		
		$return = '';
		
		if($result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
			
		} else {
			
			$return = '2';
		
		}
		
		return $return;
		
	}

	function getCatPrefix()
	{	
		
		$query = sprintf("SELECT * FROM `cats` WHERE `url` = '%s' ", $this->db->real_escape_string($this->catPrefix));
		
		$result = $this->db->query($query);
		
		$row = $result->fetch_array();
			
		if($row !== null)	
		{
			return $row;
		
		} else {
			
			return '2';
		
		}
		
	}

	function getCatPosts()
	{	
		
		$query = sprintf("SELECT * FROM `posts` WHERE `publish` = '1' AND `cat` = '%s' ORDER BY `id` DESC  LIMIT 2 ", $this->db->real_escape_string($this->postCat));
		
		$result = $this->db->query($query);
		
		$return = '';
		
		if($result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
			
		} else {
			
			$return = '2';
		
		}
		
		return $return;
		
	}

	function countCategoryPosts()
	{	
	
		$count = $this->db->query(sprintf("SELECT `id` FROM `posts` WHERE `publish` = '1' AND `cat` = '%s' ", $this->db->real_escape_string($this->postCat)));
			
		if ($count->num_rows > 0) {
				
			$count_num = $count->num_rows;
			
		} else {
				
			$count_num = '0';
				
		}
	
		return $count_num;
	
	}

	
}












