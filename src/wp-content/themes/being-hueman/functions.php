<?php
/* ------------------------------------------------------------------------- *
 *  Custom functions
/* ------------------------------------------------------------------------- */
	
	// Add your custom functions here, or overwrite existing ones. Read more how to use:
	// http://codex.wordpress.org/Child_Themes

/**
* Register Other Page Widget Area.
*/
function otherPageWidget_init() {

	register_sidebar( array(
		'name' => 'Other Page',
		'id' => 'other_page',
		'before_widget' => '<div id="otherPageWidget">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="otherPageTitle">',
		'after_title' => '</h2>',
	) );
}
add_action( 'widgets_init', 'otherPageWidget_init' );

/**
* Register Start Page Widget Area.
*/
function startPageWidget_init() {

	register_sidebar( array(
		'name' => 'Start Page',
		'id' => 'start_page',
		'before_widget' => '<div id="startPageWidget">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="startPageTitle">',
		'after_title' => '</h2>',
	) );
}

add_action( 'widgets_init', 'startPageWidget_init' );

// Add categories for posts
function sample_insert_category() {
	if(!term_exists('consultant')) {
		wp_insert_term(
			'Consultant',
			'category',
			array(
			  'description'	=> 'For posts of type "Konsulten har ordet".',
			  'slug' 		=> 'consultant'
			)
		);
	}

    if(!term_exists('expertises')) {
	    wp_insert_term(
		    'Expertises',
		    'category',
		    array(
			    'description'	=> 'For posts of type "Vad vi gör".',
			    'slug' 		=> 'expertises'
		    )
	    );
    }

    if(!term_exists('jobs')) {
	    wp_insert_term(
		    'Jobs',
		    'category',
		    array(
			    'description'	=> 'For posts of type "Jobb".',
			    'slug' 		=> 'jobs'
		    )
	    );
    }

    if(!term_exists('news')) {
	    wp_insert_term(
		    'News',
		    'category',
		    array(
			    'description'	=> 'For posts of type "Aktuellt".',
			    'slug' 		=> 'news'
		    )
	    );
    }

    if(!term_exists('nutshell')) {
	    wp_insert_term(
		    'Nutshell',
		    'category',
		    array(
			    'description'	=> 'For posts of type "Nutshell".',
			    'slug' 		=> 'nutshell'
		    )
	    );
    }

    if(!term_exists('colleagues')) {
	    wp_insert_term(
		    'Colleagues',
		    'category',
		    array(
			    'description'	=> 'For posts of type "Colleagues".',
			    'slug' 		=> 'colleagues'
		    )
	    );
    }

    if(!term_exists('projects')) {
	    wp_insert_term(
		    'Projects',
		    'category',
		    array(
			    'description'	=> 'For posts of type "Projects".',
			    'slug' 		=> 'projects'
		    )
	    );
    }
}
add_action( 'after_setup_theme', 'sample_insert_category' );