<?php
/**
* Plugin Name: List Jobs Widget
* Description: A widget to list Beamon jobs
* Version: 1.0
* Author: Alexandra Vasmatzis
**/
class List_Jobs_Widget extends WP_Widget 
{
    function __construct()
    {
        parent::__construct(

        // Base ID
        'list_jobs_widget',

        // Name
        __( 'List Beamon Jobs', 'Beamon' ),

        // Options
        array( 'description' => __( 'Lists jobs' ) )
        );
    }

    // Admin form creation
    function form( $instance ) 
    { 
        if( $instance )
        {
            $select = esc_attr( $instance['select'] );

        }
        else
        {
            $select = '';
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

    <?php          
    }
    
    // Updates after admin selection
    function update( $new_instance, $old_instance ) 
    {
        $instance = $old_instance;
        $instance['select'] = strip_tags( $new_instance['select'] );
        return $instance;
    }

    function widget( $args, $instance ) 
    {
        // Display only on page that admin selected
        if( is_page( $instance['select'] ) )
        {
        ?>
            <div id="jobs-container">
                <link href="<?php echo plugin_dir_url( __FILE__ )?>css/jobs.css" rel="stylesheet" type="text/css" />                           
                <?php
                $category_name = 'Jobs';

                // Query for posts
                $args = array( 'category_name' => $category_name );
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
                                <div class="post-inner post-hover">
                                    <a class="post-title" href="<?php echo get_the_permalink() ?>" rel="bookmark"><?php echo get_the_title() ?></a></br>
                                    <div class="entry excerpt">
                                        <p><?php echo get_the_excerpt() ?></p>
                                    </div>
                                </div>
                            </article>

                            <?php
                            // Display two posts on each row 
                            if( $i % 2 == 0 ) 
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
                ?>
            </div>
        <?php
        }
    }
}
// Register widget
function register_list_jobs_widget()
{
    register_widget('List_Jobs_Widget');
}
add_action('widgets_init', 'register_list_jobs_widget');
?>