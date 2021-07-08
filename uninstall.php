<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$apc_post_chooser_options = 'apc_post_chooser_options';


// // for site options in Multisite
// delete_site_option($option_name);
if (is_multisite()) {
    delete_site_option($apc_post_chooser_options);
} else {
    delete_option($apc_post_chooser_options);
}
// // drop a custom database table
// global $wpdb;
// $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}mytable");