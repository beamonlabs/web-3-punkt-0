<?php
/**
* Plugin Name: List Expertises Widget
* Description: A widget to list Beamon expertises
* Version: 1.0
* Author: Alexandra Vasmatzis
**/
?>

<?php
class List_Expertises_Widget extends WP_Widget 
{
    function __construct()
    {
        parent::__construct(

        // Base ID
        'list_expertises_widget',

        // Name
        __( 'List Beamon Expertises', 'Beamon' ),

        // Options
        array( 'description' => __('Lists expertises' ))
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
            
        <!-- Let admin choose sub title (only for other pages than start page) -->
        <p>
            <label for="<?php echo $this->get_field_id( 'sub_tile' ); ?>"><?php _e( 'Sub title (not for start page)' ); ?></label><br/>
            <textarea name="<?php echo $this->get_field_name( 'sub_title' ); ?>" id="<?php echo $this->get_field_id( 'sub_title' ); ?>" rows="5" style="width: 100%"><?php echo $instance['sub_title']?></textarea>
        </p>

        <!-- Let admin decide which post that will be displayed in "Konsulten har ordet" -->    
        
        <h2>Konsulten har ordet</h2>

        <p>
            <label for="<?php echo $this->get_field_id( 'select_post' ); ?>"><?php _e( 'Select post', 'wp_widget_plugin' ); ?></label><br/>          
            <select name="<?php echo $this->get_field_name( 'select_post' ); ?>" id="<?php echo $this->get_field_id( 'select_post' ); ?>">

            <?php
                $args = array( 'category_name' => 'Consultant' );
                $the_query = new WP_Query( $args );
                while ( $the_query->have_posts() ) 
                {
                    $the_query->the_post();
                    $id = get_the_ID();
                    $title = get_the_title();
                    $option = '<option value="' . $id . '"';
                    $option .= selected( $instance['select_post'], $id );
                    $option .= '>' . $title;
                    $option .= '</option>';
                    echo $option;
                }
            ?>
            </select>
        </p>

        <!-- Let admin choose author -->
        <p>
            <label for="<?php echo $this->get_field_id( 'author' ); ?>"><?php _e( 'Author' ); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name( 'author' ); ?>" id="<?php echo $this->get_field_id( 'author' ); ?>" value="<?php echo $instance['author']?>" style="width: 100%">
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
        $instance['select_post'] = strip_tags( $new_instance['select_post'] );
        $instance['author'] = strip_tags( $new_instance['author'] );
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
            $category_name = 'Expertises';

            echo '<div id="expertise-container">';

            if ( $is_startpage )
            {
                echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/expertises_small.css" rel="stylesheet" type="text/css" />';            
            }
            else
            {
                echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/expertises_large.css" rel="stylesheet" type="text/css" />';
                echo '<h2 id="expertise-title">' . $instance["title"] . '</h2><p id="intro-text">'  . $instance["sub_title"] .  '</p></div>';              
                $args = array( 'category_name' => $category_name );
            }

            if( $is_startpage )
            {            
                $selected_post = get_post( $instance["select_post"] );
          
                echo '<div id="left-container">';
                echo '<div id="emphasized-circle">';
                echo '<div id="emphasized-circle-image"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\konsultenharordet.jpg" alt="Konsulten har ordet"></div>';
                echo '<div id="emphasized-circle-text"><div id="emphasized-circle-wrap">';
                echo '<h2>Konsulten har ordet</h2><p>"' . $selected_post->post_title . '" - ' . $instance["author"] . '</p><a id="green-link" title="Läs artikeln" href="' . get_permalink( $selected_post->ID ) . '">Läs artikeln</a></div></div></div></div>';

                echo '<div id="right-container">';
                echo '<div id="text-container"><h2 id="expertise_intro_title">' . $instance["title"] . '</h2><p id="expertise_text">'  . $instance["sub_title"] .  '</p><a class="green" title="Kompetenser" href="/Vad-vi-gor/">Läs om våra kompetenser</a></div>';
                echo '<li class="expertise-image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\strategi.png" alt="Strategi"></div><p>Strategi</p></li>';           
                echo '<li class="expertise-image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\ledning.png" alt="Ledning"></div><p>Ledning</p></li>';
                echo '<li class="expertise-image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\krav.png" alt="Krav"></div><p>Krav</p></li>';           
                echo '<li class="expertise-image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\arkitektur-utveckling.png" alt="Arkitektur och utveckling"></div><p>Arkitektur/</br>Utveckling</p></li>';
                echo '<li class="expertise-image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\ux-webb.png" alt="UX/Webb"></div><p>UX/Webb</p></li>';           
                echo '<li class="expertise-image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\qa-test.png" alt="QA/qa-test"></div><p>QA/qa-test</p></li>';
                echo '</div>';
                echo '</ul></div>';
            }
            else
            {
                // Arguments for post query
                $the_query = new WP_Query( $args );
                if ( $the_query->have_posts() ) 
                {
                    echo '<div class="post-list group"><div class="post-row">';
                    $i = 1; 
                    while ( $the_query->have_posts() ) 
                    {
                        $the_query->the_post();
                                            
                        echo '<article class="group post type-post status-publish format-standard hentry"><div class="post-inner post-hover">';
                        echo '<a class="post-title" href="' . get_the_permalink() .'" rel="bookmark">' . get_the_title() .'</a></br>';
                        echo '<div><p>' . get_the_content() . '</p></div></div></article>';

                        // Display three posts on each row 
                        if( $i % 3 == 0 ) 
                        { 
                            echo '</div><div class="post-row">'; 
                        } 

                        $i++; 
                    }
                    echo '</div>';
                }
                wp_reset_postdata();
            }                   
        }
    }
}
?>

<?php
// Register widget
function register_list_expertises_widget()
{
    register_widget('List_Expertises_Widget');
}
add_action('widgets_init', 'register_list_expertises_widget');
?>