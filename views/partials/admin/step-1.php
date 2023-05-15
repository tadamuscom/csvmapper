<h2>Step 1 - Import Selection</h2>
<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="action" value="csvm-file-upload">
	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('csvm-file-upload'); ?>">
	<div class="csvm-form-group">
		<label for="csv-import-type">Import Type</label>
		<select name="csv-import-type" id="csv-import-type">
			<option value="postmeta">Post Meta</option>
			<option value="usermeta">User Meta</option>
			<option value="posts">Posts</option>
		</select>
	</div>
	<div class="csvm-form-group">
		<label for="csv-upload">Upload your CSV file:</label>
		<input type="file" name="csv-upload" id="csv-upload">
		<input type="submit" class="button button-primary csvm-button">
	</div>
</form>