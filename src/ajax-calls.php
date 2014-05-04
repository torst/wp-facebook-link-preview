<?php

add_action( 'wp_ajax_link_preview_get_details', 'link_preview_get_details_callback' );
add_action( 'wp_ajax_nopriv_link_preview_get_details', 'link_preview_get_details_callback' );

function link_preview_get_details_callback(){
	$url = $_POST['url'];	
	
	echo eek_get_content_from_url( $url );
	
	die();
}

?>