<?php
/**
* Plugin Name: List Colleagues Widget
* Description: A widget to list and display all colleagues
* Version: 1.0
* Author: Alexandra Vasmatzis
**/
?>

<?php
class List_Colleagues_Widget extends WP_Widget 
{
    // constructor
    function __construct()
    {
        parent::__construct(

        // Base ID of the widget
        'list_Colleagues_Widget',

        // Name of the widget
        __('List Colleagues', 'Beamon'),

        // Widget options
        array('description' => __('Lists all colleagues found in the specific csv file with name and image'))
        );
    }

    // Widget form creation
    function form($instance) 
    { 
        if($instance)
        {
            $select = esc_attr($instance['select']);
        }
        else{
            $select = '';
        }

        ?>
        
        <!-- Let user decide which page the widget will be displayed in -->
        <p>
            <label for="<?php echo $this->get_field_id('select'); ?>"><?php _e('Select page', 'wp_widget_plugin'); ?></label>
            
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
        <?php
    }
    
    // widget update
    function update($new_instance, $old_instance) 
    {
        $instance = $old_instance;

        $instance['select'] = strip_tags($new_instance['select']);

        return $instance;
    }

    // Widget display
    function widget($args, $instance) 
    {
        // Display only if its the startpage or the selected page
        if(is_home() || is_front_page() || is_page($instance['select']))
        {
            // Variable for differences between start page and other page
            if(is_home() || is_front_page()) { $isStartPage = TRUE; } else { $isStartPage = FALSE; }

            // Behövs om sökvägen innehåller svenska bokstäver
            $winfilename = iconv('utf-8', 'cp1252', 'C:\Users\Beamon People gäst\Documents\My Web Sites\web3punkt0\wp-content\plugins\colleagues\csv\colleagues.csv');

            if (file_exists($winfilename))
            {
                echo "<div class='colleageContainer'>";

                if ($isStartPage)
                {
                    echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/colleaguesStartPage.css" rel="stylesheet" type="text/css" />';
                    echo "<h2 id='colleagueTitle'>Våra medarbetare</h2><a id='colleagueLink' title='Medarbetare' href='/Beamon People/'>Se alla Beamon People</a>";
                }
                else
                {
                    echo '<script type="text/javascript" src="'  . plugin_dir_url( __FILE__ ) . 'scripts/knockout-3.4.0.js"></script>';
                    
                    echo '<link href="' . plugin_dir_url( __FILE__ ) . 'css/colleaguesFullPage.css" rel="stylesheet" type="text/css" />';
                    echo "<h1 id='colleagueTitle'>Beamon People</h1><p id='colleagueText'>Visst, vi är konsulter inom IT & management som alla andra. Ändå är vi inte som dem. Vi utmanar oss själva mer, tar större ansvar och har roligt tillsammans. Förändring motiverar oss och nyfikenhet driver oss. Det är därför Beamon People finns.</p>";
                    echo "<p>First name: <strong data-bind='text: firstName'>todo</strong></p>";
                }

                echo "<div class='tableContainer'><table>";

                if (($file = fopen($winfilename, "r")) !== FALSE)
                {
                    echo "<tr>";

                    $flagFirstRow = TRUE;
                    $data = array();

                    while (($data_temp = fgetcsv($file, 1000, ";")) !== FALSE) 
	                {  
                        $data[] = $data_temp;
                    }  

                    // Remove title row and get 12 random colleagues
                    array_shift($data);

                    if($isStartPage)
                    {
                        $colleagues = array_rand($data, 12);  
                        foreach($colleagues as $colleague)
                        {
                            $name = htmlentities($data[$colleague][0], ENT_QUOTES, 'ISO-8859-1');
                            echo "<td class='colleageImage'><a href=''><img src='" . plugin_dir_url( __FILE__ ) . "images\\" . $data[$colleague][3] . "' alt='" . $name . "'></br>" . $name . "</a></td>";           
                        }
                    }
                    else
                    {
                        foreach($data as $colleague)
                        {
                            $name = htmlentities($colleague[0], ENT_QUOTES, 'ISO-8859-1');
                            echo "<td class='colleageImage'><a href=''><img src='" . plugin_dir_url( __FILE__ ) . "images\\" . $colleague[3] . "' alt='" . $name . "'></br><p>" . $name . "</p></a></td>";          
                        }
                    }
                                             
                    
                    echo "</tr>";

                    fclose($file);

                    echo "</table></div></div>";
                    echo '<script type="text/javascript" src="'  . plugin_dir_url( __FILE__ ) . 'scripts/colleaguesFullPage.js"></script>';
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