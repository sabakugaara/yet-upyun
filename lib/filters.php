<?php
function yet_upyun_filter_content($content) {
	//替换图片链接
	$string = preg_replace_callback(
		'#(<img\s[^>]*src)="([^"]+)"#',
		'yet_upyun_callback_filter_img',
		$content
	);
	//替换包裹图片的<a>链接
	$html = preg_replace_callback(
		'#(<a\s[^>]*href)="([^"]+)"#',
		'yet_upyun_callback_filter_img',
		$string
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

function yet_upyun_filter_src($src) {
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

function yet_upyun_sync_thumbnail($metadata, $attachment_id) {
	if ( ! isset( $metadata['sizes'] ) || ! is_array( $metadata['sizes'] ) ) {
		return $metadata;
	}
	$uploadarr = wp_upload_dir();
	$dir = $uploadarr['path'];
	$url = $uploadarr['url'];
	foreach ( $metadata['sizes'] as $img ) {
		$file_url = $url . DIRECTORY_SEPARATOR . $img['file'];
		if(is_cdn_file($file_url)) {
			$file_path = $dir . DIRECTORY_SEPARATOR . $img['file'];
			yet_upyun_sync_file($file_path, ABSPATH);
		}
	}
	return $metadata;
}