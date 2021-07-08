<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://mrymcginty.github.io/
 * @since      1.0.0
 *
 * @package    Assign_Primary_Category
 * @subpackage Assign_Primary_Category/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Assign_Primary_Category
 * @subpackage Assign_Primary_Category/admin
 * @author     Mary McGinty <mry.mcginty@gmail.com>
 */
class Assign_Primary_Category_Admin
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

        $this->load_dependencies();
    }


    /**
     * Load the required dependencies for the Admin facing functionality.
     *
     * Include the following files that make up the plugin:
     *
     * - Wppb_Demo_Plugin_Admin_Settings. Registers the admin settings and page.
     *
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) .  'admin/class-assign-primary-category-settings.php';
    }


    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/assign-primary-category-admin.css', array(), $this->version, 'all');
    }






    public function apc_add_custom_box()
    {
        $myoptions = get_option('apc_post_chooser_options');

        //e.g. $screens = ['post', 'book'];
        $screens = $myoptions['apc_primary_category'];

        foreach ($screens as $screen) {
            add_meta_box(
                'apc_set_primary_category',   // Unique ID
                'Set Primary Category',      // Box title
                array($this, "apc_display_primary_category_options"),  // Content callback, must be of type callable
                $screen,  // Post types
                'side' //position
            );
        }
    }

    public function apc_display_primary_category_options($post)
    {

        if ($post->post_type === "post") {
            //standard post type - we can use built in category functions
            $primary_category_current = get_post_meta($post->ID, 'apc_primary_category', true);
            echo '<div class="apc_metabox">';
            echo '<label for="apc_choose_primary_category">Select the primary category for this post:</label>';
            $wp_dropdown_args = array(
                'hide_empty' => false,
                'hierarchical' => true,
                'name' => 'apc_choose_primary_category',
                'selected' => $primary_category_current
            );
            wp_dropdown_categories($wp_dropdown_args);
            echo '</div>';
        } else {
            $taxonomies = get_object_taxonomies($post->post_type);
            foreach ($taxonomies as $taxonomy) {

                $primary_category_current = get_post_meta($post->ID, 'apc_primary_' . $taxonomy, true);

                $tax_object = get_taxonomy($taxonomy); //get taxonomy as an object so we can access it's name / label properties
                echo '<div class="apc_metabox">';
                echo '<label for="apc_choose_primary_' . $taxonomy . '">Select the primary ' . $tax_object->labels->singular_name . ' for this ' . $post->post_type . ':</label>';
                $wp_dropdown_args = array(
                    'hide_empty' => false,
                    'hierarchical' => true,
                    'name' => 'apc_choose_primary_' . $taxonomy,
                    'selected' => $primary_category_current,
                    'taxonomy'           => $taxonomy
                );
                wp_dropdown_categories($wp_dropdown_args);
                echo '</div>';
            }
        }
    }


    function apc_save_primary_category_options($post_id)
    {
        $post_type = get_post_type($post_id);

        //default category saving for standard post
        if ($post_type == 'post' && array_key_exists('apc_choose_primary_category', $_POST)) {
            //first we save category id to apc_primary_category field in wp_options
            update_post_meta(
                $post_id,
                'apc_primary_category',
                $_POST['apc_choose_primary_category']
            );
            //then we check if this category has already been assigned to the post, if not we add it
            $cat_name = get_cat_name($_POST['apc_choose_primary_category']);
            //wp_set_object_terms($post_id, $cat_name, 'category', true);
            wp_set_post_categories($post_id, $_POST['apc_choose_primary_category'], true);
        } else {
            //taxonomy saving
            $taxonomies = get_object_taxonomies($post_type);
            foreach ($taxonomies as $taxonomy) {
                $array_key = 'apc_choose_primary_' . $taxonomy;
                if (array_key_exists($array_key, $_POST)) {
                    update_post_meta(
                        $post_id,
                        'apc_primary_' . $taxonomy,
                        $_POST[$array_key]
                    );
                    $term_name = get_term($_POST[$array_key])->name;
                    wp_set_object_terms($post_id, $term_name, $taxonomy, true);
                }
            }
        }
    }
}
