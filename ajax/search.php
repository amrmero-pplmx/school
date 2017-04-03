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
	
	
	require_once('../classes/configuration.php');
	require_once('../classes/functions.php');
		
	if ($_POST['form_type'] == 'search_members') {
		
		$search_class = new search;
		$search_class->db = $db;
		
		$search_class->searchTitle = $_POST['title'];
		$search_class->searchType = "user";
		$search = $search_class->searchUser();
		
		$tpl = new Template("./templates/".$Setting['template']."/layouts/search/search_user_list.tpl");
		
		if (is_array($search)) {
			echo query_users_list($search, $tpl);
		} else {
			echo 'Not found';
		}
	}
	