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

define('MENU_SLUG', 'yet-upyun');
define('YET_UPYUN_DIR', dirname(__FILE__));
define('YET_UPYUN_TEMPLATE_DIR', YET_UPYUN_DIR . '/template');

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

include YET_UPYUN_DIR . '/lib/functions.php';
include 'lib/sync-static-file.php';
include 'lib/filters.php';

add_action('admin_menu', 'yet_upyun_menu');
add_action('admin_init', 'yet_upyun_init');

function yet_upyun_menu() {
    add_menu_page(
        '又拍云加速',
        '又拍云加速',
        'manage_options',
        MENU_SLUG,
        'yet_upyun_menu_page');
}



if(! is_admin()) {
    $upyun_service = true;
    if($upyun_service) {
        add_filter('the_content', 'yet_upyun_filter_content');
        add_filter('script_loader_src', 'yet_upyun_filter_js');
    }
} else {
    //手动同步所有文件
    add_action('wp_ajax_yet_upyun_sync_file', 'yet_upyun_sync_file_callback');
    //上传附件时 同步原图
    add_action('add_attachment', 'yet_upyun_sync_attachment');
    //上传附件时 同步缩略图
    //由于没有找到合适的action， 试用该filter作为钩子
    add_filter('wp_generate_attachment_metadata', 'yet_upyun_sync_thumbnail', 10, 2);
}

function yet_upyun_init() {
    yet_upyun_register_basic_settings();
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
    register_setting( 'yet-upyun-basic', 'yet_upyun_sync_dir' );
    register_setting( 'yet-upyun-basic', 'yet_upyun_sync_ext');
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
    include YET_UPYUN_TEMPLATE_DIR . '/header.php';
}