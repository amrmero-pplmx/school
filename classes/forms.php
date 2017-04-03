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

class RenderForm 
{	
	
	public $Data;
	
	function FormGroup()
	{
		if (is_array($this->Data) ) {
			
			foreach ($this->Data as $key => $value) {
				print_r($value);
			}

		} else {
			return 'No data given';
		}

	}

	
	
}












