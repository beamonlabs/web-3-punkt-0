<?php
/**
* Plugin Name: List Colleagues Widget
* Description: A widget to list Beamon colleagues
* Version: 1.0
* Author: Alexandra Vasmatzis
**/
?>

<?php
class List_Colleagues_Widget extends WP_Widget 
{
    function __construct()
    {
        parent::__construct(

        // Base ID
        'list_colleagues_widget',

        // Name
        __( 'List Beamon Colleagues', 'Beamon' ),

        // Options
        array( 'description' => __( 'Lists colleagues found in csv file - should be changed to load the information from google spreadsheet' ) )
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
            
        <!-- Let admin choose subtitle (not displayed on start page) -->
        <p>
            <label for="<?php echo $this->get_field_id( 'sub_tile' ); ?>"><?php _e( 'Subtitle (not displayed on start page)' ); ?></label><br/>
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

            $file_path = dirname(__FILE__) . '\csv\colleagues.csv';

            if ( file_exists( $file_path) )
            {
            ?>
                <div id="colleague-container">

                <?php
                if ( $is_startpage )
                {
                ?>
                    <link href="<?php echo plugin_dir_url( __FILE__ )?>css/colleagues_small.css" rel="stylesheet" type="text/css" />
                    <h2 id="colleague-title"><?php echo $instance['title'] ?></h2>
                    <a class="green-link" title="Medarbetare" href="/Beamon People/">Se alla Beamon People</a>
                <?php
                }
                else
                {
                ?>
                    <link href="<?php echo plugin_dir_url( __FILE__ )?>css/colleagues_large.css" rel="stylesheet" type="text/css" />
                    <h1 id="colleague-title"><?php echo $instance["title"] ?></h1>
                    <p id="colleague-text"><?php $instance["sub_title"] ?></p>
                <?php
                }
                ?>
                
                <div id="colleague-image-container">
                <table>

                <?php
                /* Get data from csv file */
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

                    // Remove title row from csv
                    array_shift( $data );

                    if( $is_startpage )
                    {
                        // Get 12 random colleagues
                        $colleagues = array_rand( $data, 12 );  
                        foreach( $colleagues as $colleague )
                        {
                            $name = htmlentities( $data[$colleague][0], ENT_QUOTES, 'ISO-8859-1' );
                            ?>                            
                            <td class="colleague-image">
                                <a href="">
                                    <img src="<?php echo plugin_dir_url( __FILE__ )?>images\\<?php echo $data[$colleague][3]?>" alt="<?php echo $name ?>"></br><?php echo $name ?>
                                </a>
                            </td>
                        <?php
                        }
                    }
                    else
                    {
                        foreach( $data as $colleague )
                        {
                            $name = htmlentities( $colleague[0], ENT_QUOTES, 'ISO-8859-1' );
                            ?>
                            <td class="colleague-image">
                                <a href="">
                                    <img src="<?php echo plugin_dir_url( __FILE__ )?>images\\<?php echo $colleague[3] ?>" alt="<?php echo $name ?>"></br>
                                    <p><?php echo $name ?></p>
                                </a>
                            </td>
                        <?php          
                        }
                    }
                                             
                    fclose( $file );
                    ?>
                    
                    </tr></table></div></div>
                <?php     
                }
            }
        }
    }
}
?>

<?php
// Register widget
function register_list_colleagues_widget()
{
    register_widget('List_Colleagues_Widget');
}
add_action('widgets_init', 'register_list_colleagues_widget');
?>