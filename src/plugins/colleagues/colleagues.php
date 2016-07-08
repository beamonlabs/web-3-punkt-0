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
        __('List Beamon Colleagues', 'Beamon'),

        // Options
        array('description' => __('Lists colleagues found in csv file - should be changed to load the information from google spreadsheet'))
        );
    }

    // Admin form creation
    function form($instance) 
    { 
        if($instance)
        {
            $select = esc_attr($instance['select']);
            $title = esc_attr($instance['title']);
            $sub_title = esc_attr($instance['sub_title']);

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
            <label for="<?php echo $this->get_field_id('select'); ?>"><?php _e('Select page', 'wp_widget_plugin'); ?></label><br/>          
            <select name="<?php echo $this->get_field_name('select'); ?>" id="<?php echo $this->get_field_id('select'); ?>">
            <?php
            $pages = get_pages();
            foreach ($pages as $page) 
            {
                $option = '<option value="' . $page->ID . '"';
                $option .= selected($instance['select'], $page->ID);
                $option .= '>' . $page->post_title;
                $option .= '</option>';
                echo $option;
            }
            ?>
            </select>
        </p>
            
        <!-- Let admin choose title -->
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $instance['title']?>" style="width: 100%">
        </p>
            
        <!-- Let admin choose subtitle (not displayed on start page) -->
        <p>
            <label for="<?php echo $this->get_field_id('sub_tile'); ?>"><?php _e('Subtitle (not displayed on start page)'); ?></label><br/>
            <textarea name="<?php echo $this->get_field_name('sub_title'); ?>" id="<?php echo $this->get_field_id('sub_title'); ?>" rows="5" style="width: 100%"><?php echo $instance['sub_title']?></textarea>
        </p>

        <?php
    }
    
    // Updates after admin selection
    function update($new_instance, $old_instance) 
    {
        $instance = $old_instance;
        $instance['select'] = strip_tags($new_instance['select']);
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['sub_title'] = strip_tags($new_instance['sub_title']);
        return $instance;
    }

    function widget($args, $instance) 
    {
        // Display only on page that admin selected
        if(is_page($instance['select']))
        {
            // Flag for differences between start page and other pages
            if(is_home() || is_front_page()) { $isStartPage = TRUE; } else { $isStartPage = FALSE; }

            // Is needed if the path includes swedish characters
            $file_path_conv = iconv('utf-8', 'cp1252', 'C:\Users\Beamon People gäst\Documents\My Web Sites\web3punkt0\wp-content\plugins\colleagues\csv\colleagues.csv');

            if (file_exists($file_path_conv))
            {
                echo "<div id='colleague_intro_container'>";

                if ($isStartPage)
                {
                    echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/colleagues_small.css" rel="stylesheet" type="text/css" />';
                    echo '<h2 id="colleague_title">' . $instance['title'] . '</h2><a id="colleague_link" title="Medarbetare" href="/Beamon People/">Se alla Beamon People</a>';
                }
                else
                {
                    echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/colleagues_large.css" rel="stylesheet" type="text/css" />';
                    echo '<h1 id="colleague_title">' . $instance["title"] . '</h1><p id="colleague_text">' . $instance["sub_title"] . '</p>';
                }

                echo '<div id="colleague_container"><table>';

                if (($file = fopen($file_path_conv, "r")) !== FALSE)
                {
                    echo '<tr>';

                    $data = array();
                    while (($data_temp = fgetcsv($file, 1000, ";")) !== FALSE) 
	                {  
                        $data[] = $data_temp;
                    }  

                    // Remove title row from xls
                    array_shift($data);

                    if($isStartPage)
                    {
                        // Get 12 random colleagues
                        $colleagues = array_rand($data, 12);  
                        foreach($colleagues as $colleague)
                        {
                            $name = htmlentities($data[$colleague][0], ENT_QUOTES, 'ISO-8859-1');
                            echo '<td class="colleague_image"><a href=""><img src="' . plugin_dir_url( __FILE__ ) . 'images\\' . $data[$colleague][3] . '" alt="' . $name . '"></br>' . $name . '</a></td>';           
                        }
                    }
                    else
                    {
                        foreach($data as $colleague)
                        {
                            $name = htmlentities($colleague[0], ENT_QUOTES, 'ISO-8859-1');
                            echo '<td class="colleague_image"><a href=""><img src="' . plugin_dir_url( __FILE__ ) . 'images\\' . $colleague[3] . '" alt="' . $name . '"></br><p>' . $name . '</p></a></td>';          
                        }
                    }
                                             
                    fclose($file);

                    echo '</tr></table></div></div>';             
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