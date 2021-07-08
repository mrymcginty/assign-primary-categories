<?php

/**
 * The settings of the plugin.
 *
 * @link       http://devinvinson.com
 * @since      1.0.0
 *
 * @package    Wppb_Demo_Plugin
 * @subpackage Wppb_Demo_Plugin/admin
 */

/**
 * Class WordPress_Plugin_Template_Settings
 *
 */
class Assign_Primary_Category_Admin_Settings
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }



    /**
     * This function introduces the plugin options as a sub page of 'Settings'
     */
    public function setup_plugin_options_menu()
    {

        //Add the menu to the Plugins set of menu items
        add_options_page(
            'Assign Primary Category Options',                     // The title to be displayed in the browser window for this page.
            'Primary Category Options',                    // The text to be displayed for this menu item
            'manage_options',                    // Which type of users can see this menu item
            'apc_plugin_options',            // The unique ID - that is, the slug - for this menu item
            array($this, 'render_settings_page_content')                // The name of the function to call when rendering this menu's page
        );
    }
    /**
     * Provides default values for the Display Options.
     *
     * @return array
     */
    public function default_display_options()
    {

        $defaults = array(
            'apc_primary_category'        =>    ''
        );

        return $defaults;
    }


    /**
     * Renders a simple page to display for the theme menu defined above.
     */
    public function render_settings_page_content($active_tab = '')
    {
?>
        <!-- Create a header in the default WordPress 'wrap' container -->
        <div class=" wrap apc_settings_wrap">

            <h2><?php _e('Assign Primary Category', 'assign-primary-category'); ?></h2>



            <form method="post" action="options.php">
                <?php
                settings_fields('apc_post_chooser_options');
                do_settings_sections('apc_post_chooser_options');
                submit_button();

                ?>
            </form>

        </div><!-- /.wrap -->
<?php
    }


    /**
     * Initializes the theme's display options page by registering the Sections,
     * Fields, and Settings.
     *
     * This function is registered with the 'admin_init' hook.
     */
    public function initialize_display_options()
    {

        // If the theme options don't exist, create them.
        if (false == get_option('apc_post_chooser_options')) {
            $default_array = $this->default_display_options();
            add_option('apc_post_chooser_options', $default_array);
        }


        add_settings_section(
            'general_settings_section',                        // ID used to identify this section and with which to register options
            __('Plugin Settings', 'assign-primary-category'),                // Title to be displayed on the administration page
            array($this, 'general_options_callback'),        // Callback used to render the description of the section
            'apc_post_chooser_options'                        // Page on which to add this section of options
        );

        // Next, we'll introduce the fields for toggling the visibility of content elements.
        add_settings_field(
            'apc_primary_category',                                // ID used to identify the field throughout the theme
            __('Choose Posts', 'assign-primary-category'),                    // The label to the left of the option interface element
            array($this, 'apc_choose_custom_posts_callback'),    // The name of the function responsible for rendering the option interface
            'apc_post_chooser_options',                // The page on which this option will be displayed
            'general_settings_section',                    // The name of the section to which this field belongs

        );


        // Finally, we register the fields with WordPress
        register_setting(
            'apc_post_chooser_options',
            'apc_post_chooser_options'
        );
    } // end wppb-demo_initialize_theme_options


    /**
     * This function provides a simple description for the General Options page.
     *
     */
    public function general_options_callback()
    {
        $options = get_option('apc_post_chooser_options');
        echo '<p class="border-bottom">' . __('Choose which post types to activate this feature on:', 'assign-primary-category') . '</p>';
    } // 



    /**
     * This function renders the icheckboxes for choosing which post types should show the APC metabox
     *
     * It saves an array of post types in wp_options, option_name 'apc_post_chooser_options'
     */
    public function apc_choose_custom_posts_callback($args)
    {

        // First, we read the options collection
        $options = get_option('apc_post_chooser_options');

        $args = array(
            'public'   => true,
            '_builtin' => false,
        );

        $html = '';
        //add a checkbox for standard posts
        in_array('post', $options['apc_primary_category']) ? $post_checked = 'checked' : $post_checked = '';
        $html .= '<p>';
        $html .= '<input type="checkbox" name="apc_post_chooser_options[apc_primary_category][]" value="post" ' . $post_checked . ' />';
        $html .= '<label for="apc_primary_category">&nbsp;Posts</label>';
        $html .= '</p>';


        //list all custom post types in our install
        $output = 'names'; // names or objects, note names is the default
        $operator = 'and'; // 'and' or 'or'
        $post_types = get_post_types($args, $output, $operator);
        $i = 0;
        foreach ($post_types as $post_type) {
            $obj = get_post_type_object($post_type);
            $html .= '<p>';
            $checked = 'checked';
            if (!empty($options)) {
                in_array($post_type, $options['apc_primary_category']) ? $checked = 'checked' : $checked = '';
            } else {
                $checked = '';
            }
            $html .= '<input type="checkbox" name="apc_post_chooser_options[apc_primary_category][]" value="' . $post_type . '" ' . $checked . ' />';
            $html .= '<label for="apc_primary_category">&nbsp;'  . $obj->labels->singular_name  . '</label>';
            $html .= '</p>';
        }




        echo $html;
    } // end apc_choose_custom_posts_callback


}
