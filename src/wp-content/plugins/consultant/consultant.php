<?php
/**
* Plugin Name: List Consultant Articles Widget
* Description: A widget to list Beamon Consultant articles
* Version: 1.0
* Author: Alexandra Vasmatzis
**/
class List_Consultant_Articles_Widget extends WP_Widget 
{
    function __construct()
    {
        parent::__construct(

        // Base ID
        'list_consultant_widget',

        // Name
        __( 'List Beamon Consultant articles', 'Beamon' ),

        // Options
        array( 'description' => __('Lists Consultant articles' ))
        );
    }

    // Admin form creation
    function form( $instance ) 
    { 
        if( $instance )
        {
            $title = esc_attr( $instance['title'] );

        }
        else
        {
            $title = '';
        }
        ?>    
            
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
        $instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;
    }

    function widget( $args, $instance ) 
    {
        // Display only on startpage
        if( is_home() || is_front_page() ) 
        {  
            // Arguments for post query
            $category_name = 'Consultant';
            $post_count = 4;
            ?>

            <div id="consultant-container">
                <link href="<?php echo plugin_dir_url( __FILE__ )?>css/consultant.css" rel="stylesheet" type="text/css" />    
                <div class="startpage-widget" id="consultant-min-height"> 
                      
                    <?php
                    $args = array( 'category_name' => $category_name, 'posts_per_page' => $post_count );

                    // Query for posts 
                    $the_query = new WP_Query( $args );
                    if ( $the_query->have_posts() ) 
                    {             
                        $the_query->the_post();
                        ?>
                
                        <!-- Left container -->         
                        <div class="half-width float-left">
                            <div class="emphasized-circle">
                                <?php echo the_post_thumbnail(); ?>
                                <div class="emphasized-circle-text">
                                    <div class="emphasized-circle-wrap">  

                                        <?php
                                        // Get the first tag of the post (the author)
                                        $allposttags = get_the_tags();
                                        $i=0;
                                        if ( $allposttags ) {
                                            foreach( $allposttags as $tags ) {
                                                $i++;
                                                if ( 1 == $i ) {
                                                    $firsttag = $tags->name;
                                                }
                                            }
                                        }
                                        ?>
                    
                                        <p id="title" class="fat">"<?php echo get_the_title() ?>"</p></br>
                                        <p><?php echo get_the_date() ?>, <?php echo $firsttag ?></p></br>
                                        <a class="green-link" title="Läs artikeln" href="<?php echo get_the_permalink() ?>">Läs artikeln</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                    <!-- Right container -->
                    <div class="half-width float-right">
                        <h2 class="align-left"><?php echo $instance["title"] ?></h2>
                        <ul>
                            <?php
                            while ( $the_query->have_posts() ) 
                            {
                
                                $the_query->the_post();

                                    // Get the first tag of the post (the author)
                                $allposttags = get_the_tags();
                                $i=0;
                                if ( $allposttags ) {
                                    foreach( $allposttags as $tags ) {
                                        $i++;
                                        if ( 1 == $i ) {
                                            $firsttag = $tags->name;
                                        }
                                    }
                                }
                                ?>
                                <li class="align-left">
                                    <article>
                                        <a class="post-title" href="<?php echo get_the_permalink() ?>" rel="bookmark"><?php echo get_the_title() ?></a>                                 
                                        </br>
                                        </br>
                                        <div class="published"></div><p><?php echo get_the_date() ?>, <?php echo $firsttag ?></p>
                                        <p class="consultant-post-excerpt"><?php echo get_the_excerpt() ?></p>
                                    </article>
                                </li>
                            <?php
                            }      
                            ?>
                            </ul>
                        </div>
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
function register_list_consultant_widget()
{
    register_widget('List_Consultant_Articles_Widget');
}
add_action('widgets_init', 'register_list_consultant_widget');
?>