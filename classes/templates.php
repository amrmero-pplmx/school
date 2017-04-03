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


function loadTemplates() {
	
	global $CONF, $db;
	
	/*///////////////////////
	 Setting data 
	///////////////////////*/
	$FullAdmin = new FullAdmin();
	$FullAdmin->db = $db;
	$Setting = $FullAdmin->siteSetting();
	
	$output = '';
	
	$dir = 'templates/';
	
	if ($handle = opendir($dir)) {

		while (false !== ($entry = readdir($handle))) {

			if ($entry != "." && $entry != ".." && $entry != "index.html") {
				
				$path = $dir . $entry;
				
				include($dir.'/'.$entry.'/info.php');
				
				$query_plugins = queryTemplates($entry);
				
				if (isset($query_plugins) && $query_plugins > 0) {
					$found = '1';
				} else {
					
					$query = $db->query(sprintf("INSERT INTO `templates` (`name`,  `desc`, `link`, `version`, `positions`, `layouts`) VALUES  ('%s','%s','%s','%s','%s','%s' ) " , 
					$db->real_escape_string($template_name), $db->real_escape_string($template_desc), $db->real_escape_string($entry), $db->real_escape_string($template_version), $db->real_escape_string($template_positions), $db->real_escape_string($template_layouts)));
					
					if ($query) { $found = '1'; } else {echo $db->error;}
					
				}
				
				if ($found == '1') { 
					
					$rows = queryTemplates($entry);
				
					foreach ($rows as $row) {
						
						if ($Setting['template'] == $row['link']) { 
							$status = '<i class="icon-ok"></i>';
						} else {
							$status = '<a id="set_default" data-title="'.$row['link'].'" class="btn btn-success" > <i class="icon-home"></i> </a>';
						}
						
						$output .= '
							<tr>
							  <td>
								<span class=""> '.$row['name'].' </span> 
							  </td>
							  <td>
								'.$row['desc'].'
							  </td>
							  <td>
								<img class="plugin-thumbnail" src="'.$CONF['url'] . $dir .'/'. $row['link'] .'/'. 'preview.png" />
							  </td>
							  <td>
								'.$status.'
							  </td>
							</tr>';
					}
				}
			}
		}

		closedir($handle);
	}

	return $output;
}



function queryTemplates($link = null) {
	
	global $db;
	
	$rows = '';
	
	$query_plugins = $db->query(sprintf("SELECT * FROM `templates` WHERE `link` = '%s' ", $db->real_escape_string($link)));
	
	while ( $result = $query_plugins->fetch_assoc() ) {
						
		$rows[] = $result;
					
	}
	
	return $rows;
	
}




function get_Templates_array() {
	
	global $db;
	
	$rows = '';
	
	$query = $db->query(sprintf("SELECT * FROM `templates` "));
	
	if ($query->num_rows > 0 )
	{	
		while ( $result = $query->fetch_assoc() ) {
							
			$rows[] = $result;
						
		}
	}		
	return $rows;
	
}

function query_Templates_select() {
	
	global $db;
	
	$output = '';
	
	$rows = get_Templates_array();
	
	foreach ($rows as $row) {
		
		$output .= '<option value="'.$row['link'].'"> '.$row['name'].' </option>';
	
	}
			
	return $output;
	
}

function query_template_layouts() {
	
	global $db;
	
	$output = '';
	
	$query = query_current_template();
	$layouts = str_replace(' ', '', $query['layouts']);
	$rows = explode(",", $layouts);
	
	foreach ($rows as $row) {
		
		$output .= '<option value="'.$row.'"> '.$row.' </option>';
	
	}
			
	return $output;
	
}


function query_current_template() {
	
	global $db;
	
	$query = $db->query("SELECT * FROM `templates` , `settings` WHERE `settings`.`template` = `templates`.`link` ");
	
	if ($query->num_rows > 0) {
	
		while ( $result = $query->fetch_assoc() ) {
							
			$rows = $result;
						
		}
		
		return $rows;
	}
}

function get_template_position($current = null) {
	
	global $db;
	
	$output = '';
	
	$query = query_current_template();
	
	$position_array = str_replace(' ', '', $query['positions']);
	
	$positions = explode(",", $position_array);
	
	foreach ($positions as $position) {
		if ($position == $current) {$active = 'selected';} else {$active = '';} 
		$output .= '<option value="'.$position.'" '.$active .'> '.$position.' </option>';
		
	}
	
	return $output;
	
}
