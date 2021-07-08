=== Plugin Name ===
Contributors: Mary McGinty
Tags: categories, taxonomies
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A Simple plugin to assign a Primary Category or Taxonomy to a post or custom post type.



### Wordpress Plugin

# Assign Primary Categories

A Simple plugin to assign a Primary Category or Taxonomy to a post or custom post type.

## Features

- Compatible with Gutenberg and Classic Editor
- Compatible with Posts and Custom Post Types
- Compatible with multiple custom Taxonomies
- Can be enabled/disabled for post via Settings > Primary Category Options

## Theme Usage

### Default Categories

```
$primary_category_id = get_post_meta(get_the_ID(), 'apc_primary_category', true);

$primary_category_name = get_term($primary_category_id)->name;
```

### Custom Taxonomies

The meta key for taxonomies is saved as apc-primary\_ _< your-taxonomy-slug>_

```
$primary_taxonomy_id = get_post_meta(get_the_ID(), 'apc_primary_< your-taxonomy-slug>', true);

$primary_taxonomy_name = get_term($primary_taxonomy_id)->name;
```

> Utisizes the Wordpress Plugin Boilerplate -
> a standardized, organized, object-oriented foundation for building high-quality WordPress Plugins. https://github.com/DevinVinson/WordPress-Plugin-Boilerplate.
