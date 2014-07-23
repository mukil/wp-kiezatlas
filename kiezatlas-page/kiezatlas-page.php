<?php
/*
    Plugin Name: Kiezatlas Page
    Plugin URI: https://github.com/mukil/wp-kiezatlas
    Description: Renders all categories and data-entries of a single city map, into a single interactive wordpress page.
    Version: 1.0-SNAPSHOT
    Author: Malte Rei&szlig;ig
    Author URI: http://www.mikromedia.de
    License: GPLv3

    Copyright 2013 Malte Reißig  (email : malte@mikromedia.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

    About this file:
    This plugin-file should just make sure that the two page-templates are set up properly 
    with their various JS and CSS dependencies. And yes, the various data is fetched from 
    within the two page-templates.

*/

/** Make this configurable */
define( 'KIEZATLAS_PLUGIN_PATH', plugin_dir_path(__FILE__) );
/** define( 'KIEZATLAS_CITYMAP_ID', 't-ka-schoeneberg' );
define( 'KIEZATLAS_WORKSPACE_ID', 't-ka-workspace' ); **/

function get_kiezatlas_page($template) {
    global $post;
    if ($post->post_type == 'kiezatlas_page') {

        $template = KIEZATLAS_PLUGIN_PATH . '/kiezatlas-page-template.php';

    } else if ($post->post_type == 'kiezatlas_entry') {

        wp_enqueue_style('leaflet7', 'http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.css');
        wp_enqueue_script('leaflet7-src', 'http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.js');
        $template = KIEZATLAS_PLUGIN_PATH . '/kiezatlas-entry-template.php';

    } else if ($post->post_type == 'post') {
        // 
        $topicId = get_post_custom_values('Kiezatlas Topic ID', $post->ID);
        if (count($topicId) > 0) {
            wp_enqueue_style('leaflet7', 'http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.css');
            wp_enqueue_script('leaflet7-src', 'http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.js');
        }
    }
    return $template;
}

function create_post_types() {

    if (post_type_exists('kiezatlas_page')) {
        echo 'The Kiezatlas-Page type already exists.';
    } else {
        register_post_type( 'kiezatlas_page',
            array(
                'labels' => array(
                    'name' => __( 'Kiezatlas Pages' ),
                    'singular_name' => __( 'Kiezatlas Page' )
                ),
                'public' => true,
                'has_archive' => false,
                'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'custom-fields', 'page-attributes', ),
                'taxonomies' => array( 'category', 'post_tag' ),
                'rewrite' => array('slug' => 'kiezatlas-citymap'),
            )
        );
    }

    if (post_type_exists('kiezatlas_entry')) {
        echo 'The Kiezatlas-Entry type already exists.';
    } else {
        create_kiezatlas_entry_post_type();
    }

}


function create_kiezatlas_entry_post_type() {

    $labels = array(
        'name'                => _x( 'Kiezatlas Entries', 'Post Type General Name', 'text_domain' ),
        'singular_name'       => _x( 'Kiezatlas Entry', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'           => __( 'Kiezatlas Entry', 'text_domain' ),
        'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
        'all_items'           => __( 'All Kiezatlas Entries', 'text_domain' ),
        'view_item'           => __( 'View Kiezatlas Entry', 'text_domain' ),
        'edit_item'           => __( 'Edit Kiezatlas Entry', 'text_domain' ),
        'update_item'         => __( 'Update Kiezatlas Entry', 'text_domain' ),
        'search_items'        => __( 'Search Kiezatlas Entry', 'text_domain' ),
        'not_found'           => __( 'Kiezatlas Entry Not Found', 'text_domain' ),
        'not_found_in_trash'  => __( 'Kiezatlas Entry Not Found in Trash', 'text_domain' ),
    );
    $rewrite = array(
        'slug'                => 'kiezatlas-entry',
        'with_front'          => true,
        'pages'               => true,
        'feeds'               => true,
    );
    $args = array(
        'label'               => __( 'kiezatlas_entry', 'text_domain' ),
        'description'         => __( 'Kiezatlas Data Entry', 'text_domain' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'custom-fields', 'page-attributes', ),
        'taxonomies'          => array( 'category', 'post_tag' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'can_export'          => false,
        'has_archive'         => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'rewrite'             => $rewrite,
        'capability_type'     => 'post',
    );
    register_post_type( 'kiezatlas_entry', $args );

}

function kiezatlas_rewrite_flush() {
    create_post_types();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}

function register_topic_id_parameter($qvars) {
    $qvars[] = 'topicId';
    return $qvars;
}

function add_kiezatlas_page_header() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_script( 'kiezatlas', plugins_url('ka-SNAPSHOT.js', __FILE__) );
    wp_register_style( 'prefix-style', plugins_url('style.css', __FILE__) );
    wp_enqueue_style( 'prefix-style' );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'kiezatlas' );
}

/** function add_my_post_types_to_query( $query ) {
    if ( is_home() && $query->is_main_query() )
        $query->set( 'post_type', array( 'post', 'page', 'kiezatlas_entry' ) );
    return $query;
}
add_action( 'pre_get_posts', 'add_my_post_types_to_query' ); **/

add_action( 'init', 'create_post_types' );
add_filter( 'template_include', 'get_kiezatlas_page' );
add_filter( 'query_vars', 'register_topic_id_parameter' );
register_activation_hook( __FILE__, 'kiezatlas_rewrite_flush' );
add_action( 'wp_enqueue_scripts', 'add_kiezatlas_page_header' );

?>
