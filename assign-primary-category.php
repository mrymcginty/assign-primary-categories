<?php

/**
 * Plugin Name:       Assign Primary Category
 * Description:       Enables primary categories and taxonomies to be assigned to posts and custom post types. This plugin also provides the ability to easily display the primary category or taxonomy on the front end. 
 * Version:           1.0
 * Requires at least: 4.0
 * Requires PHP:      5.4
 * Author:            Mary McGinty
 * Author URI:        https://mrymcginty.github.io/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       assign-primary-category
 */



// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Current plugin version.
 * Update this for every version release - https://semver.org
 */
define('ASSIGN_PRIMARY_CATEGORY_VERSION', '1.0.0');


/**
 * Plugin activation.
 * see includes/class-assign-primary-category-activator.php
 */
function activate_assign_primary_category()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-assign-primary-category-activator.php';
    Assign_Primary_Category_Activator::activate();
}

/**
 * Plugin deactivation.
 * see includes/class-assign-primary-category-deactivator.php
 */
function deactivate_assign_primary_category()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-assign-primary-category-deactivator.php';
    Assign_Primary_Category_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_assign_primary_category');
register_deactivation_hook(__FILE__, 'deactivate_assign_primary_category');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-assign-primary-category.php';

/**
 * Begins execution of the plugin.
 */
function run_assign_primary_category()
{

    $plugin = new Assign_Primary_Category();
    $plugin->run();
}
run_assign_primary_category();
