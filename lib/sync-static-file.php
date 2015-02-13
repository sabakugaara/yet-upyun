<?php
include_once YET_UPYUN_DIR . '/lib/UpyunSdk/upyun.class.php';
$upyun = new UpYun(
	UPYUN_CDN_BUCKET_NAME,
	UPYUN_CDN_OPERATOR_NAME,
	UPYUN_CDN_OPERATOR_PWD
);
/**
 * just sync static file in $source_dir
 * @param $source_dir
 */

function yet_upyun_sync_dir($source_dir, $base_path, $exts) {
	global $upyun;
	if(! is_dir($source_dir) || ! ($dir = opendir($source_dir))) {
		return ;
	}

	while(($file = readdir($dir)) !== false) {
		if($file == '.' || $file == '..') {
			continue;
		}
		$path = $source_dir . '/' . $file;
		if(is_dir($path)) {
			yet_upyun_sync_dir($path, $base_path, $exts);
		} else {
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			if(in_array($ext, $exts)) {
				$upload_path = str_replace($base_path, '/', $path);
				$auto_make_dir = true;
				$upyun->writeFile($upload_path, fopen($path, 'rb'), $auto_make_dir );
			}
		}
	}
	closedir($dir);
}

function yet_upyun_sync_file_callback() {
	global $wpdb; // this is how you get access to the database
	$sync_dir = get_option('yet_upyun_sync_dir');
	$sync_ext = get_option('yet_upyun_sync_ext');
	$sync_dirs = explode('|', $sync_dir);
	$sync_exts = explode('|', $sync_ext);
	foreach($sync_dirs as $dir) {
		yet_upyun_sync_dir( ABSPATH . $dir, ABSPATH , $sync_exts);
	}
	wp_die(); // this is required to terminate immediately and return a proper response
}

function yet_upyun_sync_file($file_path, $base_path) {
	global $upyun;
	$upload_path = str_replace($base_path, '/', $file_path);
	$auto_make_dir = true;
	$upyun->writeFile($upload_path, fopen($file_path, 'rb'), $auto_make_dir );
}

function yet_upyun_sync_attachment($attachement_id) {
	$file_url = wp_get_attachment_url($attachement_id);
	$wpurl = get_bloginfo('wpurl');
	if(is_cdn_file($file_url)) {
		$file_path = str_replace($wpurl . '/', ABSPATH, $file_url);
		yet_upyun_sync_file($file_path, ABSPATH);
	}
}
