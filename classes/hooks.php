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


	
function load_hooks() {
	
	global $CONF, $db;
	
	
	$query_plugins = $db->query(sprintf("SELECT * FROM `hooks`  "));
	
	if ($query_plugins->num_rows > 0 ) {
		
		while ( $result = $query_plugins->fetch_assoc() ) {
							
			$rows[] = $result;
						
		}
		
		return $rows;
	
	}
}

	
function update_hook($link) {
	
	global $db;
	
	$status = get_plugin($link);
	
	if ($status['status'] == 0) {$set = 1;} else {$set = 0;}
	
	$query = sprintf("UPDATE `plugins` SET `status` = '%s' WHERE `link` = '%s' ", $set, $link );
	
	$do = $db->query($query);
	
	if ($do) {return 1;} 
		
}

function get_hook_link($link = null) {
	
	global $db;
	
	$query_plugins = $db->query(sprintf("SELECT * FROM `plugins` WHERE `link` = '%s' LIMIT 1", $db->real_escape_string($link)));
	
	$result = $query_plugins->fetch_assoc();
	
	return $result;
	
}

function get_hook_id($id) {
	
	global $db;
	
	$query_plugins = $db->query(sprintf("SELECT * FROM `hooks` WHERE `id` = '%s' LIMIT 1", $db->real_escape_string($id)));
	
	$result = $query_plugins->fetch_assoc();
	
	return $result;
	
}

function display_hooks_position($page , $template , $position) {
	
	global $db , $CONF , $Setting, $member;
	
	$output = '';
	
	$query_plugins = $db->query(sprintf("SELECT * FROM `hooks` WHERE `template` = '%s' AND `position` = '%s' ORDER by `order` " , $template , $position));
	
	if ($query_plugins->num_rows > 0) {
		
		while ( $result = $query_plugins->fetch_assoc() ) {
							
			$rows[] = $result;
						
		}
		
		// SET admin class
		$FullAdmin = new FullAdmin;
		
		$FullAdmin->db = $db;
		
		$i = -1;
		
		foreach ($rows as $key => $row) {
			$i++;
			if ($row['publish'] == 1) {
		
				$pages = unserialize($row['pages']);
			
				if (in_array($page , $pages) || in_array( 'ALL', $pages)) {
					
					$plugin = load_plugin_files($row['plugin']);
					
					$PLUGIN_NAME = $row['plugin'];
					$PLUGIN_PATH = $plugin;
					$PLUGIN_ID = $row['id'];
					
					$output[$i]['title'] = $row['title'];
					$output[$i]['content'] = include($plugin.'/main.php');
					
				}
			}
		}
		return $output;
	}
}



function list_plugins_array($query) {
		
	global $CONF, $db;
	
	$FullAdmin = new FullAdmin;
	$FullAdmin->db = $db;
	$Setting = $FullAdmin->siteSetting();
	
	$output = ''; 
	
	if (isset($query['title'])) {unset($query['title']);}
	
	if (is_array($query)) {
		
		foreach  ($query as $plugin) {
			
			$output .= $plugin['content'];
		}
		
	} else {
		$output .= $query;
	}
	
	return $output;

}



function load_site_plugins($query, $tpl) {
		
	global $CONF, $db;
	
	$FullAdmin = new FullAdmin;
	$FullAdmin->db = $db;
	$Setting = $FullAdmin->siteSetting();
	
	$output = ''; 
	
	if (is_array($query)) {
		
		
		foreach  ($query as $key => $value) {
			
			$code = _plugins_shortcode($value['content']);
			
			$tpl->set("url", $CONF['url']);
			$tpl->set("title", $value['title'] );
			$tpl->set("content", $code );
			$output .= $tpl->output();
		
		}
	}
	return $output;
}






function _plugins_shortcode_filter($val) {
	
	//
	// Plugin shortcode 
	// 
	// [--@plugin= Plugin Name --@id= Plugin ID --]
	//
	
	preg_match_all("|\[--@plugin=(.*)\--@id=(.*)\--](.*)</[^>]+>|U", $val, $matches, PREG_PATTERN_ORDER);	
	
	return $matches;
	
}


function _plugins_shortcode($val) {
	
	global  $db, $CONF;
	
	//
	// Plugin shortcode 
	// 
	// [--@plugin= Plugin Name --@id= Plugin ID --]
	//
	
	$matches = _plugins_shortcode_filter($val);	
	
	if (!empty($matches[1][0])) {
				
		$code_val = $matches[0][0];
		
		$hook = load_plugin_files($matches[1][0]);
					
		$PLUGIN_NAME = $matches[1][0];
		
		$PLUGIN_PATH = $hook;
		
		$PLUGIN_ID = $matches[2][0];
					
		$code = include($hook.'/main.php');
					
		$content = str_replace($code_val, $code, $val);
			
	}	else {
		
		$content = $val;
	
	}		
	return  $content;
}
