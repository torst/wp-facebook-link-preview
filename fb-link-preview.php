<?php
/*
Plugin Name: WP Facebook Link Preview
Version: 1.0
Plugin URI: http://www.softbinator.ro/
Author: Radu-Sebastian Amarie
Author URI: http://eek.ro/
Description: This plugin will let you preview url shares just like on facebook.
*/



function eek_fb_link_preview_activate() {
	global $wpdb;
	
	$query = $wpdb->query(
	"CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."link_preview` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `link` text COLLATE utf8_unicode_ci NOT NULL,
	  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
	  `picture` int(11) NOT NULL,
	  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;");

}
register_activation_hook( __FILE__, 'eek_fb_link_preview_activate' );

function eek_get_content_from_url( $url = '' ){
	global $wpdb;
	
	if( empty( $url ) ){ $url = $_POST['url']; }
	
	if( !filter_var( $url, FILTER_VALIDATE_URL ) ){ return json_encode( array( 'status' => 'Invalid URL' ) ); }
		
	$html = file_get_contents( $url );
	$dom = new domDocument;
	$dom->strictErrorChecking = FALSE;
	@$dom->loadHTML($html);
	
	$dom->preserveWhiteSpace = false;
		
	//Get Title, Primary Image and Description from og fields.

	$xpath = new DOMXPath($dom);
	
	$nodes = $xpath->query('//head/meta');
	
	foreach($nodes as $node) {
		 $name = $node->getAttribute('name');
		 $property = $node->getAttribute('property');
		 $content = $node->getAttribute('content');
		 
		switch ($property) {
			case 'og:title':
				$title = $content; 
				break;
			case 'og:image':
				$primary_image = $content;
				break;
			case 'og:description':
				$description = $content;
				break;
		}
		
		if( $name == 'description' && empty( $description ) ){
			$description = $content;	
		}
		
	}
	
	//Get the Title
	if( empty( $title ) ){
		
		$title = $xpath->query('//title')->item(0)->nodeValue;
		
		if( !$title ){
			$title = $dom->getElementsByTagName('h1');
			if( $title->length > 0 ){
				$title = ($title->item(0)->nodeValue . "\n"); // "Example Web Page"
			}else{
				$title = "Untitled";	
			}
		}
	
	}
	
	//Get the Images
	$images = $dom->getElementsByTagName('img');
	
	if( !empty( $primary_image ) ){
		$img_src = array( $primary_image );
	}else{
		$img_src = array();	
	}
		
	foreach ($images as $image) {
	  array_push( $img_src, $image->getAttribute('src') );
	}	
	
	$img_src = array_unique( $img_src );
	
	return json_encode( array( 'url' => $url, 'title' => trim( strip_tags( $title ) ), 'description' => trim( strip_tags( $description ) ), 'images' => $img_src, 'status' => 'ok' ) );
	
}