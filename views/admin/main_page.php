<h1>CSV Mapper</h1>
<?php if( ! isset( $_GET['import_id'] ) ): ?>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="action" value="csvm-file-upload">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('csvm-file-upload'); ?>">
		<div class="csvm-form-group">
			<label for="csv-upload">Upload your CSV file:</label>
			<input type="file" name="csv-upload" id="csv-upload">
			<input type="submit" class="button button-primary csvm-button">
		</div>
	</form>
<?php
else:
	$import = new CSVM_Import($_GET['import_id']);
//	$headers = $import->get_headers();

	?>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
		<input type="hidden" name="import_id" value="<?php echo $_GET['import_id'] ?>">
		<input type="hidden" name="action" value="csvm-headers-choice">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('csvm-headers-choice'); ?>">
	</form>
	<?php
endif;
	?>