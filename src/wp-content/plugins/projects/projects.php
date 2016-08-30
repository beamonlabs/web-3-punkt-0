<?php
/**
* Plugin Name: List Projects Widget
* Description: A widget to list Beamon projects
* Version: 1.0
* Author: Alexandra Vasmatzis
**/
class List_Projects_Widget extends WP_Widget 
{
    function __construct()
    {
        parent::__construct(

        // Base ID
        'list_projects_widget',

        // Name
        __( 'List Beamon Projects', 'Beamon' ),

        // Options
        array( 'description' => __( 'Lists projects found in the specific csv file' ) )
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

            $file_path = dirname(__FILE__) . '\csv\projects.csv';

            if ( file_exists( $file_path ) )
            {
            ?>
                <div id="project-container">
                    <link href="<?php echo plugin_dir_url( __FILE__ )?>css/projects.css" rel="stylesheet" type="text/css" />

                <?php
                if ( $is_startpage )
                {
                ?>
                    <div class="startpage-widget grey-background">
                    <div id="project-text-container">
                        <h2 class="align-left"><?php echo $instance["title"] ?></h2>
                        <p class="align-left"><?php echo $instance["sub_title"] ?></p>
                        <a class="green-link float-left" title="Projekt" href="/Projekt/">Läs om våra projekt</a>
                    </div>
                    <div id="project-image-container-small">
                <?php
                }
                else
                {
                ?>
                    
                    
                    <h1 class="align-center"><?php echo get_the_title(); ?></h1>
                    <p class="half-width auto-margin"><?php echo get_the_content(); ?></p>
                    <div id="project-image-container-large">
                <?php
                }
                ?>

                <table>

                <!-- Get data from csv file -->
                <?php
                if ( ( $file = fopen( $file_path, "r" ) ) !== FALSE )
                {
                ?>
                    <tr>

                    <?php
                    $data = array();
                    while ( ( $data_temp = fgetcsv( $file, 1000, ";" ) ) !== FALSE ) 
	                {  
                        $data[] = $data_temp;
                    }  

                    // Remove title row from xls
                    array_shift( $data );

                    if( $is_startpage )
                    {
                        // Get 4 random projects
                        $projects = array_rand( $data, 4 );  
                        foreach( $projects as $project )
                        {
                            $name = htmlentities( $data[$project][0], ENT_QUOTES, 'ISO-8859-1' );
                            ?>
                            
                            <td class="project-image"><a href=""><img src="<?php echo plugin_dir_url( __FILE__ )?>images\\<?php echo $data[$project][1]?>" alt="<?php echo $name ?>"></br><p><?php echo $name ?></p></a></td>          
                        <?php
                        }
                    }
                    else
                    {
                        foreach( $data as $project )
                        {
                            $name = htmlentities( $project[0], ENT_QUOTES, 'ISO-8859-1' );
                            ?>
                            
                            <td class="project-image"><a href=""><img src="<?php echo plugin_dir_url( __FILE__ )?>images\\<?php echo $project[1]?>" alt="<?php echo $name ?>"></br><p><?php echo $name ?></p></a></td>       
                        <?php
                        }
                    }                                           
                    fclose( $file );
                    ?>
                    
                    </tr></table></div></div>
                    <?php      
                    if ( $is_startpage )
                    {
                    ?>
                        </div>
                    <?php    
                    }     
                }
            }
        }
    }
}
// Register widget
function register_list_projects_widget()
{
    register_widget('List_Projects_Widget');
}
add_action('widgets_init', 'register_list_projects_widget');
?>