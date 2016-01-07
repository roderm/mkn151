<?php

function json_encode($model){
	$return = '';
	
	if(!is_array($model)){
		$return = '{';
	}
	foreach($model as $key=>$value){
		
		$return.='"'.$key.'"';
		
		switch(true){
			case is_array($value):
				
				break;
			case is_object($value):
				
				break;
			case is_string($value):
				
				break;
				 	
		}
	}
	if(!is_array($model)){
		$return.='}';
	}
	return $return;
}



/*
 * {
 * 	"name" : "ballon", 
 *  "volumen": 50,
 * }
 */