<?php
/**
* Plugin Name: List Jobs Widget
* Description: A widget to list Beamon jobs
* Version: 1.0
* Author: Alexandra Vasmatzis
**/
?>

<?php
class List_Jobs_Widget extends WP_Widget 
{
    function __construct()
    {
        parent::__construct(

        // Base ID
        'list_jobs_widget',

        // Name
        __('List Beamon Jobs', 'Beamon'),

        // Options
        array('description' => __('Lists jobs'))
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
            echo "<div class='jobs_intro_container'>";

            echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/jobs.css" rel="stylesheet" type="text/css" />';

            // Get all children pages
            $my_wp_query = new WP_Query();
            $all_wp_pages = $my_wp_query->query(array('post_type' => 'page'));
            $this_page =  get_page($instance['select']);
            $page_children = get_page_children($this_page->ID, $all_wp_pages );
                  
            echo '<h2 id="jobs_title">' . $instance["title"] . '</h2><p id="jobs_text">'  . $instance["sub_title"] .  '</p>';
            echo '<div id="jobs_container"><ul>';

            foreach($page_children as $child)
            {
                $page = $child->ID;
                $page_data = get_page($page);
                $title = $page_data->post_title;
                echo '<div class="page_content"><li><h2>' . $title . '</h2>';
                echo '</br></br><a class="page_link" href="' . get_permalink($page) . '">Läs mer</a></li></div>';
            }

            echo '</ul></div></div>';             
        }
    }
}
?>

<?php
// Register widget
function register_list_jobs_widget()
{
    register_widget('List_Jobs_Widget');
}
add_action('widgets_init', 'register_list_jobs_widget');
?>