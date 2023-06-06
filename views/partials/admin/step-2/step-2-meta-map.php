<div class="csvm-meta-map-wrap">
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
		<input type="hidden" name="import_id" value="<?php echo $import->id; ?>">
		<input type="hidden" name="action" value="csvm-meta-mapping">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('csvm-meta-mapping'); ?>">
		<input type="hidden" id="csvm-headers-list" name="headers" value='<?php echo $import->get_headers_json(); ?>'>
		<input type="hidden" id="csvm-headers-slug-list" name="headers-slug" value='<?php echo $import->get_headers_slugs_json(); ?>'>
		<div class="csvm-meta-boxes" id="csvm-meta-boxes">
			<?php /* Don't worry, some Javascript magic will add the boxes here! */ ?>
		</div>
		<div class="csvm-meta-boxes-controls">
			<p id="csvm-controls-plus">+</p>
			<p id="csvm-controls-minus">-</p>
		</div>
		<div class="csvm-form-group">
			<input type="submit" class="button button-primary csvm-button">
		</div>
	</form>
</div>