<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

/**
 * Description of view
 *
 * @author Roman
 */
class Uri{

	/**
	 * Prints the Url for the Call from the Client
	 * @param String $controller
	 * @param String $method
	 */
	public static function action($controller = 'Gamer', $method='getMain'){
		echo Url::getAction($controller, $method);
	}

	/**
	 * Returns the Url for the Call from the Client
	 * @param unknown_type $controller
	 * @param unknown_type $method
	 * @return string
	 */
	public static function getAction($controller = 'Gamer', $method='getMain'){
		return config::homePath.$controller.'/'.$method;
	}

	/**
	 * Returns Filepath from $path-File
	 * @param unknown_type $path
	 */
	public static function file($path){
		$home = str_replace('index.php/', '', config::homePath);
		echo $home.$path;
	}

	/**
	 * Does something (not used)
	 * @param unknown_type $url
	 */
	public static function esc_url($url) {
		if ('' == $url) {
			return $url;
		}

		$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

		$strip = array('%0d', '%0a', '%0D', '%0A');
		$url = (string) $url;

		$count = 1;
		while ($count) {
			$url = str_replace($strip, '', $url, $count);
		}

		$url = str_replace(';//', '://', $url);

		$url = htmlentities($url);

		$url = str_replace('&amp;', '&#038;', $url);
		$url = str_replace("'", '&#039;', $url);

		if ($url[0] !== '/') {
			// We're only interested in relative links from $_SERVER['PHP_SELF']
			return '';
		} else {
			return $url;
		}
	}
}
