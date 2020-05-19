<?php
function wpnetbase_theme_enqueue_styles() {	
	wp_enqueue_style( 'wpnetbase-child-style', get_stylesheet_directory_uri() . '/style.css' );
	if( is_front_page () ){
		wp_enqueue_script( 'wpnetbase-chart', get_stylesheet_directory_uri() . '/js/Chart.js', array(), '', false );
		wp_enqueue_script( 'wpnetbase-doughnutit', get_stylesheet_directory_uri() . '/js/doughnutit.js', array(), '', false );
		wp_enqueue_script( 'matchHeight', get_template_directory_uri() . '/assets/js/jquery.matchHeight-min.js', array(), '', true );
		wp_enqueue_script( 'wpnetbase-customize', get_stylesheet_directory_uri() . '/js/customize.js', array(), '', true );
	}
}
add_action( 'wp_enqueue_scripts', 'wpnetbase_theme_enqueue_styles', 99 );