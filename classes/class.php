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

class classes 
{	

	public $db;
	
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

	
	
}












