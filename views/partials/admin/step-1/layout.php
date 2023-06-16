<h2><?php __( 'Step 1 - Import Selection', 'csvmapper' ); ?></h2>
<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="action" value="csvm-file-upload">
	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('csvm-file-upload'); ?>">
	<div class="csvm-form-group">
		<label for="csv-import-type"><?php echo __( 'Import Type', 'csvmapper' ); ?></label>
		<select name="csv-import-type" id="csv-import-type">
			<option value="disabled" disabled selected><?php echo __( 'Select Type', 'csvmapper' ); ?></option>
			<?php
			foreach( CSVM_Import::$allowed_types as $type ):?>
				<option value="<?php echo csvm_convert_to_slug($type); ?>"><?php echo $type; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<?php new CSVM_View( 'partials/admin/step-1/post-meta', false, false ); ?>
	<?php new CSVM_View( 'partials/admin/step-1/user-meta', false, false ); ?>
	<?php new CSVM_View( 'partials/admin/step-1/posts', false, false ); ?>
	<?php new CSVM_View( 'partials/admin/step-1/custom-table', false, false ); ?>
	<div class="csvm-form-group">
		<label for="csv-upload"><?php echo __( 'Upload your CSV file:', 'csvmapper' ); ?></label>
		<input type="file" name="csv-upload" id="csv-upload">
		<input type="submit" class="button button-primary csvm-button">
	</div>
</form>