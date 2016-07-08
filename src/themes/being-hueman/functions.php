<?php
/* ------------------------------------------------------------------------- *
 *  Custom functions
/* ------------------------------------------------------------------------- */
	
	// Add your custom functions here, or overwrite existing ones. Read more how to use:
	// http://codex.wordpress.org/Child_Themes

/**
* Register Page Content Widget Area.
*/
function pageContentWidget_init() {

	register_sidebar( array(
		'name' => 'Page Content',
		'id' => 'page_content',
		'before_widget' => '<div id="pageContentWidget">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="pageContentTitle">',
		'after_title' => '</h2>',
	) );
}
add_action( 'widgets_init', 'pageContentWidget_init' );