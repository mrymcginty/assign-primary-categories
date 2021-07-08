<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://mrymcginty.github.io/
 * @since      1.0.0
 *
 * @package    Assign_Primary_Category
 * @subpackage Assign_Primary_Category/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Assign_Primary_Category
 * @subpackage Assign_Primary_Category/includes
 * @author     Mary McGinty <mry.mcginty@gmail.com>
 */
class Assign_Primary_Category
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Assign_Primary_Category_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('ASSIGN_PRIMARY_CATEGORY_VERSION')) {
            $this->version = ASSIGN_PRIMARY_CATEGORY_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'assign-primary-category';

        $this->load_dependencies();
        $this->define_admin_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Assign_Primary_Category_Loader. Orchestrates the hooks of the plugin.
     * - Assign_Primary_Category_Admin. Defines all hooks for the admin area.

     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
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
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-assign-primary-category-loader.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-assign-primary-category-admin.php';



        $this->loader = new Assign_Primary_Category_Loader();
    }


    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Assign_Primary_Category_Admin($this->get_plugin_name(), $this->get_version());
        $plugin_settings = new Assign_Primary_Category_Admin_Settings($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        // add our custom meta box to the post edit screen
        $this->loader->add_action('admin_init', $plugin_admin, 'apc_add_custom_box');
        $this->loader->add_action('save_post', $plugin_admin, 'apc_save_primary_category_options');
        //create our settings page
        $this->loader->add_action('admin_menu', $plugin_settings, 'setup_plugin_options_menu');
        $this->loader->add_action('admin_init', $plugin_settings, 'initialize_display_options');
    }





    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Assign_Primary_Category_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
