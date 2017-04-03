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


function pages_query($status = null) {
	
	global $db;

	if (isset($status)) {$get_active = ' WHERE publish = '. $status . ' ';} else {$get_active = ' ';}
	
	$query = $db->query(sprintf("SELECT * FROM `pages` %s " , $get_active));
	
	if ($query->num_rows > 0 ) {
	
		while ( $result = $query->fetch_assoc() ) {
							
			$rows[] = $result;
						
		}
	}

	return $rows;
	
}

function pages_list_select() {

	$output = '';	
	
	$rows = pages_query('1');
	
	if (!empty($rows)) {	
	
		foreach ($rows as $row) {
		
			$output .= '<option value="'.$row['id'].'"> '.$row['title'].' </option>';
		}
	}
	
	return $output;
} 

function loadPages() {
	
	global $CONF, $db;
	
	$rows = pages_query(1);
	
	if (isset($rows)) {	
	
		return $rows;
	
	}	else {
	
		return 'No pages found';
	
	}
	
}


function pages_query_per_template($template, $status = null) {
	
	global $db;

	$rows = '';
	
	if (isset($status)) {$get_active = ' AND publish = '. $status . ' ';} else {$get_active = ' ';}
	
	$query = $db->query(sprintf("SELECT * FROM `pages` WHERE `template` = '%s'  %s ", $template , $get_active));
	
	if ($query->num_rows > 0 ) {
	
		while ( $result = $query->fetch_assoc() ) {
							
			$rows[] = $result;
						
		}
	}

	return $rows;
	
}


function pages_query_per_prefix($prefix) {
	
	global $db;

	$rows = '';
	
	$prefix = $db->real_escape_string($prefix);
	
	$query = $db->query(sprintf("SELECT * FROM `pages` WHERE `prefix` = '%s' ", $prefix));
	
	if ($query->num_rows > 0 ) {
	
		while ( $result = $query->fetch_assoc() ) {
							
			$rows = $result;
						
		}
		return $rows;
	}

	
}

function page_query_home() {
	
	global $db;

	$rows = '';
	
	$query = $db->query(sprintf("SELECT * FROM `pages` WHERE `home` = '1' "));
	
	if ($query->num_rows > 0 ) {
	
		while ( $result = $query->fetch_assoc() ) {
							
			$rows = $result;
						
		}
		return $rows;
	}

	
}
