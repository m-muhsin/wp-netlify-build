<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

settings_errors();
?>
<div class="wrap">
	<h1><?php _e( 'WP Netlify Build', 'wp-netlify-build' ); ?></h1>
	<form action="<?php echo admin_url( 'options-general.php?page=wp-netlify-build' ); ?>" method="POST">
		<?php wp_nonce_field( 'wp_netlify_build_options', 'wp_netlify_build_nonce' ); ?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e( 'Publish to Netlify', 'wp-netlify-build' ); ?></th>
				<td><a href="<?php echo self::_publish_to_netlify_url(); ?>" class="button button-primary"><?php _e( 'Publish Now', 'wp-netlify-build' ); ?></a></td>
			</tr>
			
			<tr>
				<th scope="row"><?php _e( 'Netlify Settings', 'wp-netlify-build' ); ?></th>
        <td><p>Create build hook in https://app.netlify.com/sites/{{ site_name }}/settings/deploys#build-hooks and paste the URL below.</p></td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'Netlify Build Hook', 'wp-netlify-build' ); ?></th>
        <td>
          <input type='text' name='wp_netlify_build_options[netlify][build_hook]' value='<?php echo $options['netlify']['build_hook'] ?>' style="width: 500px; max-width: 100%;">
        </td>
			</tr>
			<tr>
				<th scope="row">&nbsp;</th>
				<td><input type="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'wp-netlify-build' ); ?>"></td>
			</tr>
		</table>
	</form>
</div>
