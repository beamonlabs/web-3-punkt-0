<?php
/**
* Plugin Name: Nutshell Widget
* Description: A widget to display funny facts about Beamon
* Version: 1.0
* Author: Alexandra Vasmatzis
**/
?>

<?php
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
                <h2><?php echo $instance['title'] ?></h2>
                    <?php
                    $file_path = dirname(__FILE__) . '\csv\nutshell.csv';

                    if ( file_exists( $file_path) )
                    {
                    ?>                  
                        <table id="nutshell-table">
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

                                    // Get 3 random questions
                                    $questions = array_rand( $data, 3 );  
                                    foreach( $questions as $question )
                                    {
                                        $q = htmlentities( $data[$question][0], ENT_QUOTES, 'ISO-8859-1' );
                                        $a = htmlentities( $data[$question][1], ENT_QUOTES, 'ISO-8859-1' );
                                        ?>    
                                                    
                                        <td class="question">
                                            <div class="question-image bubble">
                                                <img src="<?php echo plugin_dir_url( __FILE__ )?>images\<?php echo $data[$question][2]?>" alt="<?php echo $question ?>">
                                            </div>
                                            <div class="question-text">
                                                <p><?php echo $q ?></p>
                                                <p class="big"><?php echo $a ?></p>
                                            </div>
                                        </td>
                                    <?php
                                    }
                                    ?>
                                </tr>

                            <?php
                            fclose( $file );
                            }
                            ?>
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
?>

<?php
// Register widget
function register_nutshell_widget()
{
    register_widget('Nutshell_Widget');
}
add_action('widgets_init', 'register_nutshell_widget');
?>