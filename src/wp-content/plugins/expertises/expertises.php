<?php
/**
* Plugin Name: List Expertises Widget
* Description: A widget to list Beamon expertises
* Version: 1.0
* Author: Alexandra Vasmatzis
**/
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
            
        <!-- Let admin choose title (only for start page) -->
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (only for start page)' ); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" id="<?php echo $this->get_field_id( 'title' ); ?>" value="<?php echo $instance['title']?>" style="width: 100%">
        </p>
            
        <!-- Let admin choose sub title (only for start page) -->
        <p>
            <label for="<?php echo $this->get_field_id( 'sub_tile' ); ?>"><?php _e( 'Sub title (only for start page)' ); ?></label><br/>
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

            // Arguments for post query
            $category_name = 'Expertises';
            $args = array( 'category_name' => $category_name );
            $the_query = new WP_Query( $args );

            ?>
            
            <div id="expertise-container">
            <link href="<?php echo plugin_dir_url( __FILE__ )?>css/expertises.css" rel="stylesheet" type="text/css" />
            
            <?php
            if ( $is_startpage )
            {
            ?>  
                <div id="expertise-min-height" class="startpage-widget">
                    <div class="expertise-small-container float-left">
                        <div id="text-container">
                            <h2 class="align-left"><?php echo $instance["title"] ?></h2>
                            <p class="align-left"><?php echo $instance["sub_title"] ?></p>
                            <a class="green-link float-left" title="Kompetenser" href="/Vad-vi-gor/">Läs om våra kompetenser</a>
                        </div>
                    </div>
                    <div class="expertise-small-container float-left">
                        <ul>

                            <?php
                            $the_query = new WP_Query( $args );
                            if ( $the_query->have_posts() ) 
                            {
                                while ( $the_query->have_posts() )
                                {
                                    $the_query->the_post();
                                    ?>

                                    <li class="expertise-image">
                                        <a href="<?php echo get_the_permalink() ?>">
                                            <div class="bubble">
                                                <?php
                                                echo the_post_thumbnail();
                                                ?>
                                            </div>   
                                            <p><?php echo get_the_title() ?></p>                                  
                                        </a>                           
                                    </li>
                                <?php                                       
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            <?php
            }
            else
            {
            ?>
                <h1 class="align-center"><?php echo get_the_title(); ?></h1>
                <p class="half-width auto-margin bottom-padding"><?php echo get_the_content(); ?></p>
           
                <?php
                // Arguments for post query
                $the_query = new WP_Query( $args );
                if ( $the_query->have_posts() ) 
                {
                ?>
                    <div class="post-list group">
                        <div class="post-row">
                            <?php
                            $i = 1; 
                            while ( $the_query->have_posts() ) 
                            {
                                $the_query->the_post();
                                ?>
                                            
                                <article class="group post type-post status-publish format-standard hentry">
                                    <div class="thumbnail-left bubble">
                                        <?php
                                        echo the_post_thumbnail();
                                        ?>
                                    </div>
                                    <div class="post-inner post-hover">
                                        <a class="post-title" href="<?php echo get_the_permalink() ?>" rel="bookmark"><?php echo get_the_title() ?></a></br>
                                        <div>
                                            <p><?php echo get_the_content() ?></p>
                                        </div>
                                    </div>
                                </article>

                                <!-- Display three posts on each row -->
                                <?php 
                                if( $i % 3 == 0 ) 
                                { 
                                ?>
                                    </div>
                                    <div class="post-row">
                                <?php
                                } 
                                $i++; 
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
                wp_reset_postdata();
            } 
            ?>   
            </div>   
        <?php            
        }
    }
}
// Register widget
function register_list_expertises_widget()
{
    register_widget('List_Expertises_Widget');
}
add_action('widgets_init', 'register_list_expertises_widget');
?>