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

class upload 
{	

	public $db;
	public $userID;
	public $mediaName;
	public $mediaDesc;
	public $mediaCover;
	public $mediaFile;
	public $mediaMainFile;
	public $mediaPrice;
	public $mediaCat;
	public $mediaFrametype;
	public $mediaType;
	public $mediaDuration;
	public $mediaTags;
	public $mediaAlbum;
	public $mediaPublish;
	public $mediaInfo;
	public $mediaURL;
	public $mediaPath;
	
	
	function uploadMediaFree()
	{	
		
		$query = sprintf("INSERT into `media` (`title`, `desc`, `content`, `thumbs`, `frametype`, `cat`, `type`, `duration`, `author`, `tags`, `album`, `allow`, `publish`) 
		VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');",
		$this->mediaName, $this->mediaDesc, $this->mediaFile, $this->mediaCover, $this->mediaFrametype, $this->mediaCat, $this->mediaType, $this->mediaDuration, $this->userID, $this->mediaTags, $this->mediaAlbum, $this->mediaAllow, $this->mediaPublish );
		
		if ($this->db->query($query)) 
		{
			return '1';
		
		} else {
			
			return 'failed';
		
		}
		
	}
	
	function uploadMediaPremium()
	{	
		
		$query = sprintf("INSERT into `media` (`title`, `desc`, `content`, `link`, `cost`, `thumbs`, `frametype`, `cat`, `type`, `duration`, `author`, `tags`, `allow`, `paid`, `publish`) 
		VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');",
		$this->mediaName, $this->mediaDesc, $this->mediaFile, $this->mediaMainFile, $this->mediaPrice, $this->mediaCover, $this->mediaFrametype, $this->mediaCat, $this->mediaType, $this->mediaDuration, 
		$this->userID, $this->mediaTags, $this->mediaAllow, 1, $this->mediaPublish );
		
		if ($this->db->query($query)) 
		{
			return '1';
		
		} else {
			
			return 'failed';
		
		}
		
	}
	
		
	function youtubeVideoInfo()
	{
		global $CONF;
		
		$upload = new upload;
		$upload->db = $this->db;
		$upload->mediaURL = $this->mediaInfo['thumbnails']['hd'];
		$upload->mediaPath = 'videos/thumbs';
		$upload->mediaName = $this->mediaInfo['title'];
		$upload->mediaDesc = $this->mediaInfo['description']; 
		$upload->mediaFile = $this->mediaInfo['id'];  
		$upload->mediaFrametype = 'youtube'; 
		$upload->mediaCat = $this->mediaCat;
		$upload->mediaType = 2;
		$upload->userID = $this->userID; 
		$upload->mediaTags = $this->mediaInfo['keywords'];
		$upload->mediaAllow = 1;
		$upload->mediaPublish = 1;
		$upload->mediaPath = 'videos/thumbs';
		$upload->mediaCover = $upload->downloadFileToServer();
		
		echo $upload->uploadMediaFree();
		
	}


	
	function downloadFileToServer()
	{
		global $CONF;
		
		$output = '';
		
		$filename = mt_rand().'_'.mt_rand().'_'.mt_rand() . '_' .basename($this->mediaURL);
		$newfname = $_SERVER['DOCUMENT_ROOT'] . $CONF['path'] . '/media/'.$this->mediaPath.'/'.$filename;
		$file = fopen ($this->mediaURL, 'rb');
		if ($file) {
			$newf = fopen ($newfname, 'wb');
			if ($newf) {
				while(!feof($file)) {
					fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
				}
			}
		}
		if ($file) {
			fclose($file);
		}
		if ($newf) {
			fclose($newf);
			$output = $filename;
		}
		
		return $output;
		
	}

}



















