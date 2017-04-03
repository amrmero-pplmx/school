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

class media 
{	

	public $db;
	public $userID;
	public $userName;
	public $mediaID;
	public $mediaName;
	public $mediaDesc;
	public $mediaCover;
	public $mediaFile;
	public $mediaMainFile;
	public $mediaPrice;
	public $mediaCat;
	public $mediaCatType;
	public $mediaCatPrefix;
	public $mediaFrametype;
	public $mediaType;
	public $mediaTags;
	public $mediaPublish;
	public $mediaNum;
	public $mediaOrder;
	public $mediaData;
	public $albumID;
	public $albumPrefix;
	public $playlistID;
	public $playlistPrefix;
	public $playlistNum;
	public $playlistOrder;
	public $updateSet;
	public $updateType;
	public $getType;
	
	
	
	
	//***********************
	// Queries functions
	//***********************
	function getMedia()
	{	
		
		$query = sprintf("SELECT * FROM `media` WHERE `id` = '%s' ", $this->db->real_escape_string($this->mediaID));
		
		$result = $this->db->query($query);
		
		$row = $result->fetch_array();
			
		if($row !== null)	
		{
			$return = $row;
		
		} else {
			
			$return = '2';
		
		}
		
		return $return;
	}
	
	function updateMediaLike()
	{	
		
		if ($this->updateSet == 'like') { $likeMedia = ' `likes` + 1 ';} elseif ($this->updateSet == 'unlike') {$likeMedia = ' `likes` - 1 ';} else {$likeMedia = ' ';} 
		
		$query = $this->db->query(sprintf("UPDATE %s SET `likes` = %s WHERE `id` = '%s' ", $this->db->real_escape_string($this->updateType), $likeMedia, $this->db->real_escape_string($this->mediaID)));
		
		if($query)	
		{
			$return = '1';
		
		} else {
			
			$return = '0';
		
		}
		
		return $return;
	}
	
	function getMediaAuthor()
	{	
		
		$query = sprintf("SELECT * FROM `members` WHERE `id` = '%s' ", $this->db->real_escape_string($this->mediaID));
		
		$result = $this->db->query($query);
		
		$row = $result->fetch_array();
			
		if($row !== null)	
		{
			return $row;
		
		} else {
			
			return '2';
		
		}
		
	}
		
	function getUserMedia()
	{	
		
		$query = sprintf("SELECT * FROM `media` WHERE `author` = '%s' AND `type` = '%s' ORDER BY `id` DESC LIMIT 12", $this->db->real_escape_string($this->userID), $this->db->real_escape_string($this->mediaType));
			
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
	
	function getMoreMedia()
	{	
		
		$id = $this->db->real_escape_string($this->mediaID);
		$type = $this->db->real_escape_string($this->mediaType);
		
		$media = queryTbl('media', $id);
		$views = $media['views'];
			
		if ($this->mediaOrder == 'user-media') { 
			$get_media_for = " `publish` = '1' AND `id` < '$id' AND `author` = '$this->userID' ORDER BY `id` DESC "; 
		} elseif ( $this->mediaOrder == 'media_cat') { 
			$get_media_for = " `publish` = '1' AND `id` < '$id' AND `cat` = '$this->mediaCat' ORDER BY `id` DESC "; 
		} elseif ( $this->mediaOrder == 'load-media2') {
			$get_media_for = " `publish` = '1' AND `id` != '$id' AND `views` < '$views' ORDER BY `views` DESC , `likes` DESC , `id` DESC , `comments` DESC "; 
		} elseif ( $this->mediaOrder == 'load-media1') {
			$get_media_for = " `publish` = '1' AND `id` < '$id'  ORDER BY  `id` DESC "; 
		} 
			
		$query = sprintf("SELECT * FROM `media` WHERE  `type` = '%s' AND  %s  LIMIT 12", $type, $get_media_for);
		
		
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
	
	function getMediaList()
	{	
		if ($this->mediaCat == 'all')  { $cat = '';} else {$cat = " `cat` = '$this->mediaCat' AND ";}
		
		if ($this->mediaNum > 0)  { $num = " LIMIT $this->mediaNum "; } else {$num = '';}
		
		if ($this->mediaOrder == '2')  { $order = " Order by `views` DESC , `likes` DESC , `id` DESC , `comments` DESC "; } else {$order = '  Order by `id` DESC ';}

		$query = sprintf("SELECT * FROM `media` WHERE `type` = '%s' AND %s `publish` = '1' %s %s ", $this->db->real_escape_string($this->mediaType), $cat, $order, $num);
		
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
	
	function getRelatedMedia()
	{	
		
		$title = strtok($this->db->real_escape_string($this->mediaName), ' ');
		
		$query = sprintf("SELECT * FROM media WHERE `title` LIKE '%s' AND `type` = '$this->mediaType' AND `id` != '$this->mediaID' LIMIT 12 ", '%'.$title.'%');
		
		$result = $this->db->query($query);
		
		$return = '';
		
		if(isset($result->num_rows) && $result->num_rows > 0)	
		{	
			
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
			
		} else {
			
			$return = '2';
		
		}
		
		return $return;
		
	}
	
	function getRelatedUserMedia()
	{	
		
		$query = sprintf("SELECT * FROM media WHERE `author` = '%s' AND `type` = '$this->mediaType' AND `id` != '$this->mediaID'  LIMIT 12 ", $this->db->real_escape_string($this->userID));
		
		$result = $this->db->query($query);
		
		$return = '';
		
		if(isset($result->num_rows) && $result->num_rows > 0)	
		{	
			
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
			
		} else {
			
			$return = '2';
		
		}
		
		return $return;
		
	}
	
	
	function getAlbumsList()
	{	
		
		$query = sprintf("SELECT * FROM `albums` LIMIT 20 ");
		
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
	
	
	function getAlbum()
	{	
		
		$query = sprintf("SELECT * FROM `albums` WHERE `id` = '%s' ", $this->db->real_escape_string($this->albumID));
		
		$result = $this->db->query($query);
		
		$return = '';
		
		if(isset($result->num_rows))	
		{	
			$return = $result->fetch_assoc(); 
				
		} else {
			
			$return = '2';
		
		}
		
		return $return;
		
	}
	
	
	function getUserAlbum()
	{	
		
		$query = sprintf("SELECT * FROM `albums` WHERE `user` = '%s' ", $this->db->real_escape_string($this->userID));
		
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
	
	function getAlbumPrefix()
	{	
		
		$query = sprintf("SELECT * FROM `albums` WHERE `url` = '%s' ", $this->db->real_escape_string($this->albumPrefix));
		
		$result = $this->db->query($query);
		
		$return = '';
		
		if(isset($result->num_rows))	
		{	
			$return = $result->fetch_assoc(); 
				
		} else {
			
			$return = '2';
		
		}
		
		return $return;
		
	}
	
	function getAlbumItems()
	{	
		
		$query = sprintf("SELECT * FROM `media` WHERE `album` = '%s' ", $this->db->real_escape_string($this->albumID));
		
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
	
	function getMediaPlaylists()
	{	
		
		$user_class = new user();
		$user_class->db = $this->db;
		
		$user_class->userName = $this->db->real_escape_string($_GET['type']);
		$user = $user_class->getUser_ID();
		
		$query = sprintf("SELECT * FROM `playlist` WHERE `user` = '%s' ", $user['id']);
			
		$result = $this->db->query($query);
		
		$row = $result->fetch_array();
			
		if($row !== null)	
		{
			return $row;
		
		} else {
			
			return '2';
		
		}
	
	}
	
	function getUserPlaylists()
	{	
		
		$query = sprintf("SELECT * FROM `playlist` WHERE `user` = '%s' ", $this->db->real_escape_string($this->userID));
			
		$result = $this->db->query($query);
		
		if(isset($result->num_rows) && $result->num_rows > 0)	
		{
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
			
		} else {
			
			$return = '2';
		
		}
		
		return $return;
		
	}
	
	function getPlaylists()
	{	
		
		if (isset($this->playlistNum))  { $num = " LIMIT $this->playlistNum "; } else {$num = ' ';}
		
		if ($this->playlistOrder == '2')  { $order = " Order by `play` DESC , `likes` DESC , `comments` DESC "; } else {$order = '  Order by `id` DESC ';}

		$query = sprintf("SELECT * FROM `playlist` WHERE `public` = '1' %s %s ", $order, $num);
		
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
	
	
	function getMediaPlaylistUser()
	{	
		
		$user_class = new user();
		$user_class->db = $this->db;
		
		$user_class->userName = $this->db->real_escape_string($_GET['type']);
		$user = $user_class->getUser();
		
		$query = sprintf("SELECT * FROM `playlist` WHERE `user` = '%s' AND `url` = '%s' ", $user['id'], $this->db->real_escape_string($this->playlistPrefix));
			
		$result = $this->db->query($query);
		
		$row = $result->fetch_array();
			
		if($row !== null)	
		{
			return $row;
		
		} else {
			
			return '2';
		
		}
	
	}
	
	function getMediaPlaylistPrefix()
	{	
	
		$query = sprintf("SELECT * FROM `playlist` WHERE `url` = '%s' ", $this->db->real_escape_string($this->playlistPrefix));
			
		$result = $this->db->query($query);
		
		$row = $result->fetch_array();
			
		if($row !== null)	
		{
			return $row;
		
		} else {
			
			return '2';
		
		}
	
	}
	
	function getMediaPlaylistItems()
	{	
		
		if (isset($this->playlistNum))  { $num = " LIMIT $this->playlistNum "; } else {$num = ' ';}
		
		$query = sprintf("SELECT `media`.* FROM `media`, `playlist_items` WHERE `media`.`id` = `playlist_items`.`media` AND `playlist` = '%s' %s ", $this->db->real_escape_string($this->playlistID),  $num);
			
		$result = $this->db->query($query);
		
		$return = '';
		
		if(isset($result->num_rows) && $result->num_rows > 0)	
		{	
			while ($row = $result->fetch_assoc()) {
				$return[] = $row;  
			}
			
		} else {
			
			$return = '2';
		
		}
		
		return $return;
	}
	
	
	function getMediaCats()
	{	
		
		$query = sprintf("SELECT * FROM `cats` WHERE `type` = '%s' ", $this->db->real_escape_string($this->mediaCatType));
			
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
	
	function getMediaCat_ID()
	{	
		
		$query = sprintf("SELECT * FROM `cats` WHERE `type` = '1' AND `id` = '%s' ", $this->db->real_escape_string($this->mediaCat));
			
		$result = $this->db->query($query);
		
		$return = '';
		
		if(isset($result->num_rows))	
		{	
			$return = $result->fetch_assoc();
				
		} else {
			
			$return = '2';
		
		}
		
		return $return;
	}
	
	function getMediaCatsID()
	{	
		
		$query = sprintf("SELECT * FROM `cats` WHERE `type` = '1' AND `id` = '%s' ", $this->db->real_escape_string($this->mediaCat));
			
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
	
	function getMediaCatsPrefix()
	{	
		
		$query = sprintf("SELECT * FROM `cats` WHERE `url` = '%s' ", $this->db->real_escape_string($this->mediaCatPrefix));
			
		$result = $this->db->query($query);
		
		$return = '';
		
		if(isset($result->num_rows))	
		{	
			$return = $result->fetch_assoc();  
			
		} else {
			
			$return = '2';
		
		}
		
		return $return;
	}
	
	
	function getMediaCatItems()
	{	
		
		$query = sprintf("SELECT * FROM `media` WHERE `cat` = '%s'  ORDER BY `id` DESC LIMIT 12 ", $this->db->real_escape_string($this->mediaCat));
			
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
	
	
	function getFolMedia()
	{	
		
		$query = sprintf("SELECT `media`.* FROM `media`, `follow` WHERE `follow`.`user` = '%s' AND `follow`.`target` = `media`.`author`
		AND `media`.`type` = '%s'
		ORDER BY `id` DESC LIMIT 12
		", $this->db->real_escape_string($this->userID), $this->db->real_escape_string($this->mediaType));
			
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
	
	function getMoreFolMedia()
	{	
		
		$query = sprintf("SELECT `media`.* FROM `media`, `follow` WHERE `follow`.`user` = '%s' AND `follow`.`target` = `media`.`author`
		AND `media`.`type` = '%s' AND `media`.`id` < %s
		ORDER BY `id` DESC LIMIT 12 
		", $this->db->real_escape_string($this->userID), $this->db->real_escape_string($this->mediaType), $this->db->real_escape_string($this->mediaID));
			
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
	// Update function
	//***********************
	function enable_dis_Media()
	{
		
		$query = $this->db->query(sprintf("SELECT * FROM `media` WHERE `id` = '%s'  ", $this->db->real_escape_string($this->mediaID)));
			
		$row = $query->fetch_array();
		
		if  ($row['publish'] == 1) {$publish = '0';} else {$publish = '1';}
		
		$edit = $this->db->query(sprintf("UPDATE `media` SET `publish` = '%s' WHERE `id` = '%s' ", $publish, $this->db->real_escape_string($this->mediaID)));
		
		if($edit)	{$return = '1';} else {$return = '0';}
		
		return $return;
	
	}
	
	
	function updateMedia()
	{	
		
		$media_data = $this->mediaData;
		$id = $this->db->real_escape_string($media_data['media_id']);
		$title = $this->db->real_escape_string($media_data['media_title']);
		$desc = $this->db->real_escape_string($media_data['media_desc']);
		$tags = $this->db->real_escape_string($media_data['media_tags']);
		$cat = $this->db->real_escape_string($media_data['media_cat']);
		$allow = $this->db->real_escape_string(option_toNumber($media_data['media_allow']));
		$album_id = $this->db->real_escape_string($media_data['media_album']);
		$check_album = queryTblParams('albums', " `id` = '$album_id' AND `user` = '$this->userID' ");
		$album = isset($check_album) ? $album_id : '0';
		
		$query = $this->db->query(sprintf("UPDATE `media` SET `title` = '%s', `desc` = '%s', `tags` = '%s', `cat` = '%s', `allow` = '%s', `album` = '%s' WHERE `id` = '%s' AND `author` = '%s' "
		, $title, $desc, $tags, $cat, $allow, $album, $id, $this->db->real_escape_string($this->userID)));
		
		if($query)	
		{
			$return = '1';
		
		} else {
			
			$return = '0';
		
		}
		
		return $return;
	}
		
	
	
	//***********************
	// Delete functions
	//***********************
	function deleteMedia() {
		
		$query = sprintf("DELETE FROM `media` WHERE `id` = '%s' ",$this->db->real_escape_string($this->mediaID));
		
		$delete = $this->db->query($query);
			
		if ($delete) {
			$this->db->query(sprintf("DELETE FROM `cart` WHERE `type` = 'media' AND `type_id` = '%s' ", $this->db->real_escape_string($this->mediaID)));
			$this->db->query(sprintf("DELETE FROM `comments` WHERE `type` = 'media' AND `type_id` = '%s' ", $this->db->real_escape_string($this->mediaID)));
			$this->db->query(sprintf("DELETE FROM `like` WHERE `type` = 'media' AND `type_id` = '%s' ", $this->db->real_escape_string($this->mediaID)));
			$this->db->query(sprintf("DELETE FROM `notifications` WHERE `item` = 'media' AND `type_id` = '%s' ", $this->db->real_escape_string($this->mediaID)));
			$this->db->query(sprintf("DELETE FROM `playlist_items` WHERE  `media` = '%s' ", $this->db->real_escape_string($this->mediaID)));
			return 1;
		} else {
			return 0;
		}		
	}


	//***********************
	// Likes functions
	//***********************
	function getLikes() {
		
		$query = sprintf("SELECT `user` FROM `like` WHERE `type_id` = '%s' AND `type` = '%s'", $this->db->real_escape_string($this->mediaID), $this->db->real_escape_string($this->getType));

		$result = $this->db->query($query);
		
		$return = null;
		
		if($result->num_rows > 0) {
				
			while ($row = $result->fetch_assoc()) {
				
				$return[] = $row;
			
			}
		}
		
		return $return;
	}
	
	function getShares() {
		
		$query = sprintf("SELECT `user` FROM `share` WHERE `type_id` = '%s' AND `type` = 'media'", $this->db->real_escape_string($this->mediaID));

		$result = $this->db->query($query);
		
		$return = '';
		
		if($result->num_rows > 0) {
				
			while ($row = $result->fetch_assoc()) {
				
				$return[] = $row;
			
			}
		}
		
		return $return;
	}
	
	//***********************
	// Counts functions
	//***********************
	function countMediaLikes()
	{	
	
		$count = $this->db->query(sprintf("SELECT `id` FROM `like` WHERE `media` = '%s'", $this->db->real_escape_string($this->mediaID)));
			
		if (isset($count->num_rows) && $count->num_rows > 0) {
				
			$count_num = $count->num_rows;
			
		} else {
				
			$count_num = '0';
				
		}
	
		return $count_num;
	
	}
	
	function countMediaComments()
	{	
	
		$count = $this->db->query(sprintf("SELECT `id` FROM `comments` WHERE `type` = '%s' AND `type` = 'media' ", $this->db->real_escape_string($this->mediaID)));
			
		$count_num = null;
		
		if ($count->num_rows > 0) {
				
			$count_num = $count->num_rows;
			
		} 
	
		return $count_num;
	
	}
	
	function countMediaPlaylist()
	{	
	
		$count = $this->db->query(sprintf("SELECT `id` FROM `playlist` WHERE `user` = 'media' ", $this->db->real_escape_string($this->mediaID)));
			
		return $count->num_rows;
	}
	
	function countUserMedia()
	{	
	
		$count = $this->db->query(sprintf("SELECT `id` FROM `media` WHERE `author` = '%s' ", $this->db->real_escape_string($this->userID)));
			
		return $count->num_rows;
	}
	
	
}


function getMedia($id)
{	
	
	global $db;
		
	$query = sprintf("SELECT * FROM `media` WHERE `id` = '%s' ", $db->real_escape_string($id));
	
	$result = $db->query($query);
		
	$row = $result->fetch_array();
			
	if($row !== null)	
	{
		return $row;
		
	} else {
			
		return '2';
		
	}
		
}



















