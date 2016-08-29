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
            ?>

            <div id="news-container">
                <link href="<?php echo plugin_dir_url( __FILE__ )?>css/news.css" rel="stylesheet" type="text/css" />

                <?php
                if( $is_startpage )
                {
                ?>
                    <div id="news-startpage-widget" class="startpage-widget"> 
                    <h2 class="align-left"><?php echo $instance["title"]?> </h2>          
                    <?php
                    $args = array( 'category_name' => $category_name, 'posts_per_page' => $post_count );
                }
                else
                {
                    $args = array( 'category_name' => $category_name );
                }
            
                // Query for posts 
                $the_query = new WP_Query( $args );
                if ( $the_query->have_posts() ) 
                {
                    if( $is_startpage )
                    {
                    ?>
                        <div class="half-width float-left align-left">
                            <ul>

                            <?php
                            while ( $the_query->have_posts() ) 
                            {
                                $the_query->the_post();
                                ?>

                                <li>
                                    <a class="news-post-title" href="<?php echo get_the_permalink() ?>" rel="bookmark"><?php echo get_the_title() ?></a>
                                    </br></br><p><?php echo get_the_date() ?></p><p class="news-post-excerpt"><?php echo get_the_excerpt() ?></p>
                                </li>

                            <?php
                            }                 
                            ?>
                                <li>
                                    <a class="green-link" title="Nyheter" href="/Aktuellt/">Alla nyheter</a>
                                </li>
                            </ul>
                        </div>

                        <!-- Image on the right --> 
                        <div class="float-right half-width">
                            <div class="emphasized-circle">
                                <div class="emphasized-circle-image">
                                    <img src="<?php echo plugin_dir_url( __FILE__ ) ?>images\jobbamedoss.jpg" alt="Jobba med oss" />
                                </div>
                                <div class="emphasized-circle-text">
                                    <div class="emphasized-circle-wrap">
                                        <h2>Vill du jobba med oss?</h2>
                                        <p>Att arbeta hos Beamon kan inte beskrivas, det måste upplevas.</p>
                                        <a class="green-link" title="Läs om jobb hos oss" href="/Jobb/">Läs om jobb hos oss</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                <?php
                }
                else
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
                                        <a class="post-title" href="<?php echo get_the_permalink() ?>" rel="bookmark"><?php echo get_the_title() ?></a></br></br>
                                        <div class="published">
                                            <p><?php echo get_the_date() ?></p>
                                        </div>
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
            } 
            wp_reset_postdata();
            ?>
            </div>
        <?php
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