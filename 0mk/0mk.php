<?php
/*
Plugin Name: 0mk Shortener
Plugin URI: http://kuzmanov.info/0mk
Description: 0.mk Shortener generates a short url using the Macedonian shortener Zero[dot]mk
Version: 0.1
Author: Boris Kuzmanov
Author URI: http://kuzmanov.info
*/

// Функција
function zeromk($url) {  
	$nulamk = file_get_contents("http://api.0.mk/v2/skrati?korisnik=0mkapi&apikey=1a7854a2226925282e54d1a01af5058c&format=plaintext&link=".$url);  
return $nulamk;  
} 

// Додавање на кратката врска на крајот од написот
add_filter('the_content', 'VmetniZeroMk');

function VmetniZeroMk($content) {
$adresa = zeromk(get_permalink($post->ID));
	if(is_single()) {
		$content.= 'Short URL: <a href="'.$adresa.'">'.$adresa.'</a>';
	}
	return $content;
}
?>
