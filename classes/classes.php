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


/* ====================
==== Members class ====
==================== */
class Members {

	public $db;
	public $memberId;
	public $memberName;
	public $memberPass;
	public $memberConfirmPass;
	public $memberFullname;
	public $memberEmail;
	public $memberType;
	public $memberThumb;
	public $memberCover;
	//public $member;
	
	
		
	function UpdateChannel($id, $realname, $pass, $thumb, $email, $facebook, $twitter, $instagram, $youtube, $active) {
		
		$id = $this->db->real_escape_string($id);
	
		$realname = $this->db->real_escape_string($realname);
	
		$pass = $this->db->real_escape_string($pass);
	
		$thumb = $this->db->real_escape_string($thumb);
	
		$email = $this->db->real_escape_string($email);
	
		$facebook = $this->db->real_escape_string($facebook);
	
		$twitter = $this->db->real_escape_string($twitter);
	
		$instagram = $this->db->real_escape_string($instagram);
	
		$youtube = $this->db->real_escape_string($youtube);
	
		$active = $this->db->real_escape_string($active);
		
		if ($email == '0') {	
				
			$email = " ";	
				
		} elseif ($email !== 0 && filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
		
			return 'Please choose valid Email address';
			
			$email = " ";
			
		}  elseif (empty($email) || $this->verify_if_user_exist(0, $email) == '1') {
			
			return 'Username or Email already found';
			
			$email = " ";
		
		} elseif (isset($email) && $this->verify_if_user_exist(0, $email) == '0') {
		
			$email = "`email` = '$email' , ";
		
		} 
		
		if ($active == 'on') {
		
			$publish = " publish = '1' , ";
			
		} elseif (empty($active)) {
		
			$publish = " publish  = '0' , ";
		
		} else {
		
			$publish = " ";
		
		}
		
		if(!empty($realname)) {
		
			$realname = "`realname` = '$realname' , ";
		
		} else {
		
			$realname = " ";
		
		}
			
		if(!empty($pass) && $pass > 7 ) {
		
			$pass = md5($pass);
			
			$password = " `pass` = '$pass' , ";
		
		} else {
			$password = "";
		}
			
		
		$query = sprintf("UPDATE `members` SET %s %s %s %s `pic` = '$thumb', `facebook` = '$facebook', `twitter` = '$twitter', `instagram` = '$instagram', `youtube` = '$youtube'  WHERE id = '%s' ", $realname, $password, $email, $publish, $id);
		
		if($this->db->query($query)) {
		
			return '1';
		
		} else {
			
			return $password;
		
		}
	}

	function addMember() {
	
		
		if (empty($this->memberName) || empty($this->memberPass)) {
			
			return 'Username and Password could not be empty';
		
		} elseif (filter_var($this->memberEmail, FILTER_VALIDATE_EMAIL) == false) {
		
			return 'Please choose valid Email address';
			
		} elseif (strlen($this->memberPass) <= 6) {
		
			return 'Password must be more than (7) numbers or letters';
			
		} elseif (empty($this->memberPass) || $this->memberPass !=  $this->memberConfirmPass ) {
			
			return 'Password not matched';
		
		} elseif (empty($this->memberFullname) ) {
		
			return 'Please enter your Full name';
		
		} elseif ($this->verify_if_user_exist($this->memberName, $this->memberEmail) == '2') {
			
			$action = $this->query();
			
			if($action == 1 ) 
			{
				return '1';
			
			} else {
				return $action;
			}
			
				
		} elseif ($this->verify_if_user_exist($this->memberName, $this->memberEmail) == '1') {
			
			return 'Username or Email already found' . $this->memberEmail .' ' .$this->memberName;
		
		} 
	}
	
	function verify_if_user_exist($userName, $userEmail) {
		global $db;
		
		$regName = $db->real_escape_string($userName);
		
		$regMail = $db->real_escape_string($userEmail);
		
		$query = sprintf("SELECT `name`, `email` FROM `members` WHERE `name` = '%s' OR `email` = '%s' ", $regName, $regMail );
	
		$result = $db->query($query);
		
		$row = $result->fetch_assoc();

		if ($result->num_rows > 0) {
			
			return '1';
		
		} else {
		
			return '2';
		
		}
		
	}
	
	function LogoutMember(){
		
		// Unset all of the session variables.
		$_SESSION = array();

		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
			
		session_unset();
		
		session_destroy();
		
		unset($_SESSION['membername']);
		unset($_SESSION['nameAdmin']);
			
		header("Location: ".$CONF['url']."index.php");
		
		NotePopup('Logged out');
	}
	
	function checkMember() {
		global $db;

		if ($this->memberName && $this->memberPass )
		{
				$memberName = $db->real_escape_string($this->memberName);  
				
				$memberPass = md5($db->real_escape_string($this->memberPass));
			
				$query = sprintf("SELECT * FROM `members` WHERE `name` = '$memberName' AND `pass` = '$memberPass' OR `email` = '$memberName' AND `pass` = '$memberPass' ");

				$result = $this->db->query($query);
				
				if($result->num_rows == 0){
				
					$output = " No existing user or wrong password.";
				
				} else {  
					
					$row = $result->fetch_assoc();
					
					$memberName = $row['name'];
					
					$loggedIn = true;
		
				}
					
		} else {

			if ( isset($_SESSION["membername"]) && $_SESSION["membername"] )
			{
				$loggedIn = true;
			
			} else {
			
				$output = "Please fill all fields.";
			
			}
		}	
		
		if ( isset($loggedIn) )
		{
		
			$_SESSION['membername'] = $memberName; 
			
			$_SESSION['start'] = time(); 
			
			$_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
			
			$output = '1';
		}
		
		return $output;
	}
	
	function query() {
		global $db;
		
		$query = sprintf("INSERT into `members` (`name`,`pass`,`email`,`realname`,`pic`,`cover`,`type`,`date`,`publish`) VALUES ('%s', '%s', '%s', '%s', 'default.png', 0, 1, CURRENT_TIMESTAMP, 1);", $db->real_escape_string(strtolower($this->memberName)), md5($db->real_escape_string($this->memberPass)), $db->real_escape_string($this->memberEmail), $db->real_escape_string($this->memberFullname));
		
		if ($db->query($query)) {
			
			return 1;
		
		} else {
			return  $db->error;
		}
	}
	
	function DeleteMember() {
		
		$query = sprintf("DELETE FROM members WHERE `id` = '%s' ", $this->memberId);
		
		$delete = $this->db->query($query);
			
		if ($delete) {
			$this->db->query(sprintf("DELETE FROM `albums` WHERE `user` = '%s' ", $this->memberId));
			$this->db->query(sprintf("DELETE FROM `cart` WHERE `user` = '%s' ", $this->memberId));
			$this->db->query(sprintf("DELETE FROM `chat` WHERE `user` = '%s' OR `sender` = '%s' ", $this->memberId, $this->memberId));
			$this->db->query(sprintf("DELETE FROM `comments` WHERE `user` = '%s' ", $this->memberId));
			$this->db->query(sprintf("DELETE FROM `follow` WHERE `user` = '%s' ", $this->memberId));
			$this->db->query(sprintf("DELETE FROM `like` WHERE `user` = '%s' ", $this->memberId));
			$this->db->query(sprintf("DELETE FROM `media` WHERE `author` = '%s' ", $this->memberId));
			$this->db->query(sprintf("DELETE FROM `notifications` WHERE `sender` = '%s' OR `user` = '%s' ", $this->memberId, $this->memberId));
			$this->db->query(sprintf("DELETE FROM `playlist` WHERE `user` = '%s' ", $this->memberId));
			$this->db->query(sprintf("DELETE FROM `playlist_items` WHERE `user` = '%s' ", $this->memberId));
			return 1;
		} else {
			return 0;
		}		
	}

	function UpdateMember() {
	
		if(!empty($memberFullname)) {
			$realname = $this->db->real_escape_string($memberFullname);
			$realname = "`realname` = '$realname'";
		} else {
			$realname = "";
		}
		
		if(!empty($memberPass) ) {
			if($memberPass == $memberConfirmPass ) {
				$pass = md5($db->real_escape_string($memberPass));
				$pass = "`pass` = '$pass'";
			} else {
				return 'Password not matched';
			}
		} else {
			$pass = "";
		}
		
		if(!empty($realname) && !empty($pass)) {
			
			$and = ' , ';
			
		}
		
		$query = sprintf("UPDATE `members` SET %s %s %s , `email` = '$email', `publish` = '$active' WHERE id = '%s' ", $realname, $and, $memberPass, $memberId);
		
		if($this->db->query($query)) {
		
			return '1';
		} else {
			return 0;
		}
	}

	function GetMember($id) {
		global $db;
		
		$id = $db->real_escape_string($id);
		
		if (!empty($id) && $id !== '0') {
		
			$query = sprintf("SELECT * FROM `members` WHERE `id` = '$id' ");

			$result = $db->query($query);
			
			$row = $result->fetch_array();
			
			if($result == null){
				
				echo "<div> No existing User.</div>";
					
			} else {
				return $row;
			}
		}
	}

	function GetMember_by_name($name) {
		global $db;
		
		$name = $db->real_escape_string($name);
		
		if (!empty($name) && $name !== '0') {
		
			$query = sprintf("SELECT * FROM `members` WHERE `name` = '$name' ");

			$result = $db->query($query);
			
			$row = $result->fetch_array();
			
			if($result == null){
				
				echo "<div> No existing User.</div>";
					
			} else {
				return $row;
			}
		}
	}

	function AllMembers() {
		global $db;
		
		$query = sprintf("SELECT * FROM `members` ORDER BY `id` DESC LIMIT 12");

		$result = $this->db->query($query);
		
		$rows = '';
		
		if ($result->num_rows > 0 ) {		
		
			while ($row = $result->fetch_assoc()) {
				
				$rows[] = $row;
			
			}
			
			
		}
		return $rows;
	}	

	function moreMembersID($id) {
		global $db;
		
		$query = sprintf("SELECT * FROM `members` WHERE `id` < '%s' ORDER BY `id` DESC LIMIT 12", $id);
		
		$rows = '';
		
		$result = $this->db->query($query);
		
		if ($result->num_rows > 0 ) {		
		
			while ($row = $result->fetch_assoc()) {
				
				$rows[] = $row;
			
			}
			
			
		}
		return $rows;
	}	
}


/* ========================
=== Administrator class ===
======================== */
class FullAdmin {

	public $db;
	public $adminId;
	public $adminFullName;
	public $adminName;
	public $adminPass;
	public $adminEmail;
	public $adminLevel;
	public $adminPublish;
	
	function AddAdmin() {
	
		if (empty($this->adminFullName) || empty($this->adminName) || empty($this->adminPass) || empty($this->adminEmail) || empty($this->adminPublish)) {
			return 'Please fill all fields';
		}
		
		if ($this->verify_if_user_exist() == 1) {
			$this->query();
			return 1;
		} else {
			return 'This email or username already excited. Choose another one';
		}
	}
	
	function verify_if_user_exist() {
		
		$query = sprintf("SELECT `name`, `email` FROM `admins` WHERE `name` = '%s' OR `email` = '%s'", $this->db->real_escape_string(strtolower($this->adminName)), $this->db->real_escape_string(strtolower($this->adminEmail)));
		
		$result = $this->db->query($query);
		
		if ($result->num_rows == 0) {
			return 1;
		} else {
			return 0;
		}
	}
	
	function verify_if_user_exist_update() {
		
		$id = $this->db->real_escape_string($this->adminId);
		
		$name = $this->db->real_escape_string(strtolower($this->adminName));
		
		$email = $this->db->real_escape_string($this->adminEmail);
		
		$query = sprintf("SELECT * FROM `admins` WHERE `name` = '%s' AND `id` != '%s' OR `email` = '%s' AND `id` != '%s' " , $name, $id, $email , $id);
		
		$result = $this->db->query($query);
		
		if ($result->num_rows > 0) {
			return 2;
		} else {
			return 1;
		}
	}
	
	function LogoutAdmin(){
	
		if( isset($_SESSION["nameAdmin"]) && $_SESSION["nameAdmin"] )
		{
			unset($_SESSION['nameAdmin']);
			unset($_SESSION['pass']);
			setcookie("nameAdmin", '', 1);
			setcookie("admin", '', 1);
			session_destroy();
		}
	}
	
	function checkAdmin() {
		
		$output = ''; $loggedIn = '';
		
		/////////////////////////////////////////////////
		// Allow Admin to login by 'email' or 'username'
		/////////////////////////////////////////////////
			
		if (isset($_POST["username"]) ) {
		
			$adminName = $this->db->real_escape_string($_POST["username"]);
			$adminEmail = $this->db->real_escape_string($_POST["username"]); 
			
			$adminPass = $this->db->real_escape_string(md5($_POST["password"]));
			
			if ($adminName && $adminPass )
			{
				$query = sprintf("SELECT * FROM `admins` WHERE `name` = '$adminName' AND `password` = '$adminPass' OR  `email` = '$adminName' AND `password` = '$adminPass'");
				
				// echo $query;
				$result = $this->db->query($query);
				
				if($result->num_rows == 0){
					
					$output .= "<div> No existing account or wrong password.</div>";
				
				} else { 
					
					$output = 1; 
					
					$_SESSION['nameAdmin'] = $adminName; 
					
				}
			}
			
		} else {
	
			if ( isset($_SESSION["nameAdmin"]) && $_SESSION["nameAdmin"] )
			{
				$loggedIn = 1;
			}
		}	
		
		if ( $loggedIn != 1 )
		{
			$output .= '';
			
		} else {
			
			$_SESSION['start'] = time(); 
			
			$_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
			
		}
		
		echo $output;
	}
	
	function query() {
		
		$adminFullName = $this->db->real_escape_string($this->adminFullName);
		$adminName = $this->db->real_escape_string(strtolower($this->adminName));
		$adminPass = md5($this->db->real_escape_string($this->adminPass));
		$adminEmail = $this->db->real_escape_string($this->adminEmail);
		$adminLevel = $this->db->real_escape_string($this->adminLevel);
		$adminPublish = $this->db->real_escape_string($this->adminPublish);
		
		if ($adminPublish == 'on') {
			$adminPublish = '1';
		} else {
			$adminPublish = '0';
		}
		
		$query = sprintf("INSERT into `admins` (`fullname`,`name`,`password`,`email`,`level`,`publish`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s');"
		, $adminFullName, $adminName, $adminPass, $adminEmail, $adminLevel, $adminPublish );
		
		if ($this->db->query($query)) {
			
			return 1;
		
		}
	}
	
	
	
	//--------------------------
	// Administrator functions
	//--------------------------
	function DeleteAdmin() {
		
		$id = $this->db->real_escape_string($this->adminId);
		
		$query = sprintf("DELETE FROM `admins` WHERE `id` = '%s' ", $id);
			
		if($this->db->query($query)) 
		{
			return 1;
		}	else {
			return 'ID not found';
		}	 	
	}

	function editAdmin() {
		
		$id = $this->db->real_escape_string($this->adminId);
		
		if ($this->verify_if_user_exist_update() == 1) {
		
			if(!empty($this->adminName)) {
				$username = $this->db->real_escape_string(strtolower($this->adminName));
				$name = "`name` = '$username' , ";
			} else {
				$name = "";
			}
			
			if(!empty($this->adminPass)) {
				$adminPass = md5($this->db->real_escape_string($this->adminPass));
				$pass = " , `password` = '$adminPass' ";
			} else {
				$pass = " ";
			}
			
			if(!empty($this->adminLevel)) {
				$level = " , `level` = '$this->adminLevel'  ";
			} else {
				$level = "";
			}
			
			if(isset($this->adminPublish) && $this->adminPublish == 'on') {
				$publish = " , `publish` = '1' ";
			} else {
				$publish = " , `publish` = '0' ";
			}
			
			
			$query = sprintf("UPDATE `admins` SET %s `fullname` = '%s', `email` = '%s' %s %s %s WHERE id = '%s' ",
			$name, $this->db->real_escape_string($this->adminFullName),
			$this->db->real_escape_string($this->adminEmail),
			$level, $publish, $pass, $id);
			
			if ($this->db->query($query))
			{
				return 1;
				
			} else {
				return ' unknown error';
			}
			
		} else {
			return 'This email or username already excited. Choose another one';
		}
		
	}

	function getAdmin($id) {
		
		$id = $this->db->real_escape_string($id);
		
		$query = sprintf("SELECT * FROM `admins` WHERE `id` = '%s'", $id);

		$result = $this->db->query($query);
		
		if($result->num_rows > 0) {
				
			while ($row = $result->fetch_assoc()) {
				
				return $row;
			
			}
		}
	}

	function get_admin_by_name($name) {
		
		$name = $this->db->real_escape_string($name);
		
		$query = sprintf("SELECT * FROM `admins` WHERE `name` = '%s'", $name);

		$result = $this->db->query($query);
		
		if($result->num_rows > 0) {
				
			while ($row = $result->fetch_assoc()) {
				
				return $row;
			
			}
		}
	}

	function AllAdmins() {
		
		$view ='';
		
		$query = sprintf("SELECT * FROM `admins`");

		$result = $this->db->query($query);
		
		if($result->num_rows > 0) {
				
			while ($row = $result->fetch_assoc()) {
				
				$rows[] = $row;
			
			}
			
			foreach($rows as $row) {
				
				if ($row['publish'] == 1) {
					$publish = '<i class="fa fa-check"></i>';
				} else {
					$publish = '<i class="fa fa-times" "></i>';
				}
				
				$level = $this->levelTitle($row['level']);
				
				$view .= '
					<tr class="view_administrators" id="admin'.$row['id'].'">
						<td>
							'.$row['id'].' 
						</td>
						<td>
							<a href="'.$row['fullname'].'" > '.$row['fullname'].' </a>
						</td>
						<td>
							<span> '.$row['name'].' </span>
						</td>
						<td>
							<span> '.$row['email'].' </span>
						</td>
						<td class=" hide hidden">
							<span> '.$level.' </span>
						</td>
						<td>
							<span> '.$publish.' </span>
						</td>
						<td>
							<span><a class="btn btn-info btn-sm require-form" data-id="'.$row['id'].'" data-title="Edit Administrator" data-require="edit_admin" href="#MinModal" data-toggle="modal" data-placement="bottom">
								<i class="icon-edit"></i>
							</a></span>
						</td>
						<td>
							<span><a class="btn btn-danger btn-sm confirm-form" data-id="'.$row['id'].'" data-type="del_admin"  href="#ConfirmModal" data-title="Are you sure you want to delete '.$row['name'].'" data-toggle="modal" data-placement="bottom">
								<i class="icon-trash"></i>
							</a></span>
						</td>
					</tr>
				';

			}
		} else {
			
			$view = '';
			
		}		
		return $view;
	}	
			

	//-------------------
	// Setting functions
	//-------------------
	function siteSetting() {
		global $CONF, $db;
		
		$query = sprintf("SELECT * from `settings`");
		
		$result = $db->query($query);
		
		if ($result->num_rows > 0 ) {
			
			while ($Settings = $result->fetch_assoc()) {
			
				return $Settings;
			
			}
		}	
	}
	
	
	function editSettings($post) {
		
		if ( isset($post['under']) ) {$under = option_toNumber($post['under']); } else {$under = '';}
		if ( isset($post['allow_reg']) ) {$allow_reg = option_toNumber($post['allow_reg']); } else {$allow_reg = '';}
		if ( isset($post['facebook']) ) {$facebook = option_toNumber($post['facebook']); } else {$facebook = '';}
		if ( isset($post['twitter']) ) {$twitter = option_toNumber($post['twitter']); } else {$twitter = '';}
		if ( isset($post['google']) ) {$google = option_toNumber($post['google']); } else {$google = '';}
		if ( isset($post['comments']) ) {$comments = option_toNumber($post['comments']); } else {$comments = '';}
		if ( isset($post['edit_comments']) ) {$edit_comments = option_toNumber($post['edit_comments']); } else {$edit_comments = '';}
		if ( isset($post['html_comments']) ) {$html_comments = option_toNumber($post['html_comments']); } else {$html_comments = '';}
		if ( isset($post['enable_music']) ) {$enable_music = option_toNumber($post['enable_music']); } else {$enable_music = '';}
		if ( isset($post['enable_videos']) ) {$enable_videos = option_toNumber($post['enable_videos']); } else {$enable_videos = '';}
		if ( isset($post['enable_photos']) ) {$enable_photos = option_toNumber($post['enable_photos']); } else {$enable_photos = '';}
		if ( isset($post['enable_paid']) ) {$enable_paid = option_toNumber($post['enable_paid']); } else {$enable_paid = '';}
		if ( isset($post['enable_soundcloud']) ) {$enable_soundcloud = option_toNumber($post['enable_soundcloud']); } else {$enable_soundcloud = '';}
		if ( isset($post['enable_youtube']) ) {$enable_youtube = option_toNumber($post['enable_youtube']); } else {$enable_youtube = '';}
		if ( isset($post['enable_ajax']) ) {$enable_ajax = option_toNumber($post['enable_ajax']); } else {$enable_ajax = '';}
		if ( isset($post['auto_publish']) ) {$auto_publish = option_toNumber($post['auto_publish']); } else {$auto_publish = '';}
		
		$sitename = $post['sitename'];
		$desc = $post['desc'];
		$keywords = $post['keywords'];	
		$email = $post['email'];	
		$pic_ext = $post['pic_ext'];	
		$audio_ext = $post['audio_ext'];	
		$videos_ext = $post['videos_ext'];	
		$sender_email = $post['sender_email'];	
		$undermsg = $post['undermsg'];	
		$template = $post['template'];	
		$logo = $post['logo'];	
		$logo_w = $post['logo_w'];	
		$logo_h = $post['logo_h'];	
		$facebook_key = $post['facebook_key'];	
		$facebook_secret = $post['facebook_secret'];
		$twitter_key = $post['twitter_key'];	
		$twitter_secret = $post['twitter_secret'];
		$google_key = $post['google_key'];	
		$google_secret = $post['google_secret'];
		$google_analytics = $post['google_analytics'];
		$soundcloud_key = $post['soundcloud_key'];
		$youtube_key = $post['youtube_key'];
		$css = $post['css'];
		$js = $post['js'];
		$percent = $post['percent'];
		
		$query = sprintf("UPDATE `settings` SET `sitename` = '{$sitename}', `desc` = '{$desc}', `keywords` = '{$keywords}', `allow_reg` = '{$allow_reg}',
		`undermsg` = '{$undermsg}',`under` = '{$under}',`email` = '{$email}', `sender_email` = '{$sender_email}', `template` = '{$template}',
		`css` = '{$css}', `js` = '{$js}', `pic_ext` = '{$pic_ext}', `audio_ext` = '{$audio_ext}', `videos_ext` = '{$videos_ext}',
		`logo` = '{$logo}', `logo_w` = '{$logo_w}', `logo_h` = '{$logo_h}', `google_analytics` = '{$google_analytics}'
		, `facebook` = '{$facebook}', `facebook_key` = '{$facebook_key}', `facebook_secret` = '{$facebook_secret}'
		, `twitter` = '{$twitter}', `twitter_key` = '{$twitter_key}', `twitter_secret` = '{$twitter_secret}'
		, `google` = '{$google}', `google_key` = '{$google_key}', `google_secret` = '{$google_secret}', `youtube_key` = '{$youtube_key}', `soundcloud_key` = '{$soundcloud_key}'
		, `comments` = '{$comments}', `edit_comments` = '{$edit_comments}', `html_comments` = '{$html_comments}', `auto_publish` = '{$auto_publish}'
		, `enable_music` = '{$enable_music}', `enable_videos` = '{$enable_videos}', `enable_photos` = '{$enable_photos}', `enable_paid` = '{$enable_paid}'
		, `enable_youtube` = '{$enable_youtube}', `enable_soundcloud` = '{$enable_soundcloud}', `enable_ajax` = '{$enable_ajax}', `percent` = '{$percent}'
		");
		
		$statement = $this->db->query($query);
		
		if ($statement) {
		
			return 1;
		
		} else {
		
			return $this->db->error;
		
		}	
	}
	
	//------------------------------
	// Registered Members functions
	//------------------------------
	function GetMember($id) {
		global $db;
		
		$id = $db->real_escape_string($id);
		
		if (!empty($id) && $id !== '0') {
		
			$query = sprintf("SELECT * FROM `members` WHERE `id` = '$id' ");

			$result = $db->query($query);
			
			$row = $result->fetch_array();
			
			if($result == null){
				
				echo "<div> No existing User.</div>";
					
			} else {
				return $row;
			}
		}
	}

	function GetMember_by_name($name) {
		global $db;
		
		$name = $db->real_escape_string($name);
		
		if (!empty($name) && $name !== '0') {
		
			$query = sprintf("SELECT * FROM `members` WHERE `name` = '$name' ");

			$result = $db->query($query);
			
			$row = $result->fetch_array();
			
			if($result == null){
				
				echo "<div> No existing User.</div>";
					
			} else {
				return $row;
			}
		}
	}

	//---------------------------------
	// Administrator levels functions
	//---------------------------------
	function AddALevel($title, $acc_view, $acc_edit, $set_edit, $bulk_msg) {
	
		$title = $this->db->real_escape_string($title);
		
		if ($acc_view == 'on') {$acc_view = '1';} else {$acc_view = '0';}
		if ($acc_edit == 'on') {$acc_edit = '1';} else {$acc_edit = '0';}
		if ($set_edit == 'on') {$set_edit = '1';} else {$set_edit = '0';}
		if ($bulk_msg == 'on') {$bulk_msg = '1';} else {$bulk_msg = '0';}
		
		$query = sprintf("INSERT INTO `levels` (`title`, `account_view`, `account_edit`, `edit_setting`, `bulk_msg`)  VALUES ('%s','%s','%s','%s','%s') ",
		$title,$acc_view,$acc_edit,$set_edit,$bulk_msg);
		
		if ($this->db->query($query)) 
		{ 
			return 1;			
		}
	}

	function editLevel($id, $title, $acc_view, $acc_edit, $set_edit, $bulk_msg) {
		
		$id = $this->db->real_escape_string($id);
		$title = $this->db->real_escape_string($title);
		if ($acc_view == 'on') {$acc_view = '1';} else {$acc_view = '0';}
		if ($acc_edit == 'on') {$acc_edit = '1';} else {$acc_edit = '0';}
		if ($set_edit == 'on') {$set_edit = '1';} else {$set_edit = '0';}
		if ($bulk_msg == 'on') {$bulk_msg = '1';} else {$bulk_msg = '0';}
		
		$query = sprintf("UPDATE `levels` SET title = '{$title}', `account_view` = '{$acc_view}', `account_edit` = '{$acc_edit}', `edit_setting` = '{$set_edit}', `bulk_msg` = '{$bulk_msg}' WHERE `id` = '{$id}'  ");
		
		$result = $this->db->query($query);
		
		if ($result) {
		
			return 1;
		
		} else {
		
			return 0;
		
		}	
	}
	
	function DeleteLevel($id) {
		
		$id = $this->db->real_escape_string($id);
		
		$query = sprintf("DELETE FROM `levels` WHERE `id` = '%s' ", $id);
			
		if($this->db->query($query)) 
		{
			return 1;
		}	else {
			return 'ID not found';
		}	 	
	}
	
	function levelTitle($id) {
		
		$id = $this->db->real_escape_string($id);
		
		$query = sprintf("SELECT * from `levels` WHERE `id` = '%s'", $id);
		
		$result = $this->db->query($query);
		
		if ($result->num_rows > 0 ) {
			
			while ($level = $result->fetch_assoc()) {
			
				return $level['title'];
			
			}
		}	
	}	
	
	function adminsLevelsSelect() {
		
		$output ='';
		
		$query = sprintf("SELECT * from `levels`");
		
		$result = $this->db->query($query);
		
		if ($result->num_rows > 0 ) {
			
			while ($level = $result->fetch_assoc()) {
			
				$levels[] = $level;
			
			}
			
			foreach ($levels as $level) {
				
				$output .= '<option value="'.$level['id'].'">'.$level['title'].'</option>';
			}
			
			return $output;
		}	
	}
	
	function adminsLevels($id) {
		
		$output = '';
		
		if (isset($id)) {
		
			$id = $this->db->real_escape_string($id);
		
			$query = sprintf("SELECT * from `levels` WHERE `id` = '%s' ", $id);
			
		} else {
		
			$query = sprintf("SELECT * from `levels`");
			
		}
		
		$result = $this->db->query($query);
		
		if ($result->num_rows > 0 ) {
			
			if (isset($id)) {
			
				while ($level = $result->fetch_assoc()) {
				
					return $level;
				
				}
				
			} else {
			
				while ($level = $result->fetch_assoc()) {
				
					$levels[] = $level;
				
				}
			
				foreach ($levels as $row) {
					
					if ($row['account_view']) { $acc_view = '<i class="fa fa-check"></i>'; } else { $acc_view = '<i class="fa fa-times"></i>'; }
					if ($row['account_edit']) { $acc_edit = '<i class="fa fa-check"></i>'; } else { $acc_edit = '<i class="fa fa-times"></i>'; }
					if ($row['edit_setting']) { $set_edit = '<i class="fa fa-check"></i>'; } else { $set_edit = '<i class="fa fa-times"></i>'; }
					if ($row['bulk_msg']) { $bulk_msg = '<i class="fa fa-check"></i>'; } else { $bulk_msg = '<i class="fa fa-times"></i>'; }
					
					$output .= '
						<tr class="view_administrators" id="admin'.$row['id'].'">
							<td>
								'.$row['id'].' 
							</td>
							<td>
								<span> '.$row['title'].' </span>
							</td>
							<td>
								<span> '.$acc_view.' </span>
							</td>
							<td>
								<span> '.$acc_edit.' </span>
							</td>
							<td>
								<span> '.$set_edit.' </span>
							</td>
							<td>
								<span> '.$bulk_msg.' </span>
							</td>
							<td>
								<span><a class="btn btn-default btn-sm require-form" data-id="'.$row['id'].'" data-title="Edit Level" data-require="edit_level" href="#MinModal" data-toggle="modal" data-placement="bottom">
									<i class="fa fa-edit"></i>
								</a></span>
							</td>
							<td>
								<span><a class="btn btn-danger btn-sm confirm-form" data-id="'.$row['id'].'" data-type="del_level"  href="#ConfirmModal" data-title="Are you sure you want to delete '.$row['title'].'" data-toggle="modal" data-placement="bottom">
									<i class="fa fa-times"></i>
								</a></span>
							</td>
						</tr>';
				}
				
				return $output;
			}	
		}	
	}
	
	
	function editADS($topad, $bottomad, $sidead) {
		global $db;
		
		$topad = $db->real_escape_string($topad);
		
		$bottomad = $db->real_escape_string($bottomad);
		
		$sidead = $db->real_escape_string($sidead);
		
		// Edit ads
		$query = sprintf("UPDATE `ads` SET `topad` = '%s', `bottomad` = '%s', `sidead` = '%s' ", $topad, $bottomad, $sidead );
		$do = $db->query($query);
			
		if ($do) {
			return 1;
		} else {
			return 2;
		}
	}
	
	
	function updateTemplate($title) {
		
		$query = sprintf("UPDATE `settings` SET `template` = '%s' ", $title );
		$do = $this->db->query($query);
			
		if ($do) {
			return 1;
		} else {
			return 2;
		}
	
	}

	
	
	//-----------------
	// Media functions
	//-----------------
	function loadMedia($type) {
		
		$type = $this->db->real_escape_string($type);
		
		$query = sprintf("SELECT * FROM `media` WHERE `type` = '%s' ORDER BY `id` DESC LIMIT 12 ", $type);

		$result = $this->db->query($query);
		
		if($result->num_rows > 0) {
				
			while ($row = $result->fetch_assoc()) {
				
				$return[] = $row;  
			
			}
			return $return;
		}
	}
	
	
	function moreMediaID($id, $type) {
		
		$type = $this->db->real_escape_string($type);
		$id = $this->db->real_escape_string($id);
		
		$query = sprintf("SELECT * FROM `media` WHERE `type` = '%s' AND `id` < '%s' ORDER BY `id` DESC LIMIT 12 ", $type, $id);

		$result = $this->db->query($query);
		
		if($result->num_rows > 0) {
				
			while ($row = $result->fetch_assoc()) {
				
				$return[] = $row;  
			
			}
			return $return;
		}
	}
	
	
	
	//-----------------
	// Pages functions
	//-----------------
	function AddPage($title, $prefix, $template, $layout, $content, $keywords, $desc, $publish ) {
		
		if ($publish == 'on') {$publish = '1';} else {$publish = '0';}
		
		$query = sprintf("INSERT INTO `pages` (`title`, `prefix`, `template`, `layout`, `content`, `keywords`, `desc`, `publish`) VALUES ('%s' , '%s' , '%s' , '%s' ,  '%s' ,  '%s' ,  '%s' , '%s')  ", $title, $prefix, $template, $layout, $content, $keywords, $desc, $publish );
		
		$do = $this->db->query($query);
			
		if ($do) {
			return 1;
		} else {
			return $this->db->error;
		}
	
	}
	
	
	function getPage($id) {
		
		$id = $this->db->real_escape_string($id);
		
		$query = sprintf("SELECT * FROM `pages` WHERE `id` = '%s'", $id);

		$result = $this->db->query($query);
		
		if($result->num_rows > 0) {
				
			while ($row = $result->fetch_assoc()) {
				
				return $row;
			
			}
		}
	}
	
	
	function page_by_link($prefix) {
		
		$prefix = $this->db->real_escape_string($prefix);
		
		$query = sprintf("SELECT * FROM `pages` WHERE `prefix` = '%s'", $prefix);

		$result = $this->db->query($query);
		
		if($result->num_rows > 0) {
				
			while ($row = $result->fetch_assoc()) {
				
				return $row;
			
			}
		}
	}
	
	function editPage($id, $title, $prefix, $template, $layout, $content, $keywords, $desc, $publish) {
		
		$publish = option_toNumber($publish);
		
		// Edit Pages
		$query = sprintf("UPDATE `pages` SET `title` = '%s', `prefix` = '%s', `template` = '%s', `layout` = '%s', `content` = '%s', `keywords` = '%s', `desc` = '%s', `publish` = '%s'  WHERE `id` = '%s' ", $title, $prefix, $template, $layout, $content, $keywords, $desc, $publish, $id );
		$do = $this->db->query($query);
			
		if ($do) {
			return 1;
		} else {
			return 2;
		}
	}
	
	function set_homepage($id) {
		
		global $db;
		
		$current_home = page_query_home();
		
		$query2 = sprintf("UPDATE `pages` SET `home` = '0'  WHERE `id` = '%s' ", $current_home['id'] );
		$query = sprintf("UPDATE `pages` SET `home` = '1', `publish` = '1'  WHERE `id` = '%s' ", $id );
		
		$do = $db->query($query);
		$undo = $db->query($query2);
			
		if ($do && $undo) {
			return 1;
		} else {
			return 2;
		}
	}
	
	
	function DeletePage($id) {
		
		$id = $this->db->real_escape_string($id);
		
		$query = sprintf("DELETE FROM `pages` WHERE `id` = '%s' ", $id);
			
		if($this->db->query($query)) 
		{
			return 1;
		}	else {
			return 'ID not found';
		}	 	
	}
	
	
	
	
	
	//----------------------
	// Categories functions
	//----------------------
	function AddCategory($title, $link, $category, $publish, $type ) {
		
		$query = $this->getCategoryLink($link);
		
		if (isset($query['id'])) {
			$link = $link . rand(1,99);
		}
		
		if ($publish == 'on') {$publish = '1';} else {$publish = '0';}
		
		$query = sprintf("INSERT INTO `cats` (`title`, `url`, `mother`, `publish`, `type`) VALUES ('%s' , '%s' , '%s' , '%s', '%s')  ", $title, $link, $category, $publish, $type );
		
		$do = $this->db->query($query);
			
		if ($do) {
			return 1;
		} else {
			return 2;
		}
	
	}
	
	
	function loadCats($type) {
		
		$query = sprintf("SELECT * FROM `cats` WHERE `id` != 0 AND `type` = '%s' ", $type);

		$result = $this->db->query($query);
		
		if($result->num_rows > 0) {
				
			while ($row = $result->fetch_assoc()) {
				
				$rows[] = $row;
				
			}
			return $rows;
		}
	}
	
	function getCategory($id) {
		
		$id = $this->db->real_escape_string($id);
		
		$query = sprintf("SELECT * FROM `cats` WHERE `id` = '%s'", $id);

		$result = $this->db->query($query);
		
		if($result->num_rows > 0) {
				
			while ($row = $result->fetch_assoc()) {
				
				return $row;
			
			}
		}
	}
	
	function getCategoryLink($link) {
		
		$link = $this->db->real_escape_string($link);
		
		$query = sprintf("SELECT * FROM `cats` WHERE `url` = '%s'", $link);

		$result = $this->db->query($query);
		
		if($result->num_rows > 0) {
				
			while ($row = $result->fetch_assoc()) {
				
				return $row;
			
			}
		}
	}
	
	function editCategory($id, $title, $link, $mother, $publish) {
		
		$publish = option_toNumber($publish);
		
		// Edit category
		$query = sprintf("UPDATE `cats` SET `title` = '%s', `mother` = '%s', `publish` = '%s'  WHERE `id` = '%s' ", $title, $mother, $publish, $id );
		$do = $this->db->query($query);
			
		if ($do) {
			return 1;
		} else {
			return 2;
		}
	}
	
	
	function DeleteCategory($id) {
		
		$id = $this->db->real_escape_string($id);
		
		$query = sprintf("DELETE FROM `cats` WHERE `id` = '%s' ", $id);
			
		if($this->db->query($query)) 
		{
			return 1;
		}	else {
			return 'ID not found';
		}	 	
	}
	
	
	//----------------
	// Posts functions
	//----------------
	function AddPost_by_admin($post, $admin) {
		
		if (isset($post['publish']) && $post['publish'] == 'on') {$publish = '1';} else {$publish = '0';}
		
		if ($post['photo'])
		{
			$admin = $this->get_admin_by_name($admin);
			
			$query = sprintf("INSERT INTO `posts` (`title`, `short`, `content`, `cat`, `photo`, `admin`, `publish`) VALUES ('%s' , '%s' , '%s', '%s', '%s', '%s' , '%s')  ", $post['title'], $post['short'], $post['full'], $post['cat'],  $post['photo'],  $admin['id'],  $publish  );
			
			$do = $this->db->query($query);
				
			if ($do) {
				return 1;
			} else {
				return 2;
			}
		
		}	else {
		
			return 'upload photo first';
		
		}
	}
	
	function AddPost_by_member($title, $cat, $short, $content, $publish, $author, $file ) {
		
		if ($publish == 'on') {$publish = '1';} else {$publish = '0';}
		
		$upload = upload_media($file);
		
		if ($upload)
		{
			$author = $this->GetMember_by_name($author);
			
			$query = sprintf("INSERT INTO `posts` (`title`, `short`, `content`, `cat`, `photo`, `author`, `publish`) VALUES ('%s' , '%s' , '%s', '%s', '%s', '%s' , '%s')  ", $title, $short, $content, $cat,  $upload,  $author['id'],  $publish  );
			
			$do = $this->db->query($query);
				
			if ($do) {
				return 1;
			} else {
				return 2;
			}
		
		}	else {
		
			return 'upload photo first';
		
		}
	}
	
	
	function loadPosts($limit = null) {
		
		if(isset($limit)) {$limit = $limit;} else {$limit = 20;}
		
		$query = sprintf("SELECT * FROM `posts` LIMIT 0,%s " , $limit);

		$result = $this->db->query($query);
		
		if($result->num_rows > 0) {
				
			while ($row = $result->fetch_assoc()) {
				
				$rows[] = $row;
				
			}
			return $rows;
		}
	}
	
	
	function load_posts_custom($cat = null, $order = null, $limit = null, $author = null) {
		
		if(isset($cat) && !empty($cat)) {$_cat = "  AND `cat` = '{$cat}' " ;} else {$_cat = ' ';}
		
		if(isset($author) && !empty($author)) {$_author = "  AND `author` = '{$author}' " ;} else {$_author = ' ';}
		
		if(isset($order) && !empty($order)) {$_order = " order by id {$order} " ;} else {$_order = ' ';}
		
		if(isset($limit)  && !empty($limit)) {$_limit = $limit;} else {$_limit = 10;}
		
		$query = sprintf("SELECT * FROM `posts` WHERE `id` != 0 %s %s  %s  LIMIT %s " , $_cat , $_author , $_order , $_limit);

		$result = $this->db->query($query);
		
		if($result->num_rows > 0) {
				
			while ($row = $result->fetch_assoc()) {
				
				$rows[] = $row;
				
			}
		
		} else {
			$rows = $this->db->error;
		}	
		return $rows;
	}
	
	function getPost($id) {
		
		$id = $this->db->real_escape_string($id);
		
		$query = sprintf("SELECT * FROM `posts` WHERE `id` = '%s'", $id);

		$result = $this->db->query($query);
		
		if($result->num_rows > 0) {
				
			while ($row = $result->fetch_assoc()) {
				
				return $row;
			
			}
		}
	}
	
	function editPost($post, $admin) {
		global $CONF;
		
		if (isset($post['publish'])) {$publish = option_toNumber($post['publish']);} else {$publish = '0';}
		
		// Edit post
		$query = sprintf("UPDATE `posts` SET `title` = '%s', `cat` = '%s', `photo` = '%s', `short` = '%s', `content` = '%s', `publish` = '%s'  
		WHERE `id` = '%s' ", $post['title'], $post['cat'], $post['photo'], $post['short'], $post['full'], $publish, $post['id'] );
		$do = $this->db->query($query);
			
		if ($do) {
			return 1;
		} else {
			return 2;
		}
	}
	
	
	function DeletePost($id) {
		
		global $CONF;
			
		$id = $this->db->real_escape_string($id);
		
		$post = $this->getPost($id);
		
		//if ($post['photo'] != 1) { unlink($CONF['full_path'] .'media/photos/'. $post['photo']); }
		
		$query = sprintf("DELETE FROM `posts` WHERE `id` = '%s' ", $id);
			
		if($this->db->query($query)) 
		{
			return 1;
		}	else {
			return 'ID not found';
		}	 	
	}
	
	
	
	//----------------
	// Hooks functions
	//----------------		
	function add_hook($post ) {
		
		global $db;
		
		$publish = option_toNumber($post['hook_publish']);
		
		$hook_title = $post['hook_title'];
		$hook_position = $post['hook_position'];
		$hook_template = $post['hook_template'];
		$hook_plugin = $post['hook_plugin'];
		$hook_pages = serialize($post['hook_pages']);
		
		unset($post['hook_title'],$post['hook_position'],$post['hook_template'],$post['hook_plugin'],$post['hook_pages'],$post['hook_publish'],$post['hook_id'],$post['form_type']);
		
		$Plugin_content = serialize($post);
		
		$query = $db->query(sprintf("INSERT INTO `hooks` (`title`, `position`, `template`, `plugin`, `pages`, `publish`, `content`) VALUES  ('%s','%s','%s','%s','%s','%s','%s' ) "
		, $hook_title, $hook_position, $hook_template, $hook_plugin, $hook_pages, $publish, $Plugin_content ));
					
		if ($query) { $output = '1'; } else {$output = $db->error;}
		
		return $output;
		
	}

	function update_hook($post) {
		
		global $db;
		
		if (isset($post['hook_publish'])) {$publish = option_toNumber($post['hook_publish']);} else {$publish = '0';}
		
		$output = '';
		
		$hook_id = $post['hook_id'];
		$hook_title = $post['hook_title'];
		$hook_position = $post['hook_position'];
		$hook_template = $post['hook_template'];
		$hook_plugin = $post['hook_plugin'];
		$hook_pages = serialize($post['hook_pages']);
		
		unset($post['hook_title'],$post['hook_position'],$post['hook_template'],$post['hook_plugin'],$post['hook_pages'],$post['hook_publish'],$post['hook_id'],$post['form_type']);
		
		$Plugin_content = serialize($post);

		$query = $db->query(sprintf("UPDATE `hooks` SET `title` = '%s', `position` = '%s', `template` = '%s', `pages` = '%s', `publish` = '%s', `content` = '%s' WHERE `id` = '%s' "
		, $hook_title, $hook_position, $hook_template, $hook_pages, $publish, $Plugin_content, $hook_id ));
						
		if ($query) { $output = '1'; } else {$output = $db->error;}
		
		return $output;
		
	}
	
	
	function DeleteHook($id) {
		
		$id = $this->db->real_escape_string($id);
		
		$query = sprintf("DELETE FROM `hooks` WHERE `id` = '%s' ", $id);
			
		if($this->db->query($query)) 
		{
			return 1;
		}	else {
			return 'ID not found';
		}	 	
	}
	
	
	
	// Count function
	function count_items($table, $params) {
		
		$count = $this->db->query(sprintf("SELECT `id` FROM %s %s  ", $table, $params));
		
		$return = 0;
		
		if (isset($count->num_rows)) {
			
			$return = $count->num_rows;
		
		} 
		
		return $return;
		
	}
	
	
	
	
	function send_admin_message($title, $content, $sender, $reply_id = null ) {
		
		
		$query = sprintf("INSERT INTO `messages` (`title`, `content`, `sender`, `seen`, `reply_id`) VALUES ('%s' , '%s' , '%s', '0', '%s')  ", $title, $content, $sender, 0,  $reply_id);
		
		$do = $this->db->query($query);
				
		if ($do) {return 1;} else {return 0;}
		
	}
	
	
}



/* ========================
==== Tempalating class ====
======================== */
class Template {
    	/**
    	 * The filename of the template to load.
    	 *
    	 * @access protected
    	 * @var string
    	 */
        protected $file;
        
        /**
         * An array of values for replacing each tag on the template (the key for each value is its corresponding tag).
         *
         * @access protected
         * @var array
         */
        protected $values = array();
        
        /**
         * Creates a new Template object and sets its associated file.
         *
         * @param string $file the filename of the template to load
         */
        public function __construct($file) {
            $this->file = $file;
        }
        
        /**
         * Sets a value for replacing a specific tag.
         *
         * @param string $key the name of the tag to replace
         * @param string $value the value to replace
         */
        public function set($key, $value) {
            $this->values[$key] = $value;
        }
        
        /**
         * Outputs the content of the template, replacing the keys for its respective values.
         *
         * @return string
         */
        public function output() {
        	/**
        	 * Tries to verify if the file exists.
        	 * If it doesn't return with an error message.
        	 * Anything else loads the file contents and loops through the array replacing every key for its value.
        	 */
			
			if (!file_exists($this->file)) {
				
				$this->file = '.'.$this->file;
				
				if (!file_exists($this->file)) {
					return "Error loading template file ($this->file).<br />";
				}
            }
			
            $output = file_get_contents($this->file);
            
            foreach ($this->values as $key => $value) {
            	$tagToReplace = "[@$key]";
            	$output = str_replace($tagToReplace, $value, $output);
            }

            return $output;
        }
        
        /**
         * Merges the content from an array of templates and separates it with $separator.
         *
         * @param array $templates an array of Template objects to merge
         * @param string $separator the string that is used between each Template object
         * @return string
         */
        static public function merge($templates, $separator = "\n") {
        	/**
        	 * Loops through the array concatenating the outputs from each template, separating with $separator.
        	 * If a type different from Template is found we provide an error message. 
        	 */
            $output = "";
            
            foreach ($templates as $template) {
            	$content = (get_class($template) !== "Template")
            		? "Error, incorrect type - expected Template."
            		: $template->output();
            	$output .= $content . $separator;
            }
            
            return $output;
        }
}




/* ========================
==== MP3 duration class ===
======================== */
class MP3File
{
    protected $filename;
    public function __construct($filename)
    {
        $this->filename = $filename;
    }
 
    public static function sc_formatTime($current_duration) 
    {
       
        $duration = $current_duration / 1000;
        $hours = floor($duration / 3600);
        $minutes = floor( ($duration - ($hours * 3600)) / 60);
        $seconds = $duration - ($hours * 3600) - ($minutes * 60);
		
		if($hours == '0') {$_hours = '';} else {$_hours = $hours.':';}
		
        return sprintf("%s%02d:%02d", $_hours, $minutes, $seconds);
    }
 
    public static function formatTime($duration) //as hh:mm:ss
    {
        //return sprintf("%d:%02d", $duration/60, $duration%60);
        $hours = floor($duration / 3600);
        $minutes = floor( ($duration - ($hours * 3600)) / 60);
        $seconds = $duration - ($hours * 3600) - ($minutes * 60);
		
		if($hours == '0') {$_hours = '';} else {$_hours = $hours.':';}
		
        return sprintf("%s%02d:%02d", $_hours, $minutes, $seconds);
    }
 
    //Read first mp3 frame only...  use for CBR constant bit rate MP3s
    public function getDurationEstimate()
    {
        return $this->getDuration($use_cbr_estimate=true);
    }
 
    //Read entire file, frame by frame... ie: Variable Bit Rate (VBR)
    public function getDuration($use_cbr_estimate=false)
    {
        $fd = fopen($this->filename, "rb");
 
        $duration=0;
        $block = fread($fd, 100);
        $offset = $this->skipID3v2Tag($block);
        fseek($fd, $offset, SEEK_SET);
        while (!feof($fd))
        {
            $block = fread($fd, 10);
            if (strlen($block)<10) { break; }
            //looking for 1111 1111 111 (frame synchronization bits)
            else if ($block[0]=="\xff" && (ord($block[1])&0xe0) )
            {
                $info = self::parseFrameHeader(substr($block, 0, 4));
                if (empty($info['Framesize'])) { return $duration; } //some corrupt mp3 files
                fseek($fd, $info['Framesize']-10, SEEK_CUR);
                $duration += ( $info['Samples'] / $info['Sampling Rate'] );
            }
            else if (substr($block, 0, 3)=='TAG')
            {
                fseek($fd, 128-10, SEEK_CUR);//skip over id3v1 tag size
            }
            else
            {
                fseek($fd, -9, SEEK_CUR);
            }
            if ($use_cbr_estimate && !empty($info))
            { 
                return $this->estimateDuration($info['Bitrate'],$offset); 
            }
        }
        return round($duration);
    }
 
    private function estimateDuration($bitrate,$offset)
    {
        $kbps = ($bitrate*1000)/8;
        $datasize = filesize($this->filename) - $offset;
        return round($datasize / $kbps);
    }
 
    private function skipID3v2Tag(&$block)
    {
        if (substr($block, 0,3)=="ID3")
        {
            $id3v2_major_version = ord($block[3]);
            $id3v2_minor_version = ord($block[4]);
            $id3v2_flags = ord($block[5]);
            $flag_unsynchronisation  = $id3v2_flags & 0x80 ? 1 : 0;
            $flag_extended_header    = $id3v2_flags & 0x40 ? 1 : 0;
            $flag_experimental_ind   = $id3v2_flags & 0x20 ? 1 : 0;
            $flag_footer_present     = $id3v2_flags & 0x10 ? 1 : 0;
            $z0 = ord($block[6]);
            $z1 = ord($block[7]);
            $z2 = ord($block[8]);
            $z3 = ord($block[9]);
            if ( (($z0&0x80)==0) && (($z1&0x80)==0) && (($z2&0x80)==0) && (($z3&0x80)==0) )
            {
                $header_size = 10;
                $tag_size = (($z0&0x7f) * 2097152) + (($z1&0x7f) * 16384) + (($z2&0x7f) * 128) + ($z3&0x7f);
                $footer_size = $flag_footer_present ? 10 : 0;
                return $header_size + $tag_size + $footer_size;//bytes to skip
            }
        }
        return 0;
    }
 
    public static function parseFrameHeader($fourbytes)
    {
        static $versions = array(
            0x0=>'2.5',0x1=>'x',0x2=>'2',0x3=>'1', // x=>'reserved'
        );
        static $layers = array(
            0x0=>'x',0x1=>'3',0x2=>'2',0x3=>'1', // x=>'reserved'
        );
        static $bitrates = array(
            'V1L1'=>array(0,32,64,96,128,160,192,224,256,288,320,352,384,416,448),
            'V1L2'=>array(0,32,48,56, 64, 80, 96,112,128,160,192,224,256,320,384),
            'V1L3'=>array(0,32,40,48, 56, 64, 80, 96,112,128,160,192,224,256,320),
            'V2L1'=>array(0,32,48,56, 64, 80, 96,112,128,144,160,176,192,224,256),
            'V2L2'=>array(0, 8,16,24, 32, 40, 48, 56, 64, 80, 96,112,128,144,160),
            'V2L3'=>array(0, 8,16,24, 32, 40, 48, 56, 64, 80, 96,112,128,144,160),
        );
        static $sample_rates = array(
            '1'   => array(44100,48000,32000),
            '2'   => array(22050,24000,16000),
            '2.5' => array(11025,12000, 8000),
        );
        static $samples = array(
            1 => array( 1 => 384, 2 =>1152, 3 =>1152, ), //MPEGv1,     Layers 1,2,3
            2 => array( 1 => 384, 2 =>1152, 3 => 576, ), //MPEGv2/2.5, Layers 1,2,3
        );
        //$b0=ord($fourbytes[0]);//will always be 0xff
        $b1=ord($fourbytes[1]);
        $b2=ord($fourbytes[2]);
        $b3=ord($fourbytes[3]);
 
        $version_bits = ($b1 & 0x18) >> 3;
        $version = $versions[$version_bits];
        $simple_version =  ($version=='2.5' ? 2 : $version);
 
        $layer_bits = ($b1 & 0x06) >> 1;
        $layer = $layers[$layer_bits];
 
        $protection_bit = ($b1 & 0x01);
        $bitrate_key = sprintf('V%dL%d', $simple_version , $layer);
        $bitrate_idx = ($b2 & 0xf0) >> 4;
        $bitrate = isset($bitrates[$bitrate_key][$bitrate_idx]) ? $bitrates[$bitrate_key][$bitrate_idx] : 0;
 
        $sample_rate_idx = ($b2 & 0x0c) >> 2;//0xc => b1100
        $sample_rate = isset($sample_rates[$version][$sample_rate_idx]) ? $sample_rates[$version][$sample_rate_idx] : 0;
        $padding_bit = ($b2 & 0x02) >> 1;
        $private_bit = ($b2 & 0x01);
        $channel_mode_bits = ($b3 & 0xc0) >> 6;
        $mode_extension_bits = ($b3 & 0x30) >> 4;
        $copyright_bit = ($b3 & 0x08) >> 3;
        $original_bit = ($b3 & 0x04) >> 2;
        $emphasis = ($b3 & 0x03);
 
        $info = array();
        $info['Version'] = $version;//MPEGVersion
        $info['Layer'] = $layer;
        //$info['Protection Bit'] = $protection_bit; //0=> protected by 2 byte CRC, 1=>not protected
        $info['Bitrate'] = $bitrate;
        $info['Sampling Rate'] = $sample_rate;
        //$info['Padding Bit'] = $padding_bit;
        //$info['Private Bit'] = $private_bit;
        //$info['Channel Mode'] = $channel_mode_bits;
        //$info['Mode Extension'] = $mode_extension_bits;
        //$info['Copyright'] = $copyright_bit;
        //$info['Original'] = $original_bit;
        //$info['Emphasis'] = $emphasis;
        $info['Framesize'] = self::framesize($layer, $bitrate, $sample_rate, $padding_bit);
        $info['Samples'] = $samples[$simple_version][$layer];
        return $info;
    }
 
    private static function framesize($layer, $bitrate,$sample_rate,$padding_bit)
    {
        if ($layer==1)
            return intval(((12 * $bitrate*1000 /$sample_rate) + $padding_bit) * 4);
        else //layer 2, 3
            return intval(((144 * $bitrate*1000)/$sample_rate) + $padding_bit);
    }
}	