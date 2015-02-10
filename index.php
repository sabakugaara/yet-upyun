<?php
/*
Plugin Name: Yet Another Upyun Plugin For Wordpress
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description:
Version:  1.0
Author: Hubbert
Author URI: http://URI_Of_The_Plugin_Author作者地址
Text Domain: yet_upyun
 */
add_action('admin_menu', 'yet_upyun_menu');
add_action('admin_init', 'yet_upyun_init');

define('MENU_SLUG', 'yet-upyun');
define('YET_UPYUN_DIR', dirname(__FILE__));
include YET_UPYUN_DIR . '/lib/functions.php';

function yet_upyun_menu() {
    add_menu_page(
        '又拍云加速',
        '又拍云加速',
        'manage_options',
        MENU_SLUG,
        'yet_upyun_menu_page');
}

if(get_option('host')) {
    define('UPYUN_CDN_HOST', get_option('host'));
}
if(get_option('bucket_name')) {
    define('UPYUN_CDN_BUCKET_NAME', get_option('bucket_name'));
}
if(get_option('operator_name')) {
    define('UPYUN_CDN_OPERATOR_NAME', get_option('operator_name'));
}
if(get_option('operator_pwd')) {
    define('UPYUN_CDN_OPERATOR_PWD', get_option('operator_pwd'));
}
if(get_option('bucket_type')) {
    define('UPYUN_CDN_BUCKET_TYPE', get_option('bucket_type'));
}
if(get_option('bucket_dir')) {
    define('UPYUN_CDN_BUCKET_DIR', get_option('bucket_dir'));
}

if(! is_admin()) {
    $upyun_service = true;
    if($upyun_service) {
        include 'lib/filters.php';
        add_filter('the_content', 'yet_upyun_filter_content');
        add_filter('script_loader_src', 'yet_upyun_filter_js');
    }
} else {
    include 'lib/sync-static-file.php';
    add_action( 'wp_ajax_yet_upyun_sync_file', 'yet_upyun_sync_file_callback' );
}

function yet_upyun_init() {
    yet_upyun_register_basic_settings();
    yet_upyun_register_file_settings();
    /*
    $plugin_dir = basename( dirname( __FILE__ ) );
    load_plugin_textdomain( 'yet_upyun', null, $plugin_dir );
    */
}


function yet_upyun_register_basic_settings() {
    register_setting( 'yet-upyun-basic', 'host' );
    register_setting( 'yet-upyun-basic', 'bucket_name' );
    register_setting( 'yet-upyun-basic', 'operator_name' );
    register_setting( 'yet-upyun-basic', 'operator_pwd' );
    register_setting( 'yet-upyun-basic', 'bucket_type' );
    register_setting( 'yet-upyun-basic', 'bucket_dir' );
}

function yet_upyun_register_file_settings() {
    register_setting( 'yet-upyun-file', 'yet_upyun_sync_dir' );
    register_setting( 'yet-upyun-file', 'yet_upyun_sync_ext' );
}

function yet_upyun_admin_tabs($current = 'basic') {
    $tabs = array( 'basic' => '又拍云设置', 'file' => '文件设置');
    $html = '<div id="icon-themes" class="icon32"><br></div>';
    $html .= '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        $html .= "<a class='nav-tab$class' href='?page=" . MENU_SLUG . "&tab=$tab'>$name</a>";
    }
    $html .= '</h2>';
    return $html;
}

function yet_upyun_menu_page() {
    $dir = plugin_dir_path(__FILE__);
    include $dir . 'template/header.php';
}
