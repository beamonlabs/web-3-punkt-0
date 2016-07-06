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
        __('List Beamon Expertises', 'Beamon'),

        // Options
        array('description' => __('Lists expertises'))
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

        <!-- Let admin decide which post that will be displayed in "Konsulten har ordet" -->    
        
        <h2>Konsulten har ordet</h2>

        <p>
            <label for="<?php echo $this->get_field_id('select_post'); ?>"><?php _e('Select post', 'wp_widget_plugin'); ?></label><br/>          
            <select name="<?php echo $this->get_field_name('select_post'); ?>" id="<?php echo $this->get_field_id('select_post'); ?>">

            <?php
                $posts = get_posts();
                foreach ($posts as $post) 
                {
                    $option = '<option value="' . $post->ID . '"';
                    $option .= selected($instance['select_post'], $post->ID);
                    $option .= '>' . $post->post_title;
                    $option .= '</option>';
                    echo $option;
                }
            ?>
            </select>
        </p>

        <!-- Let admin choose author -->
        <p>
            <label for="<?php echo $this->get_field_id('author'); ?>"><?php _e('Author'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('author'); ?>" id="<?php echo $this->get_field_id('author'); ?>" value="<?php echo $instance['author']?>" style="width: 100%">
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
        $instance['select_post'] = strip_tags($new_instance['select_post']);
        $instance['author'] = strip_tags($new_instance['author']);
        return $instance;
    }

    function widget($args, $instance) 
    {
        // Display only on page that admin selected
        if(is_page($instance['select']))
        {
            // Flag for differences between start page and other pages
            if(is_home() || is_front_page()) { $isStartPage = TRUE; } else { $isStartPage = FALSE; }

            echo '<div id="expertise_intro_container">';

            if ($isStartPage)
            {
                echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/expertises_small.css" rel="stylesheet" type="text/css" />';            
            }
            else
            {
                echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/expertises_large.css" rel="stylesheet" type="text/css" />';
                echo '<h2 id="expertise_intro_title">' . $instance["title"] . '</h2><p id="intro_text">'  . $instance["sub_title"] .  '</p>';
            }

            if($isStartPage)
            {
                
                $selected_post = get_post($instance["select_post"]);
          
                echo '<div id="left_container">';
                echo '<div id="emphasized-circle">';
                echo '<div id="image"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\konsultenharordet.jpg" alt="Konsulten har ordet"></div>';
                echo '<div id="text"><div id="wrap">';
                echo '<h2>Konsulten har ordet</h2><p>"' . $selected_post->post_title . '" - ' . $instance["author"] . '</p><a id="green" title="Läs artikeln" href="' . get_permalink($selected_post->ID) . '">Läs artikeln</a></div></div></div></div>';

                echo '<div id="right_container">';
                echo '<div id="text_container"><h2 id="expertise_intro_title">' . $instance["title"] . '</h2><p id="expertise_text">'  . $instance["sub_title"] .  '</p><a class="green" title="Kompetenser" href="/Vad-vi-gor/">Läs om våra kompetenser</a></div>';
                echo '<li class="expertise_image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\strategi.png" alt="Strategi"></div><p>Strategi</p></li>';           
                echo '<li class="expertise_image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\ledning.png" alt="Ledning"></div><p>Ledning</p></li>';
                echo '<li class="expertise_image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\krav.png" alt="Krav"></div><p>Krav</p></li>';           
                echo '<li class="expertise_image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\arkitektur-utveckling.png" alt="Arkitektur och utveckling"></div><p>Arkitektur/</br>Utveckling</p></li>';
                echo '<li class="expertise_image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\ux-webb.png" alt="UX/Webb"></div><p>UX/Webb</p></li>';           
                echo '<li class="expertise_image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\qa-test.png" alt="QA/qa-test"></div><p>QA/qa-test</p></li>';
                echo '</div>';
            }
            else
            {
                echo '<div class="expertise"><li class="expertise_image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\strategi.png" alt="Strategi"></div><div class="expertise_text"><h2 class="expertise_title">Strategi</h2><p class="expertise_desc">Visst är vi i grunden ett specialiserat IT-bolag men med hög andel klassiska strategi- och managementuppdrag. Kanske därför att IT idag är en affärskritisk process i så många bolag. Kanske därför att vi genomfört så många förändringsprojekt i allt från små startups till globala storbolag. Tack vare vår kunskap om teknik kan vi ofta både genomföra analysen och föreslå en strategi samt designa lösningen och implementera den.</p></li></div>';           
                echo '<div class="expertise"><li class="expertise_image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\ledning.png" alt="Ledning"></div><div class="expertise_text"><h2 class="expertise_title">Ledning</h2><p class="expertise_desc">Att leda en organisation, ett projekt eller ett team låter kanske enkelt. Allt som behövs är väl ett gemensamt mål och sedan påverka alla att springa åt samma håll? Men ibland är det rätt så invecklat. Människor är personligheter med olika drömmar, drivkrafter, kompetenser, preferenser och motiv. Organisationer utvecklas och förändras – eller stelnar och stagnerar. I situationer som kräver tydligt ledarskap tar vi gärna på oss ledartröjan och blir din projektledare, teamansvarig, linjechef, coach eller bollplank till den befintliga ledningen.</p></li></div>';
                echo '<div class="expertise"><li class="expertise_image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\krav.png" alt="Krav"></div><div class="expertise_text"><h2 class="expertise_title">Krav</h2><p class="expertise_desc">Som man ropar får man svar, otydliga krav ger en diffus leverans. En korrekt och komplett kravspec gör det lättare för dig att sätta mål och sedan mäta om ni kom hela vägen. Det är rena rutinen för oss att leda och assistera arbetet med att identifiera, prioritera, beskriva och dokumentera krav. Vårt fokus är alltid på effekt och affärsnytta. Ett framgångsrikt kravarbete innefattar både organisation, medarbetare och leverantörer och vi kan leda, strukturera och dokumentera hela processen på ett metodiskt sätt mot alla parter.</p></li></div>';           
                echo '<div class="expertise"><li class="expertise_image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\arkitektur-utveckling.png" alt="Arkitektur och utveckling"></div><div class="expertise_text"><h2 class="expertise_title">Arkitektur och utveckling</h2><p class="expertise_desc">Behovsanpassad arkitektur och programvaruutveckling inom webb, mobil, desktop, integration och server. Spetskompetens för retail, finans och myndigheter. Tjänsterna omfattar webb-, mobil- och systemutvecklare, systemintegratörer samt lösnings-, system- och verksamhetsarkitekter. Ofta uppfinner vi hjulet på nytt men ställer ändå alltid krav på oss själva att allt ska vara robust och skalbart. Ett förslag från oss har det signum att det alltid baseras på etablerade tekniker och designmönster och att lösningen är testbar, lättförvaltad och lätt att förstå.</p></li></div>';
                echo '<div class="expertise"><li class="expertise_image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\ux-webb.png" alt="UX/Webb"></div><div class="expertise_text"><h2 class="expertise_title">UX/Webb</h2><p class="expertise_desc">All framgångsrik IT utgår från användaren. Hur leds besökaren genom en webbshop från entré ut genom kassan? Hur får en handläggare snabbt fram det bästa beslutsunderlaget? Hur rapporterar den verkställande ledningen enklast till styrelsen? Vi erbjuder allt från själva strategiarbetet med UX och webb i hela organisationen till själva hantverket: Målgruppsanalyser, användningstester, webbutveckling, webbdesign och prototyping.</p></li></div>';           
                echo '<div class="expertise"><li class="expertise_image"><div class="bubble"><img src="' . plugin_dir_url( __FILE__ ) . 'images\\qa-test.png" alt="QA/qa-test"></div><div class="expertise_text"><h2 class="expertise_title">QA/qa-test</h2><p class="expertise_desc">Quality Assurance - kvalitetssäkring på svenska - är det systematiska jobbet med att säkerställa hög kvalitet i motsats till kvalitetskontroll som enbart mäter kvalitetsnivån mot ett förutbestämt värde. Våra QA-uppdrag har genomförts för såväl hela organisationer som i enstaka projekt. Vi arbetar framgångsrikt framför allt med analys och strategier och tekniker för att definiera rätt kvalitetsnivå. Vi kan teorierna, vi behärskar hantverket och vi har gjort det många gånger. Vårt erbjudande är rådgivning för kvalitetsarbete, tester med spetskompetens inom prestandatestning, testautomatisering, testdesign och testledning. Typiskt för oss är att våga utmana inarbetade rutiner och arbetssätt. Vi kan identifiera de lämpligaste metoderna för att sätta den önskade och den mest kostnadseffektiva kvalitetsnivån.</p></li></div>';
            }
             
            echo '</ul></div></div>';             
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