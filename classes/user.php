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

class user 
{	

	public $db;
	public $dbaser;
	public $userID;
	public $userName;
	public $userNum;
	public $userData;
	
	public $userType;

	
	function convertUsersTypes()
	{
		if ($this->userType == 'students')
			return 1;
		elseif ($this->userType == 'parents') 
			return 2;
		elseif ($this->userType == 'teachers') 
			return 3;
		elseif ($this->userType == 'drivers') 
			return 4;
		else
			return 0;

	}

	function getUser()
	{	
		
		$query = sprintf("SELECT * FROM `members` WHERE `name` = '%s' ", $this->db->real_escape_string($this->userName));
		
		$result = $this->db->query($query);
		
		$row = $result->fetch_array();
			
		if($row !== null)	
		{
			return $row;
		
		} else {
			
			return '2';
		
		}
		
	}

	function getUser_ID()
	{	
		
		$query = sprintf("SELECT * FROM `members` WHERE `id` = '%s' ", $this->db->real_escape_string($this->userID));
		
		$result = $this->db->query($query);
		
		$row = $result->fetch_array();
			
		if($row !== null)	
		{
			return $row;
		
		} else {
			
			return '2';
		
		}
		
	}

	
	function getLatestUsers()
	{	
		
		$query = sprintf("SELECT * FROM `members` WHERE `publish` = '1' ORDER BY `id` DESC LIMIT 20 ");
		
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

	
	function getTopUsers()
	{	
		
		$query = sprintf("SELECT * FROM `members` WHERE `id` != '%s' group by `members`.`id` ORDER BY `id` DESC LIMIT 20 ", $this->db->real_escape_string($this->userID));
		
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
	
	
	
	
	//***********************
	// Edit functions
	//***********************
	function editUser() 
	{
		
		$ok = '';
		
		$data = $this->userData;
		
		$id = $this->db->real_escape_string($data['user_id']);
		$user_realname = $this->db->real_escape_string($data['user_full_name']);
		$user_name = $this->db->real_escape_string($data['user_name']);
		$user_email = $this->db->real_escape_string($data['user_email']);
		$info = $this->db->real_escape_string($data['user_info']);
		$gender = $this->db->real_escape_string($data['user_gender']);
		$facebook = $this->db->real_escape_string($data['user_facebook']);
		$twitter = $this->db->real_escape_string($data['user_twitter']);
		$google = $this->db->real_escape_string($data['user_google']);
		$youtube = $this->db->real_escape_string($data['user_youtube']);
		$instagram = $this->db->real_escape_string($data['user_instagram']);
		
		if ($email == '0') {	
				
			$email = "  ";	 $ok = 1;
			
		} elseif ($user_email !== 0 && filter_var($user_email, FILTER_VALIDATE_EMAIL) == false) {
		
			$error = 'Please choose valid Email address';
			
		}  elseif (empty($user_email) || $this->verify_if_user_exist($id, $user_name, $user_email) == '1') {
			
			$error = 'Username or Email already found';
		
		} elseif (isset($user_email) && $this->verify_if_user_exist($id, $user_name, $user_email) == '2') {
		
			$email = " `email` = '$user_email' , ";
			$name = " `name` = '$user_name' , ";
		
			$ok = 1;
			
		} else {
			
			$error = $this->verify_if_user_exist($id, $user_name, $user_email);
		}
		
		if(!empty($user_realname)) {
		
			$realname = " `realname` = '$user_realname' , ";
		
			$ok = 1;
			
		} else {
		
			$realname = " ";
		
		}
			
		if(isset($data['user_pic']) ) {
			
			$thumb = $data['user_pic'];
			
			$pic = " `pic` = '$thumb' , ";
		
		} else {
			$pic = " ";
		}
		
		if(!empty($data['user_password_edit']) ) {
			
			$pass = $this->db->real_escape_string($data['user_password_edit']);
			
			if($data['user_password_edit'] == $data['user_password_confirm'] ) {
				
				if(strlen($pass) > 6 ) {
				
					$pass = md5($pass);
					
					$password = " `pass` = '$pass' , ";
				
					$ok = 1;
				
				} else {
					$error = 'Password must be at least 7 letters or numbers ';
				}
			
			} else {
				$error = 'Password not matched ';
			}
			
		} else {
		
			$password = " ";
			
			$ok = 1;
			
		}
			
		if(isset($error)) {
			return $error;
		} else {
		
			if($ok == 1) {
				
				$query = sprintf("UPDATE `members` SET %s %s %s %s %s  `gender` = '$gender', `facebook` = '$facebook', `twitter` = '$twitter', `instagram` = '$instagram', `youtube` = '$youtube', `google` = '$google', `info` = '$info'  WHERE id = '%s' ", $name, $realname, $email, $password, $pic, $id);
				
				if($this->db->query($query)) {
			
					return 1;
				
				} else {
					
					return $this->db->error;
				
				}
					
			}
		}
		
	}
	
	
	
	function verify_if_user_exist($userID, $userName, $userEmail) {
		global $db;
		
		$regName = $db->real_escape_string($userName);
		
		$regMail = $db->real_escape_string($userEmail);
		
		$query = sprintf("SELECT `name`, `email` FROM `members` WHERE `name` = '%s' AND `id` != '%s' OR `email` = '%s' AND `id` != '%s' ", $regName, $userID, $regMail, $userID );
	
		$result = $db->query($query);
		
		$row = $result->fetch_assoc();

		if ($result->num_rows > 0) {
			
			return '1';
		
		} else {
		
			return '2';
		
		}
		
	}
	
	
	
	//***********************
	// Counts functions
	//***********************
	function countUserFollowers()
	{	
	
		$count = $this->db->query(sprintf("SELECT `followers` FROM `members` WHERE `id` = '%s'", $this->db->real_escape_string($this->userID)));
		
		if ($count->num_rows > 0) {
			
			$return = $count->fetch_assoc();
			
			$return = $return['followers'];
			
		
		} else {
				
			$return = '0';
				
		}
		
		return $return;
	
	}
	
	function countUserMedia()
	{	
	
		$count = $this->db->query(sprintf("SELECT `id` FROM `media` WHERE `author` = '%s'", $this->db->real_escape_string($this->userID)));
		
		if (isset($count->num_rows)) {
			
			$return = $count->num_rows;
		
		} 
		
		return $return;
	
	}
	
	


	/////////////////////
	// Social login
	/////////////////////
	function socialLogin($name, $realname, $email, $emailVerified, $cover_url, $profile, $provider, $redirect) {

		global $CONF;
		
		$userName = $this->db->real_escape_string($name);
		$userRealName = $this->db->real_escape_string($realname);
		
		if (empty($email)) {
			
			$userEmail = $this->db->real_escape_string($emailVerified);
			
		} else {
		
			$userEmail = $this->db->real_escape_string($email);
		}
		
		if ($provider == "Twitter" || $provider == "twitter" ) {
		
			$twitter = $profile;
			$facebook = '';
		
		} elseif ($provider == "Facebook" || $provider == "facebook") {
			
			$twitter = '';
			$facebook = $profile;
		
		}
		
		// Check if logged in before
		$check = $this->db->query(sprintf("SELECT * FROM `members` WHERE `name` = '%s'  ", $userName));
		
		if ($check->num_rows > 0 ) {
			
			$logged = 1;
			
		} else {
			
			// Upload the cover 
			$dirpath = realpath(dirname(getcwd()));
			
			$cover = mt_rand().'_'.mt_rand().'_'.mt_rand() . '.jpg';
						
			$local_file = $dirpath . $CONF['path'] ."media/channels/" . $cover;
								
			$remote_file = $cover_url; 
									
			$ch = curl_init();
									
			$fp = fopen ($local_file, 'w+');
									
			$ch = curl_init($remote_file);
									
			curl_setopt($ch, CURLOPT_TIMEOUT, 999999);
									
			curl_setopt($ch, CURLOPT_FILE, $fp);
									
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
									
			curl_setopt($ch, CURLOPT_ENCODING, "");
									
			curl_exec($ch);
									
			curl_close($ch);
									
			fclose($fp);
						
			$query = sprintf("INSERT into `members` (`name`,`email`,`realname`,`pic`,`facebook`,`twitter`,`publish`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', 1);", $userName, $userEmail, $userRealName, $cover, $facebook, $twitter);
			
			if ($this->db->query($query)) {
				
				$logged = 1;
				
			}		
		}	
		
		if ($logged == 1) {
				
				
			$_SESSION['membername'] = $userName; 
			
			$_SESSION['start'] = time(); 
			
			$_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
				
			$output = NotePopup( 'Logged in successfully' );
				
			header("Location: ".$CONF['url'].$redirect);
		
		}	else {
				
				header("Location: ".$CONF['url']);
				
		}

	}
	
	
	
	
	
}












