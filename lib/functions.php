<?php
function in_sync_exts($ext) {
	$exts = get_option('yet_upyun_sync_ext');
	if(!$exts) {
		return false;
	}
	$exts = explode('|', $exts);
	return is_array($exts) &&
	       in_array($ext, $exts);
}
