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
	<form method="POST" action="options.php">
		<?php settings_fields('yet-upyun-file');?>
		<?php do_settings_sections('yet-upyun-file');?>
		<table class="form-table" >
			<tr valign="top">
				<th scope="row">静态文件目录</th>
				<td>
					<input type="text" name="yet_upyun_sync_dir" value="<?php echo  esc_attr( get_option('yet_upyun_sync_dir') ); ?>" placeholder="wp-content|wp-includes"/>
					<p>
						<span>多个目录以 | 分隔</span>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">需要上传到CDN的文件后缀</th>
				<td>
					<input type="text" name="yet_upyun_sync_ext" value="<?php echo esc_attr( get_option('yet_upyun_sync_ext') ); ?>" placeholder="js|css|png|jpg|jpeg"/>
					<span>多个后缀以 | 分隔</span>
				</td>
			</tr>
		</table>
		<?php submit_button('保存设置');?>
		<?php if (get_option('yet_upyun_sync_dir') && get_option('yet_upyun_sync_ext')): ?>
				<input type="button" onclick="sync_file();" class="button button-primary" value="同步文件"/>
		<?php endif;?>
	</form>
</div>

