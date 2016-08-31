<?php
/**
* Plugin Name: Nutshell Widget
* Description: A widget to display funny facts about Beamon
* Version: 1.0
* Author: Alexandra Vasmatzis
**/
class Nutshell_Widget extends WP_Widget 
{
    function __construct()
    {
        parent::__construct(

        // Base ID
        'nutshell_widget',

        // Name
        __( 'Nutshell', 'Beamon' ),

        // Options
        array( 'description' => __( 'Displays funny facts about Beamon' ) )
        );
    }

    // Admin form creation
    function form( $instance ) 
    { 
        if( $instance )
        {
            $select = esc_attr( $instance['select'] );
            $title = esc_attr( $instance['title'] );
        }
        else
        {
            $select = '';
            $title = '';
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

    <?php
    }
    
    // Updates after admin selection
    function update( $new_instance, $old_instance ) 
    {
        $instance = $old_instance;
        $instance['select'] = strip_tags( $new_instance['select'] );
        $instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;
    }

    function widget( $args, $instance ) 
    {
        // Display only on page that admin selected
        if( is_page( $instance['select'] ) )
        {
        ?>
            <div id="nutshell-container">
            <link href="<?php echo plugin_dir_url( __FILE__ )?>css/nutshell.css" rel="stylesheet" type="text/css" />
            <div class="container-padding grey-background">               
                <h2 class="align-center fat"><?php echo $instance['title'] ?></h2>
                    
                <?php                   
                $category_name = 'Nutshell';  
                $post_count = 3; 
                $order_type = 'rand'; 

                // Query for posts
                $args = array( 'category_name' => $category_name, 'posts_per_page' => $post_count, 'orderby' => $order_type  );
                $the_query = new WP_Query( $args );
                if ( $the_query->have_posts() ) 
                {
                ?>                 
                    <table id="nutshell-table"> 
                        <tr>                      
                            <?php        
                            $i = 1; 
                            // query 3 random posts
                            while ( $the_query->have_posts() ) 
                            {
                            $the_query->the_post();  
                            ?>                    
                                <td class="question">
                                    <div class="question-image bubble">
                                        <?php echo the_post_thumbnail(); ?>
                                    </div>
                                    <div class="question-text">
                                        <p><?php echo get_the_title() ?></p>
                                        <p class="big"><?php echo get_the_excerpt() ?></p>
                                    </div>
                                </td>
                            <?php
                            }
                            ?>
                        </tr>
                    </table>
                <?php          
                } 
                ?>
            </div> 
        </div>                                     
        <?php     
        }
    }
}
// Register widget
function register_nutshell_widget()
{
    register_widget('Nutshell_Widget');
}
add_action('widgets_init', 'register_nutshell_widget');
?>