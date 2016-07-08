<?php
/**
* Plugin Name: List News Widget
* Description: A widget to list latest news
* Version: 1.0
* Author: Alexandra Vasmatzis
**/
?>

<?php
class List_News_Widget extends WP_Widget 
{
    function __construct()
    {
        parent::__construct(

        // Base ID
        'list_news_widget',

        // Name
        __('List Beamon News', 'Beamon'),

        // Options
        array('description' => __('Lists latest news'))
        );
    }

    // Admin form creation
    function form($instance) 
    { 
        if($instance)
        {
            $title = esc_attr($instance['title']);

        }
        else
        {
            $title = '';
        }

        ?>
            
        <!-- Let admin choose title -->
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $instance['title']?>" style="width: 100%">
        </p>
     
        <?php
    }
    
    // Updates after admin selection
    function update($new_instance, $old_instance) 
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    function widget($args, $instance) 
    {
        // Display only on start page
        if(is_front_page())
        {
            echo '<div id="news_intro_container">';

            echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/news.css" rel="stylesheet" type="text/css" />';
            echo '<h2 id="news_intro_title">' . $instance["title"] . '</h2>';

            echo '<div id="news_post_container"><ul>';

            $args = array('numberposts' => 3);
            $recent_posts = wp_get_recent_posts($args);
             
            foreach($recent_posts as $recent)
            {
		        echo '<li><a class="post_title" href="' . get_permalink($recent["ID"]) . '">' . $recent["post_title"] . '</a><p class="post_excerpt">' . $recent["post_content"] . '</p></li>';
	        }

            echo '<li><a id="news_link" title="Nyheter" href="/Aktuellt/">Alla nyheter</a></li></ul></div>';
            
            echo '<div id="image_container"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\jobbamedoss.jpg" alt="Jobba med oss" /></br>' . $name . '</div></div>';             
        }
    }
}
?>

<?php
// Register widget
function register_list_news_widget()
{
    register_widget('List_News_Widget');
}
add_action('widgets_init', 'register_list_news_widget');
?>