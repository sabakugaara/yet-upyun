<?php
function in_sync_exts($file_url) {
	$ext = yet_upyun_get_ext($file_url);
	$exts = get_option('yet_upyun_sync_ext');
	if(!$exts) {
		return false;
	}
	$exts = explode('|', $exts);
	return is_array($exts) &&
	       in_array($ext, $exts);
}

function is_cdn_file($file_url) {
	return in_sync_dir($file_url) && in_sync_exts($file_url);
}

function in_sync_dir($file_url) {
	$wpurl = get_bloginfo('wpurl');
	$dirs = get_option('yet_upyun_sync_dir');
	if(!$dirs) {
		return false;
	}
	$dirs = explode('|', $dirs);
	if(in_array('/', $dirs)) {
		return true;
	}
	if(strpos($file_url, $wpurl) !== false) {
		$dir = str_replace($wpurl . '/', '', $file_url);
		list($top_dir, $others) = explode('/', $dir, 2);
		if(in_array($top_dir, $dirs)) {
			return true;
		}
	} else {
		return false;
	}
}

function yet_upyun_get_ext($file_url) {
	$ext = pathinfo($file_url, PATHINFO_EXTENSION);
	//hack for js?ver=14034323422
	if(preg_match('!^(\w*)!', $ext, $matches)) {
		return $matches[1];
	} else {
		return $ext;
	}
}
