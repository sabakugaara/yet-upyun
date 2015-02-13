<?php
function yet_upyun_filter_content($content) {
	$html = preg_replace_callback(
		'#(<img\s[^>]*src)="([^"]+)"#',
		'yet_upyun_callback_filter_img',
		$content
	);
	return $html;
}

function yet_upyun_callback_filter_img($match) {
	list($all, $img, $src) = $match;
	if(is_cdn_file($src)) {
		$cdnSrc = yet_upyun_replace_cdn_url($src);
		return $img . "=\"$cdnSrc\"";
	}
	return $img . "=\"$src\"";
}

function yet_upyun_filter_js($src) {
	$wpurl = get_bloginfo('wpurl');
	if(strpos($src, $wpurl) === false) {
		return $src;
	}
	if(is_cdn_file($src)) {
		return yet_upyun_replace_cdn_url($src);
	}
	return $src;
}

function yet_upyun_replace_cdn_url($url) {
	$wpurl = get_bloginfo('wpurl');
	$cdnSrc = str_replace($wpurl, UPYUN_CDN_HOST, $url);
	return $cdnSrc;
}