<div class="wrap">
    <h2> 又拍云绑定空间基本设置</h2>
    <form method="POST" action="options.php">
        <?php settings_fields('yet-upyun-basic');?>
        <?php do_settings_sections('yet-upyun-basic');?>
        <table class="form-table" >
            <tr valign="top">
                <th scope="row">域名</th>
                <td>
                    <input type="text" name="host" value="<?php echo esc_attr( get_option('host') ); ?>" placeholder="必须http(s)://开头，例如: http://file-b.b0.upaiyun.com"/>
                    <span>填写又拍云绑定的域名</span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">空间名</th>
                <td><input type="text" name="bucket_name" value="<?php echo esc_attr( get_option('bucket_name') ); ?>" />
                    <span>填写域名绑定的空间名</span>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">操作员名</th>
                <td>
                    <input type="text" name="operator_name" value="<?php echo esc_attr( get_option('operator_name') ); ?>" />
                    <span>上一步所填空间名对应的操作员姓名</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">操作员密码</th>
                <td>
                    <input type="text" name="operator_pwd" value="<?php echo esc_attr( get_option('operator_pwd') ); ?>" />
                    <span>上一步所填空间名对应的操作员姓名</span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    空间类型
                </th>
                <td>
                    <input type="radio" name="bucket_type" value="file" <?php if(get_option('bucket_type') == 'file') echo 'checked';?>/><label>文件空间</label>
                    <input type="radio" name="bucket_type" value="image" <?php if(get_option('bucket_type') == 'image') echo 'checked';?>/><label>图片空间</label>
                    <input type="radio" name="bucket_type" value="cdn" <?php if(get_option('bucket_type') == 'cdn') echo 'checked';?>/><label>CDN空间</label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">文件存放目录</th>
                <td>
                    <input type="text" name="bucket_dir" value="<?php echo esc_attr( get_option('bucket_dir') ); ?>" placeholder="wp_path"/>
                </td>
            </tr>
        </table>
        <?php submit_button();?>
    </form>
</div>
