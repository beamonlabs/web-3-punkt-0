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
        __( 'List Beamon News', 'Beamon' ),

        // Options
        array( 'description' => __( 'Lists latest news' ) )
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
            // Flag for differences between start page and other pages
            if( is_home() || is_front_page() ) { $is_startpage = TRUE; } else { $is_startpage = FALSE; }

            // Arguments for post query
            $category_name = 'News';
            $post_count = 3;

            if( $is_startpage )
            {
                echo '<div id="news-container">';
                echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/news_small.css" rel="stylesheet" type="text/css" />';
                echo '<h2 id="news-title">' . $instance["title"] . '</h2>';
                $args = array( 'category_name' => $category_name, 'posts_per_page' => $post_count );
            }
            else
            {
                echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/news_large.css" rel="stylesheet" type="text/css" />';
                $args = array( 'category_name' => $category_name );
            }
            
            // Query for posts 
            $the_query = new WP_Query( $args );
            if ( $the_query->have_posts() ) 
            {
                if( $is_startpage )
                {
                    echo '<div id="news-post-container"><ul>';
                    while ( $the_query->have_posts() ) 
                    {
                        $the_query->the_post();
                    
                        echo '<li><a class="news-post-title" href="' . get_the_permalink() .'" rel="bookmark">' . get_the_title() .'</a>';
                        echo '</br></br><p>' . get_the_date() . '</p><p class="news-post-excerpt">' . get_the_excerpt() . '</p></li>';
                    }                 
                    echo '<li><a class="green-link" title="Nyheter" href="/Aktuellt/">Alla nyheter</a></li></ul></div>';
          
                    // Image on the right 
                    echo '<div id="right-container">';
                    echo '<div id="emphasized-circle">';
                    echo '<div id="emphasized-circle-image"><img src="' . plugin_dir_url( __FILE__ ). 'images\\jobbamedoss.jpg" alt="Jobba med oss" /></div>';
                    echo '<div id="emphasized-circle-text"><div id="emphasized-circle-wrap">';
                    echo '<h2>Vill du jobba med oss?</h2><p>Att arbeta hos Beamon kan inte beskrivas, det måste upplevas.</p>';
                    echo '<a class="green-link" title="Läs om jobb hos oss" href="/Jobb/">Läs om jobb hos oss</a></div></div></div></div></div>';
                }
                else
                {
                    echo '<div class="post-list group"><div class="post-row">';
                    $i = 1; 
                    while ( $the_query->have_posts() ) 
                    {
                        $the_query->the_post();
                                            
                        echo '<article class="group post type-post status-publish format-standard hentry"><div class="post-inner post-hover">';
                        echo '<a class="post-title" href="' . get_the_permalink() .'" rel="bookmark">' . get_the_title() .'</a></br></br>';
                        echo '<div class="published"><p>' . get_the_date() . '</p></d>';
                        echo '<div class="entry excerpt"><p>' . get_the_excerpt() . '</p></div></div></article>';

                        // Display two posts on each row 
                        if( $i % 2 == 0 ) 
                        { 
                            echo '</div><div class="post-row">'; 
                        } 

                        $i++; 
                    }
                    echo '</div></div>';
                }            
            } 
            wp_reset_postdata();
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