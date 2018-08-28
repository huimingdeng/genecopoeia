<div class="wrap">
	<h2>WP2WPQAQ Settings:</h2>
	<div>
		<form action="options.php" method="post">
		<?php settings_fields( 'wp2wpqaq_options' ); ?>
		<?php $options=get_option("wp2wp_server_option");?>
			<table class="form-table">
				<tbody>
					<tr>	
						<th>Host Name of Target:</th>
						<td><input type="text" name="wp2wp_server_option[host]" id="wp2wp_server_option" value="<?php echo $options['host'];?>"></td>
					</tr>
					<tr>
						<th>Username on Target:</th>
						<td><input type="text" name="wp2wp_server_option[user]" id="wp2wp_server_option" autocomplete="off" value="<?php echo $options['user'];?>"></td>
					</tr>
					<tr>
						<th>Password on Target:</th>
						<td><input type="password" name="wp2wp_server_option[pass]" id="wp2wp_server_option" autocomplete="new-password" value="<?php echo $options['pass'];?>"><span class="dashicons dashicons-yes"><?php if(isset($options['authenticated'])){echo 'auth:'.$options['authenticated'];}else{echo 'auth:'.$options['api'];}?></span></td>
					</tr>
				</tbody>
			</table>
			<!-- <input type="submit" name="submit" class="button button-primary" value="保存更改"> -->
			<?php submit_button(); ?>
		</form>
	</div>
</div>