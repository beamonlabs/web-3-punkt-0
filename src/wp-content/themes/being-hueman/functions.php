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