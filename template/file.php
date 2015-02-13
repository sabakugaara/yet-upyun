<?php
add_action( 'admin_footer', 'yet_upyun_sync_file_js' );

function yet_upyun_sync_file_js(){
?>
<script type="text/javascript" >
	function sync_file(){
		jQuery.post(ajaxurl, {action: 'yet_upyun_sync_file'}, function(response) {
			alert(response);
			//alert('正在同步中...');
		});
	}
</script>
<?php
}
?>
<div class="wrap">
	<h2> 又拍云服务设置</h2>
		<?php if (get_option('yet_upyun_sync_dir') && get_option('yet_upyun_sync_ext')): ?>
				<input type="button" onclick="sync_file();" class="button button-primary" value="同步文件"/>
			<?php else:?>
			请先通过又拍云设置目录
		<?php endif;?>
</div>

