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
	$ext = pathinfo($src, PATHINFO_EXTENSION);
	if(in_sync_exts($ext)) {
		$wpurl = get_bloginfo('wpurl');
		$cdnSrc = str_replace($wpurl, UPYUN_CDN_HOST, $src);
		return $img . "=\"$cdnSrc\"";
	}
	return $img . "=\"$src\"";
}

function yet_upyun_filter_js($src) {
	if(in_sync_exts('js')) {
		$wpurl = get_bloginfo('wpurl');
		$cdnSrc = str_replace($wpurl, UPYUN_CDN_HOST, $src);
		return $cdnSrc;
	}
	return $src;
}

