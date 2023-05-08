<h1>CSV Mapper</h1>
<?php if( ! isset( $_GET['import_id'] ) ): ?>
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
	<?php
	else:
		$import = new CSVM_Import($_GET['import_id']);
		$headers = $import->get_headers();
	?>
	<h2>Step 2 - Import Mapping</h2>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
		<input type="hidden" name="import_id" value="<?php echo $_GET['import_id'] ?>">
		<input type="hidden" id="csvm-headers-list" value="<?php echo $import->get_headers_slug_list(); ?>">
		<input type="hidden" name="action" value="csvm-import-mapping">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('csvm-import-mapping'); ?>">

		<?php foreach($headers as $header): ?>
		<div class="csvm-form-group">
			<div class="csvm-map-group">
				<div class="csvm-map-cell">
					<p for="csv-header-<?php echo csvm_convert_to_slug( $header ); ?>"><?php echo csvm_convert_to_slug( $header ); ?></p>
				</div>
				<div class="csvm-map-cell">
					<p>-></p>
				</div>
				<div class="csvm-map-cell">
					<div class="csvm-meta-group">
						<div class="csvm-form-group">
							<label for="meta-name-<?php echo csvm_convert_to_slug( $header ); ?>">Meta Name</label>
							<div class="csvm-inline-form-group">
								<input type="text" name="meta-name-<?php echo csvm_convert_to_slug( $header ); ?>" id="meta-name-<?php echo csvm_convert_to_slug( $header ); ?>">
								<a href="javascript:void(0)" id="meta-name-settings-<?php echo csvm_convert_to_slug( $header ); ?>">
									<span>{$}</span>
								</a>
							</div>
						</div>
						<div class="csvm-form-group">
							<label for="meta-value-<?php echo csvm_convert_to_slug( $header ); ?>">Meta Value</label>
							<div class="csvm-inline-form-group">
								<input type="text" name="meta-value-<?php echo csvm_convert_to_slug( $header ); ?>" id="meta-value-<?php echo csvm_convert_to_slug( $header ); ?>">
								<a href="javascript:void(0)" id="meta-name-settings-<?php echo csvm_convert_to_slug( $header ); ?>">
									<span>{$}</span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
		<input type="submit" class="button button-primary csvm-button">

	</form>
	<?php endif; ?>