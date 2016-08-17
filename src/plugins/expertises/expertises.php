<?php
/**
* Plugin Name: List Expertises Widget
* Description: A widget to list Beamon expertises
* Version: 1.0
* Author: Alexandra Vasmatzis
**/
?>

<?php
class List_Expertises_Widget extends WP_Widget 
{
    function __construct()
    {
        parent::__construct(

        // Base ID
        'list_expertises_widget',

        // Name
        __( 'List Beamon Expertises', 'Beamon' ),

        // Options
        array( 'description' => __('Lists expertises' ))
        );
    }

    // Admin form creation
    function form( $instance ) 
    { 
        if( $instance )
        {
            $select = esc_attr( $instance['select'] );
            $title = esc_attr( $instance['title'] );
            $sub_title = esc_attr( $instance['sub_title'] );

        }
        else
        {
            $select = '';
            $title = '';
            $sub_title = '';
        }
        ?>
        
        <!-- Let admin decide which page the widget will be displayed in -->
        <p>
            <label for="<?php echo $this->get_field_id( 'select' ); ?>"><?php _e( 'Select page', 'wp_widget_plugin' ); ?></label><br/>          
            <select name="<?php echo $this->get_field_name( 'select' ); ?>" id="<?php echo $this->get_field_id( 'select' ); ?>">

            <?php
                $pages = get_pages();
                foreach ( $pages as $page ) 
                {
                    $option = '<option value="' . $page->ID . '"';
                    $option .= selected( $instance['select'], $page->ID );
                    $option .= '>' . $page->post_title;
                    $option .= '</option>';
                    echo $option;
                }
            ?>
            </select>
        </p>
            
        <!-- Let admin choose title -->
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' ); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" id="<?php echo $this->get_field_id( 'title' ); ?>" value="<?php echo $instance['title']?>" style="width: 100%">
        </p>
            
        <!-- Let admin choose sub title (only for other pages than start page) -->
        <p>
            <label for="<?php echo $this->get_field_id( 'sub_tile' ); ?>"><?php _e( 'Sub title (not for start page)' ); ?></label><br/>
            <textarea name="<?php echo $this->get_field_name( 'sub_title' ); ?>" id="<?php echo $this->get_field_id( 'sub_title' ); ?>" rows="5" style="width: 100%"><?php echo $instance['sub_title']?></textarea>
        </p>

    <?php
    }
    
    // Updates after admin selection
    function update( $new_instance, $old_instance ) 
    {
        $instance = $old_instance;
        $instance['select'] = strip_tags( $new_instance['select'] );
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['sub_title'] = strip_tags( $new_instance['sub_title'] );
        return $instance;
    }

    function widget( $args, $instance ) 
    {
        // Display only on page that admin selected
        if( is_page( $instance['select'] ) )
        {
            // Flag for differences between start page and other pages
            if( is_home() || is_front_page() ) { $is_startpage = TRUE; } else { $is_startpage = FALSE; }

            echo '<div id="expertise-container">';

            if ( $is_startpage )
            {
                echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/expertises_small.css" rel="stylesheet" type="text/css" />';            
            }
            else
            {
                echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/expertises_large.css" rel="stylesheet" type="text/css" />';
                echo '<h2 id="expertise-title">' . $instance["title"] . '</h2><p id="intro-text">'  . $instance["sub_title"] .  '</p></div>';              
            }

            // Arguments for post query
            $category_name = 'Expertises';
            $args = array( 'category_name' => $category_name );
            $the_query = new WP_Query( $args );

            if( $is_startpage )
            {            
          
                echo '<div id="left-container"><div id="text-container"><h2 id="expertise-title">' . $instance["title"] . '</h2><p id="expertise-text">'  . $instance["sub_title"] .  '</p><a class="green-link" title="Kompetenser" href="/Vad-vi-gor/">Läs om våra kompetenser</a></div></div>';

                echo '<div id="right-container"><ul>';

                $the_query = new WP_Query( $args );
                if ( $the_query->have_posts() ) 
                {
                    while ( $the_query->have_posts() )
                    {
                        $the_query->the_post();

                        echo '<li class="expertise-image"><a href="' . get_the_permalink() . '"><div class="bubble">';
                        echo the_post_thumbnail();
                        echo '</div><p>' . get_the_title() . '</p></a></li>';                                        
                    }
                }

                echo '</ul></div></div>';
            }
            else
            {
                // Arguments for post query
                $the_query = new WP_Query( $args );
                if ( $the_query->have_posts() ) 
                {
                    echo '<div class="post-list group"><div class="post-row">';
                    $i = 1; 
                    while ( $the_query->have_posts() ) 
                    {
                        $the_query->the_post();
                                            
                        echo '<article class="group post type-post status-publish format-standard hentry"><div class="thumbnail-left bubble">';
                        echo the_post_thumbnail();
                        echo '</div><div class="post-inner post-hover">';
                        echo '<a class="post-title" href="' . get_the_permalink() .'" rel="bookmark">' . get_the_title() .'</a></br>';
                        echo '<div><p>' . get_the_content() . '</p></div></div></article>';

                        // Display three posts on each row 
                        if( $i % 3 == 0 ) 
                        { 
                            echo '</div><div class="post-row">'; 
                        } 

                        $i++; 
                    }
                    echo '</div>';
                }
                wp_reset_postdata();
            }                   
        }
    }
}
?>

<?php
// Register widget
function register_list_expertises_widget()
{
    register_widget('List_Expertises_Widget');
}
add_action('widgets_init', 'register_list_expertises_widget');
?>