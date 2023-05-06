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

<?php endif; ?>