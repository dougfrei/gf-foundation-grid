<?php
/*
Plugin Name: Gravity Forms Foundation Grid
Plugin URI: https://github.com/dougfrei/gf-foundation-grid
Description: Create responsive layouts in Gravity Forms using the Foundation CSS Grid
Version: 1.0.0
Author: Doug Frei
Author URI: https://github.com/dougfrei
*/

add_action('init', function() {
	if (!class_exists('GFForms')) {
		return;
	}

	require_once('classes/GFFoundation.php');

	// GFFoundation::init();
	$gf_foundation = new GFFoundation();
});
