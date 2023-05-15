<h2>Step 1 - Import Selection</h2>
<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="action" value="csvm-file-upload">
	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('csvm-file-upload'); ?>">
	<div class="csvm-form-group">
		<label for="csv-import-type">Import Type</label>
		<select name="csv-import-type" id="csv-import-type">
			<option value="disabled" disabled selected>Select Type</option>
			<?php
			foreach(CSVM_Import::$allowed_types as $type):?>
				<option value="<?php echo csvm_convert_to_slug($type); ?>"><?php echo $type; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<?php new CSVM_View( 'partials/admin/step-1/post-meta' ); ?>
	<?php new CSVM_View( 'partials/admin/step-1/user-meta' ); ?>
	<?php new CSVM_View( 'partials/admin/step-1/posts' ); ?>
	<?php new CSVM_View( 'partials/admin/step-1/custom-table' ); ?>
	<div class="csvm-form-group">
		<input type="checkbox" name="enable-batched-rows" id="csvm-enable-batched-rows">
		<label class="csvm-inline-label" for="csvm-enable-batched-rows"> Enable Batched Rows</label>
	</div>
	<?php new CSVM_View( 'partials/admin/step-1/batched-rows' ) ?>
	<div class="csvm-form-group">
		<label for="csv-upload">Upload your CSV file:</label>
		<input type="file" name="csv-upload" id="csv-upload">
		<input type="submit" class="button button-primary csvm-button">
	</div>
</form>