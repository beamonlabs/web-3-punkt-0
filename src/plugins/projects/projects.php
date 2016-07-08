<?php
/**
* Plugin Name: List Projects Widget
* Description: A widget to list Beamon projects
* Version: 1.0
* Author: Alexandra Vasmatzis
**/
?>

<?php
class List_Projects_Widget extends WP_Widget 
{
    function __construct()
    {
        parent::__construct(

        // Base ID
        'list_projects_widget',

        // Name
        __('List Beamon Projects', 'Beamon'),

        // Options
        array('description' => __('Lists projects found in the specific csv file'))
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
            
        <!-- Let admin choose sub title (only for other pages than start page) -->
        <p>
            <label for="<?php echo $this->get_field_id('sub_tile'); ?>"><?php _e('Sub title (not for start page)'); ?></label><br/>
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
            $file_path_conv = iconv('utf-8', 'cp1252', 'C:\Users\Beamon People gäst\Documents\My Web Sites\web3punkt0\wp-content\plugins\projects\csv\projects.csv');

            if (file_exists($file_path_conv))
            {
                echo '<div id="project_intro_container">';

                if ($isStartPage)
                {
                    echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/projects_small.css" rel="stylesheet" type="text/css" />';
                    echo '<div id="project_text_container"><h2 id="project_title">' . $instance["title"] . '</h2><p id="project_text">' . $instance["sub_title"] . '</p><a id="project_link" title="Projekt" href="/Projekt/">Läs om våra projekt</a></div>';
                }
                else
                {
                    echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/projects_large.css" rel="stylesheet" type="text/css" />';
                    echo '<h1 id="project_title">' . $instance["title"] . '</h1><p id="project_text">' . $instance["sub_title"] . '</p>';
                }

                echo '<div id="project_container"><table>';

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
                        // Get 4 random projects
                        $projects = array_rand($data, 4);  
                        foreach($projects as $project)
                        {
                            $name = htmlentities($data[$project][0], ENT_QUOTES, 'ISO-8859-1');
                            echo '<td class="project_image"><a href=""><img src="' . plugin_dir_url( __FILE__ ) . 'images\\' . $data[$project][1] . '" alt="' . $name . '"></br>' . $name . '</a></td>';           
                        }
                    }
                    else
                    {
                        foreach($data as $project)
                        {
                            $name = htmlentities($project[0], ENT_QUOTES, 'ISO-8859-1');
                            echo '<td class="project_image"><a href=""><img src="' . plugin_dir_url( __FILE__ ) . 'images\\' . $project[1] . '" alt="' . $name . '"></br><p>' . $name . '</p></a></td>';          
                        }
                    }
                                             
                    fclose($file);

                    echo "</tr></table></div></div>";             
                }
            }
        }
    }
}
?>

<?php
// Register widget
function register_list_projects_widget()
{
    register_widget('List_Projects_Widget');
}
add_action('widgets_init', 'register_list_projects_widget');
?>