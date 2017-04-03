<?php

//======================================================================\\

// CMS			                        								\\

//----------------------------------------------------------------------\\

// Copyright (c) 2015 HiMero.net 					    				\\

//----------------------------------------------------------------------\\

// All rights reserved.						 		    				\\

//----------------------------------------------------------------------\\

// http://www.HiMero.net/               								\\

//======================================================================\\
	
require_once("classes.php");




function add_head_plugins_css($type = null, $key = null) {
	
	if (isset($key)) {
		return $key();
	} else {
		return $key;
	}
}

function GetCount($type) {

	global $db;
	
	$type = $db->real_escape_string($type);
	
	if ($type == 'members') {
		$query = $db->query(sprintf("SELECT count(*) as total FROM `members` "));
	} elseif ($type == 'comments' ) {
		$query = $db->query(sprintf("SELECT count(*) as total FROM `media_comments` "));	
	} elseif ($type == 'media' ) {
		$query = $db->query(sprintf("SELECT count(*) as total FROM `media` "));	
	} elseif ($type == 'views' ) {
		$query = $db->query(sprintf("SELECT count(*) as total FROM `media_views` "));	
	}
	
	$row = $query->fetch_assoc();
	
	$output = $row['total'];
	
	return $output;
}

function getTemplates() {

		$output = '';
		//path to templates directory 
		$directory = "templates/";
		 
		//get all folders in specified directory
		$files = glob($directory . "*");
		 
		//print each folder name
		foreach($files as $file)
		{
			
			//check to see if the file is a templates/[templatename]
			if(is_dir($file))
			{
				$folder = str_replace($directory, '', $file);
				$output .= '<option value="'.$folder.'" >'.$folder.' </option>';
			}
		}
	return $output;	
}

function getLangs() {

		//path to templates directory 
		$directory = "classes/langs/";
		 
		//get all folders in specified directory
		$files = glob($directory . '*.php', GLOB_BRACE);
		 
		//print each folder name
		foreach($files as $file)
		{
			
			//check to see if the file is a templates/[templatename]
			if(!is_dir($file))
			{
				$folder = str_replace($directory, '', $file);
				$folder = str_replace('.php', '', $folder);
				$output .= '<option value="'.$folder.'" >'.$folder.' </option>';
			}
		}
	return $output;	
}





function Languages($url, $ln = null, $type = null) {

	/* //////////////////////////////////
	@Params Search for Availiable langs
	///////////////////////////////////*/
	
	$LangsDir = './classes/langs/';
	
	$language = glob($LangsDir . '*.php', GLOB_BRACE);

	if($type == 1) {
	
		foreach($language as $lang) {
		
			// Get Language path
			$path = pathinfo($lang);
			
			// Add the filename into $available array
			$available .= '<a href="'.$url.'/index.php?lang='.$path['filename'].'">'.ucfirst(strtolower($path['filename'])).'</option>  ';
			
		}
		
		return substr($available, 0, -3);
	
	} else {
		// Set the cookie
		$lang = 'english'; // DEFAULT LANGUAGE
		
		$LangsDir = './classes/langs/';
		
		if(isset($_GET['lang'])) {
		
			if(in_array($LangsDir.$_GET['lang'].'.php', $language)) {
			
				$lang = $_GET['lang'];
				
				setcookie('lang', $lang, time() +  (10 * 365 * 24 * 60 * 60)); // Expire in one month
			
			} else {
			
				setcookie('lang', $lang, time() +  (10 * 365 * 24 * 60 * 60)); // Expire in one month
			
			}
			
		} elseif(isset($_COOKIE['lang'])) {
		
			if(in_array($LangsDir.$_COOKIE['lang'].'.php', $language)) {
			
				$lang = $_COOKIE['lang'];
			
			}
		
		} else {
		
			setcookie('lang', $lang, time() +  (10 * 365 * 24 * 60 * 60)); // Expire in one month
		
		}

		if(in_array($LangsDir.$lang.'.php', $language)) {
		
			return $LangsDir.$lang.'.php';
	
		}
	}
}

function viewADS() {
	global $db;
	
	////////////////////////
	// Returns 3 ADS
	////////////////////////
	// 1 - Top AD
	// 2 - Side AD
	// 3 - Bottom AD
	////////////////////////////
	
	$query = sprintf("SELECT * from `ads`");
	$result = $db->query($query);
	
	if ($result->num_rows > 0) {
	
		while ($Settings = $result->fetch_assoc()) {
			return $Settings;    
		}
	}
}



function NotePopup($msg, $type) {
	
	if ($type == '1') {
		$text_color = 'style="color:#333"';
		$state = 'alert-success';
		$alert = '<script>alertify.success("'.$msg.'");</script>';
	} elseif ($type == '2') {
		$text_color = 'style="color:#fff !important"';
		$state = 'alert-danger';
		$alert = '<script>alertify.error("'.$msg.'");</script>';
	} elseif ($type == '3') {
		$text_color = 'style="color:#fff"';
		$state = 'alert-info';
		$alert = '<script>alertify.warning("'.$msg.'");</script>';
	}
	
	/////////////////////////
	// Returns Notifications 
	/////////////////////////
	return '
        <div class="alert '.$state.'"  '.$text_color.'>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          '.$msg.' '.$alert.'
        </div>';
	
}

function ago($i){
    
	$m = time()-$i; $o='just now';
    
	$t = array('year'=>31556926,'month'=>2629744,'week'=>604800, 'day'=>86400,'hour'=>3600,'minute'=>60,'second'=>1);
    
	foreach($t as $u=>$s){
		
		if($s<=$m){$v=floor($m/$s); $o="$v $u".($v==1?'':'s').' ago'; break;}
	}
    
	return $o;
}

/* =================================
==== Dropdown selection function ===
================================= */
function list_options_select($query, $current = null) {
	
	$output = '';
	
	if (isset($query)) {

		if (is_array($current)) {

			foreach ($query as $row) {
				
				foreach ($current as $curr) {
					
					if ($row['id'] == $curr['id']) {
						$output .= '<option value="'.$row['id'].'" selected="selected"> '.$row['title'].'</option>';
					} 
					
				}	

			}		
							
		} else {

			foreach ($query as $row) {
				
				if ($current ==  $row['id']) {
					$cur = 'selected="selected"';
				} else {
					$cur = '';

				}		

				$output .= '<option value="'.$row['id'].'" '.$cur.'> '.$row['title'].'</option>';
			}
		}
	}
		
	return $output;
}
	


/* ============================
==== Selected list function ===
============================ */
function list_options_active($id , $query, $current = null) {
		
	$output = '';
			
	if (isset($query) && is_array($query)) {
			
		foreach ($query as $row) {
			
			if ($current != $row['id']) {
					
				if ($row['id'] == $id) { $active = select_active($id);} else {$active = '';}
				
				$output .= '<option value="'.$row['id'].'" '.$active.'> '.$row['title'].'</option>';
			}
		}
			
	}
		
	return $output;
}
	

/* ============================
==== multi-select  function ===
============================ */
function multi_select_active($id , $query) {
		
	$output = '';
			
	if (isset($query)) {
			
		foreach ($query as $row) {
				
			if (in_array($row['id'], $id)) { $active = select_active($id);} else {$active = '';}
			
			$output .= '<option value="'.$row['id'].'" '.$active.'> '.$row['title'].'</option>';
		}
			
	}
		
	return $output;
}
	

/* ============================
==== Check status function ====
============================ */
function select_active($value) {
	
	if (isset($value) && !empty($value)) {$output = 'selected';} else {$output = ' ';}
	
	return $output;
	
}

function option_toChecked($value) {
	
	if (isset($value) && !empty($value) ) {$output = 'checked';} else {$output = ' ';}
	
	return $output;
	
}

function option_toNumber($value) {
	
	if ($value == 'on') {$output = '1';} else {$output = '0';}
	
	return $output;
	
}




/* ============================
==== Get current template =====
============================ */
function checkTemplate($value , $rows) {
	global $db;
	
	$output = '';
	
	if (!empty($rows)) {
		
		foreach ($rows as $row) {
		
			if ($value == $row['link']) {$select = 'selected=""';} else {$select = ' ';}
			
			$output .= '<option '.$select.' value="'.$row['link'].'"> '.$row['name'].' </option>';

		}	
		return $output;		
	}
}




/* ============================
==== Check current select =====
============================ */
function check_current_select($value , $query , $field) {
	
	global $db;
	
	$output = '';
	
	$layouts = str_replace(' ', '', $query[$field]);
	
	$rows = explode(",", $layouts);
	
	foreach ($rows as $row) {
		
		if ($value == $row) {$select = 'selected=""';} else {$select = ' ';}
		
		$output .= '<option value="'.$row.'" '.$select.'> '.$row.' </option>';
	
	}
			
	return $output;
	
}




/* ==========================
==== Retutn page css data ==
========================== */
$load_all_plugins_css = array();
	
function load_all_plugins_css() {
	global $load_all_plugins_css , $CONF;
	
	$output =  '';
	
	$files = array_unique($load_all_plugins_css);

	foreach ($files as $file) {
		
		$file = str_replace('./' , '' , $file);
		
		$output .= '<link href="'.$CONF['url'].$file.'" rel="stylesheet"> 
	';
	}
	
	return $output ;
}


function add_head_css($type , $file) {

	global $load_all_plugins_css;
	
	array_push($load_all_plugins_css , $file);
	
	if (in_array($file , $load_all_plugins_css)) {
	
		unset($load_all_plugins_css[$file]);
		
	}
	
}



/* ===========================
==== Retutn page JS plugins ==
=========================== */
$load_all_plugins_js = array();
	
function load_all_plugins_js() {
	global $load_all_plugins_js, $CONF;
	
	$output =  '';
	
	$files = array_unique($load_all_plugins_js);

	foreach ($files as $file) {
		
		$file = str_replace('./' , '' , $file);
		
		$output .= '<script type="text/javascript" src="'.$CONF['url'].$file.'" ></script>
	';
	}
	
	return $output ;
	
}


function add_head_js($type , $file) {

	global $load_all_plugins_js;
	
	array_push($load_all_plugins_js , $file);
	
	if (in_array($file , $load_all_plugins_js)) {
	
		unset($load_all_plugins_js[$file]);
		
	}
	
}




/* ===========================
==== Retutn page JS plugins ==
=========================== */
$load_footer_plugins_js = array();
	
function pages_footer_js() {
	global $load_footer_plugins_js, $CONF;
	
	$output =  '';
	
	$files = array_unique($load_footer_plugins_js);

	foreach ($files as $file) {
		
		$file = str_replace('./' , '' , $file);
		
		$output .= '<script type="text/javascript" src="'.$CONF['url'].$file.'" ></script>
	';
	}
	
	return $output ;
	
}


function add_footer_js($type , $file) {

	global $load_footer_plugins_js;
	
	array_push($load_footer_plugins_js , $file);
	
	if (in_array($file , $load_footer_plugins_js)) {
	
		unset($load_footer_plugins_js[$file]);
		
	}
	
}


/* ==========================
==== Retutn page head data ==
========================== */
function pages_head_meta($page_array , $type) {
	
	global $CONF;
	
	$FullAdmin = new FullAdmin();
	$Setting = $FullAdmin->siteSetting();
	
	if (isset($page_array)) {
		
		$title = $page_array['title'];
		$desc = $page_array['desc'];
		$keywords = $page_array['keywords'];
		
		if (isset($page_array['url'])) { 
			$url = $CONF['url'] . $page_array['url'];
		} else {
			$url = $CONF['url'];
		}		
		
	} else {
	
		$title = $Setting['sitename'];
		$desc = $Setting['desc'];
		$keywords = $Setting['keywords'];
		$url = $CONF['url'];
		
	}
	
	return  ' 
	<!--Content Type UTF-8-->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

	<!--IE Compatibility modes-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<!--Mobile first-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
   
	<title>'.$title.' </title>
	<link rel="shortcut icon" type="image/png" href="'.$CONF['url'].'favicon.png"/>
	<meta name="description" content="'.$desc.'" >
	<meta name="keywords" content="'.$keywords.'" >
	
	<!-- Twitter Card data -->
	<meta name="twitter:card" value="'.$desc.'">
	<meta name="twitter:site" content="'.$title.'">
	<meta name="twitter:title" content="'.$title.'">
	<meta name="twitter:description" content="'.$desc.'">
	<meta name="twitter:image:src" content="'.$CONF['url'].'favicon.png">
	
	<!-- Schema.org markup for Google+ -->
	<meta itemprop="name" content="'.$title.'">
	<meta itemprop="description" content="'.$desc.'">
	<meta itemprop="image" content="'.$CONF['url'].'favicon.png"> 

	<!-- Open Graph data -->
	<meta property="og:title" content="'.$title.'" />
	<meta property="og:url" content="'.$url.'" />
	<meta property="og:image" content="'.$CONF['url'].'favicon.png" />
	<meta property="og:description" content="'.$desc.'" /> 
	<meta property="og:site_name" content="'.$title.'" />
	'    ;
}


/* =========================
==== Retutn head CSS & JS ==
========================= */
function pages_head($page_array , $type) {
	
	$load_css = load_all_plugins_css();
	
	$load_js = load_all_plugins_js();
	
	return  
	$load_css.'
  '.$load_js.'
	'    ;
}


/* ==========================
==== Retutn page footer    ==
========================== */
function pages_footer($page_array , $type) {
	
	global $CONF;
	
	$FullAdmin = new FullAdmin();
	$Setting = $FullAdmin->siteSetting();
	
	if (isset($page_array)) {
		
		$title = $page_array['title'];
		$desc = $page_array['desc'];
		$keywords = $page_array['keywords'];
		
	} else {
	
		$title = $Setting['sitename'];
		$desc = $Setting['desc'];
		$keywords = $Setting['keywords'];
		
	}
	
	$load_js = pages_footer_js();
	
	return $load_js.'
	';
}




/* ==================================
==== Delete media file (photos) =====
================================== */
function delete_media($id) {
	
	global $CONF;
	
	$dir = $_SERVER['DOCUMENT_ROOT'] . $CONF['path'] . 'media/photos/';
	
	$file = $dir.$id;
	
	if (file_exists($file)) {
	
		if (unlink($file)) {
			return 1;
		} else {
			return 'File can not be deleted';
		}
		
	} else {
		return 'File not found';
	}
		
}




/* =============================
==== Require media library =====
============================= */
function media_library() {
	
	global $CONF;
	
	$output = '';
	
	$dir = $_SERVER['DOCUMENT_ROOT'] . $CONF['path'] . 'media/photos/';
		
	if ($handle = opendir($dir)) {
			
		while (false !== ($entry = readdir($handle))) {
				
			if ($entry != "." && $entry != ".." && $entry != "index.html" && $entry != "thumbs" && $entry != "premium") {
				
				$output .= '<li id="media_lib_id" class="li_'.$entry.'" data-id="'.$entry.'" data-url="'.$CONF['url'].'image.php?src='.$entry.'&w=200&h=150&img=pic"><img src="'.$CONF['url'].'image.php?src='.$entry.'&w=100&h=100&img=pic" />
				<a id="delete_photo" data-id="'.$entry.'"><i class="fa fa-times"></i></a>
				</li>';
			}
		}
		closedir($handle);
	}
	
	return $output;
}



/* ==========================
==== Upload media files =====
========================== */
function upload_media($file, $type = null) {
	
	global $CONF, $Setting;
	
	if ($type == '1') {$folder = 'audio'; $all_ext = $Setting['audio_ext'];} 
	if ($type == '1_1') {$folder = 'audio/thumbs'; $all_ext = $Setting['pic_ext'];} 
	if ($type == '1_2') {$folder = 'audio/premium'; $all_ext = $Setting['audio_ext'];} 
	if ($type == '2') {$folder = 'videos'; $all_ext = $Setting['videos_ext'];} 
	if ($type == '2_1') {$folder = 'videos/thumbs'; $all_ext = $Setting['pic_ext'];} 
	if ($type == '2_2') {$folder = 'videos/premium'; $all_ext = $Setting['videos_ext'];} 
	if ($type == '3' || $type == null) {$folder = 'photos'; $all_ext = $Setting['pic_ext'];} 
	if ($type == '3_1') {$folder = 'photos/thumbs'; $all_ext = $Setting['pic_ext'];} 
	if ($type == '3_2' ) {$folder = 'photos/premium'; $all_ext = $Setting['pic_ext'];} 
	if ($type == '4') {$folder = 'channels'; $all_ext = $Setting['pic_ext'];} 
	if ($type == '5') {$folder = 'ads'; $all_ext = $Setting['pic_ext'];} 
	if ($type == '6') {$folder = 'albums'; $all_ext = $Setting['pic_ext'];} 
	
	if ( isset($file['size']) && !empty($file) && $file['size'] > 0 ) { 
					
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		
		if ($file  && strpos($all_ext, strtolower($ext)) !== false  )
		{
			if ($file["error"] > 0)
			{
				$output = "Error : Return Code: " . $file["error"] . "<br />";
			}
			else
			{
				$temp = explode(".",$file["name"]);
				$photoname = mt_rand().'_'.mt_rand().'_'.mt_rand() . '.' .end($temp);
				if (move_uploaded_file($file["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $CONF['path'] . '/media/'.$folder.'/' . $photoname)) {
									
					$output = $photoname;
									
				}	
			}
		}
		else
		{
			$output =  'Error file extension not allowed';
		}
	
	} 	else {
		
		$output = 'Error no file selected'; 
	}
	
		return $output;
					
}



/* ====================================
==== Convert media type to number =====
==================================== */
function MediaNameToNumber($type) {
	$output = '';
	if ($type == 'music') {
		$output = '1';
	} elseif ($type == 'video' || $type == 'videos') {
		$output = '2';
	} elseif ($type == 'photo' || $type == 'photos') {
		$output = '3';
	}
	return $output;

}




/* ====================================
==== Convert media type to number =====
==================================== */
function MediaNameToNumberImg($type) {
	
	if ($type == 'music') {
		$output = 'm3';
	} elseif ($type == 'video') {
		$output = 'm1';
	} elseif ($type == 'photo') {
		$output = 'm2-t';
	}
	return $output;

}



/* ==========================
==== Filter html tags   =====
========================== */
function filter_html_tags($content) {

	$output = strip_tags($content);
	
	return $output;

}



/* ==========================
==== Autoload Plugins      ==
========================== */
function autoload_plugins()
{
	global $CONF, $db, $Setting, $payment_class, $member;
	
	$plugins_list = queryPlugins_list();
	
	foreach ($plugins_list as $row) 
	{
		$PLUGIN_PATH = str_replace('./', '', $row['path']);
		$PLUGIN_NAME = $row['name'];
		$PLUGIN_TYPE = $row['type'];
		$PLUGIN_ID = $row['id'];
		
		if ($handle = opendir($PLUGIN_PATH)) {

			while (false !== ($entry = readdir($handle))) {
				
				if ($entry == "autoload.php" ) {
					require_once($PLUGIN_PATH.'/' . $entry);
				}
			}
		}
	}
	
}

/* ==========================
==== Payment Plugins      ==
========================== */
function payment_plugins($load, $type = null)
{
	global $CONF, $db, $Setting, $member, $cart_class;
	$return  = '';  
	
	if (!empty($type)) {
		
		$plugins_list = queryPlugins_type($type);
		
		if (is_array($plugins_list)) {		
			
			foreach ($plugins_list as $row) 
			{
				$PLUGIN_PATH = str_replace('./', '', $row['path']);
				$PLUGIN_NAME = $row['name'];
				$PLUGIN_TYPE = $row['type'];
				$PLUGIN_ID = $row['id'];
				
				if ( $handle = opendir($PLUGIN_PATH)) {
				
					while (false !== ($entry = readdir($handle))) {
					
						if ($entry == $load . ".php" ) {
							$return .=  include($PLUGIN_PATH.'/'.$load.'.php');
						}
					}
				}				
			}
		}
	}
	return	$return;
}

/* ==========================
==== Payment Plugins      ==
========================== */
function payment_process($load, $type = null)
{
	global $CONF, $db, $Setting, $member, $cart_class;
	$return  = '';  
	
	if (!empty($type)) {
		
		$row = get_plugin($type);
		
		if (is_array($row)) {		
				
			$PLUGIN_PATH = str_replace('./', '', $row['path']);
			$PLUGIN_NAME = $row['name'];
			$PLUGIN_TYPE = $row['type'];
			$PLUGIN_ID = $row['id'];
			
			if ( $handle = opendir($PLUGIN_PATH)) {
			
				while (false !== ($entry = readdir($handle))) {
				
					if ($entry == $load . ".php" ) {
						
						$return =  include($PLUGIN_PATH.'/'.$load.'.php');
					}
				}
			}				
		}
	}
	return	$return;
}

/* ==============================
==== Delete records from Tables==
============================== */
function deleteTblParams($tbl, $params) {
	
	global $db;
	
	$query = $db->query(sprintf("DELETE FROM %s WHERE %s ", $tbl, $params));
	
	if ($result) { return 1; } else { return '0'; }
	
}


/* ==============================
==== Query Tables Multi params ==
============================== */
function queryTblParams($tbl, $params) {
	
	global $db;
	
	$query = $db->query(sprintf("SELECT * FROM %s WHERE %s ", $tbl, $params));
	
	$result = $query->fetch_assoc();
	
	if (!empty($result)) { return $result; } else { return '0'; }
	
}

/* ==========================
==== Query Tables         ==
========================== */
function queryTbl($tbl, $id) {
	
	global $db;
	
	$query = $db->query(sprintf("SELECT * FROM %s WHERE `id` = '%s' ", $tbl, $id));
	
	$result = $query->fetch_assoc();
	
	if (!empty($result)) { return $result; } else { return '0'; }
	
}

/* ==========================
==== Update Tables         ==
========================== */
function updateTblVal($tbl, $id, $val) {
	
	global $db;
	
	$query = $db->query(sprintf("UPDATE %s SET %s WHERE `id` = '%s' ", $tbl, $val, $id));
	
	if ($query) { return '1'; } else { return '0'; }
	
}
/* ==========================
==== Add new comment       ==
========================== */
function addNewComment($post, $sender) {

	global $db;
	
	$FullAdmin = new FullAdmin;
	$FullAdmin->db = $db;
	
	$comment = $db->real_escape_string($post['comment']);
	$type = $db->real_escape_string($post['type']);
	$type_id = $db->real_escape_string($post['type_id']);
	
	$user_data = $FullAdmin->GetMember_by_name($sender);
	
	if ($user_data) {
		
		$query = $db->query(sprintf("INSERT INTO `comments` (`comment`, `user`, `type`, `type_id`) VALUES ('%s', '%s', '%s', '%s') ", $comment, $user_data['id'], $type, $type_id ));
	
		if ($query) {
			
			$check = $db->query(sprintf("SELECT * FROM %s WHERE `id` = '%s' ", $type, $type_id));
			$check_result = $check->fetch_assoc();
			if ($type == 'media') {$user = $check_result['author'];} elseif ($type == 'pages') {$user = '';} else {$user = $check_result['user'];}
			
			$set = updateTblVal($type, $type_id, ' `comments` = `comments` + 1 ');
			
			if ($set) {$output = 1;} else {$output = $set;}
			
			if ($type !== 'pages') {
				add_notification($user, '2', $type_id, $type);
			}
			
		} else {
			
			$output = $db->error;
		}
	}
	
	return $output;
	
}

//-----------------
// Quick functions
//-----------------
function GetMember($name) {
	global $db;
	
	$name = $db->real_escape_string($name);
	
	if (!empty($name) && $name !== '0') {
	
		$query = sprintf("SELECT * FROM `members` WHERE `name` = '$name' ");
		
		$result = $db->query($query);
		
		$row = $result->fetch_array();
			
		if($result == null){
				
			return '1';
					
		} else {
			return $row;
		}
	}
}

function GetMemberID($id) {
	global $db;
	
	$id = $db->real_escape_string($id);
	
	if (!empty($id) && $id !== '0') {
	
		$query = sprintf("SELECT * FROM `members` WHERE `id` = '$id' ");
		
		$result = $db->query($query);
		
		$row = $result->fetch_array();
			
		if($result == null){
				
			return '1';
					
		} else {
			return $row;
		}
	}
}

function NotFound() {
	global $CONF;
	
	return '
	<div class="wrapper">
		<div class="ui negative icon message">
			<i style="font-size: 40px; padding-right: 20px;" class="icon-lock pull-left"></i>	
			<div class="content">
				<div class="header">
				We are sorry we can not find that page
				</div>
				<p>Back to <a href="'.$CONF['url'].'">home</a> and try again</p>
			</div>
		</div>
	</div>';
	
}


function NotItems($title) {
	global $CONF;
	
	return '
	
		<div class="ui  icon ">	
			<p>No '.$title.' here yet</p>
		</div>
	';
	
}



function media_type($type) {
	
	if ($type == '1') { $return = 'm3'; }
	if ($type == '2') { $return = 'm1'; }
	if ($type == '3') { $return = 'm2-t'; }
	if ($type == '4') { $return = 'pic'; }
	if ($type == '5') { $return = 'ch'; }
	if ($type == '6') { $return = 'al'; }
	return $return; 
}


function LoginFirst() {
	
	return '
	
		<div class="ui negative icon message">
			<i style="font-size: 22px; padding-right: 10px;" class="icon-lock pull-left"></i>	
			<div class="content">
				<p>Please login First</p>
			</div>
		</div>';
	
}

function viewMessage($title, $type = null) {
	
	if (!empty($type)) { $class="positive";} else {$class="negative";}
	
	return '
	
		<div class="ui '.$class.' icon message">
			<i style="font-size: 22px; padding-right: 10px;" class="icon-question pull-left"></i>	
			<div class="content">
				<p>'.$title.'</p>
			</div>
		</div>';
	
}


//---------------------------------
// Set $setting variable as global
//---------------------------------
$FullAdmin = new FullAdmin(); $Members = new Members();
$FullAdmin->db = $db; $Members->db = $db;

$Setting = $FullAdmin->siteSetting();


include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."classes/comments.php");
include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."classes/web-pages.php");			
include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."classes/templates.php");
include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."classes/plugins.php");
include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."classes/hooks.php");
include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."classes/upload.php");
include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."classes/media.php");
include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."classes/user.php");
include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."classes/post.php");
include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."classes/search.php");
include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."classes/chat.php");
include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."classes/actions.php");
include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."classes/notifications.php");
include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."classes/cart.php");
include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."classes/class.php");

$action_class = new action(); $user_class = new user(); $note_class = new notifications; $chat_class = new chat;  $media_class = new media;   $cart_class = new cart; 
$action_class->db = $db;  $user_class->db = $db;  $note_class->db = $db;   $chat_class->db = $db;  $media_class->db = $db;  $cart_class->db = $db;
	
if(isset($_SESSION['membername']) || !empty($_SESSION['membername'])) {		
	$member = GetMember($_SESSION['membername']);
} else {
	$member = '';
}
		
include($_SERVER['DOCUMENT_ROOT'].$CONF['path'] ."templates/".$Setting['template']."/functions.php");