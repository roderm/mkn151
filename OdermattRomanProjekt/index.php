<?php
include 'classes/Uri.php';
include 'config.php';
$_subPage = str_replace(config::homePath, '', $_SERVER['PHP_SELF']);

if($_subPage === ''){
  $_subPage = 'Gamer/getMain';
}

$bla=explode('/', $_subPage);
$className = $bla[0];
if(sizeof($bla)>1){
	$method = $bla[1];
}else{
	$method = 'getMain';
}
if(!stristr($_subPage, '.')){
	require('classes/routFactory.php');
	include_once 'classes/person.php';
	person::startSession();
	new routFactory($className, $method);
}else{
	switch(true){
		case stristr($_subPage, 'script/'):
			$bla=explode('script/', $_subPage);
			echo file_get_contents('script/'.$bla[1]);
			break;
		case stristr($_subPage, 'style/'):
			$bla=explode('style/', $_subPage);
			echo file_get_contents('style/'.$bla[1]);
			break;
	}
	
}

?>
