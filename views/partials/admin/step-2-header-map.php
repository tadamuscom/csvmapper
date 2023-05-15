<h2>Step 2 - Import Mapping</h2>
<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
	<input type="hidden" name="import_id" value="<?php echo $_GET['import_id'] ?>">
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
								<a href="javascript:void(0)" id="meta-name-settings-<?php echo csvm_convert_to_slug( $header ); ?>" class="csvm-open-field-list" group-type="meta-name" group="<?php echo csvm_convert_to_slug( $header ); ?>">
									<span>{$}</span>
								</a>
							</div>
							<div id="csvm-name-field-list-<?php echo csvm_convert_to_slug( $header ); ?>" class="csvm-field-list csvm-d-none">
								<?php foreach($headers as $sub_header): ?>
									<a href="javascript:void(0)" class="csvm-field-list-link" csvm-slug="<?php echo csvm_convert_to_slug( $sub_header ); ?>" group-type="meta-name" group="<?php echo csvm_convert_to_slug( $header ); ?>" mapping-value="{<?php echo csvm_convert_to_slug($sub_header); ?>}"><p><?php echo $sub_header; ?></p></a>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="csvm-form-group">
							<label for="meta-value-<?php echo csvm_convert_to_slug( $header ); ?>">Meta Value</label>
							<div class="csvm-inline-form-group">
								<input type="text" name="meta-value-<?php echo csvm_convert_to_slug( $header ); ?>" id="meta-value-<?php echo csvm_convert_to_slug( $header ); ?>">
								<a href="javascript:void(0)" id="meta-name-settings-<?php echo csvm_convert_to_slug( $header ); ?>" class="csvm-open-field-list" group-type="meta-value" group="<?php echo csvm_convert_to_slug( $header ); ?>">
									<span>{$}</span>
								</a>
							</div>
							<div id="csvm-value-field-list-<?php echo csvm_convert_to_slug( $header ); ?>" class="csvm-field-list csvm-d-none">
								<?php foreach($headers as $sub_header): ?>
									<a href="javascript:void(0)" class="csvm-field-list-link" csvm-slug="<?php echo csvm_convert_to_slug( $sub_header ); ?>" group-type="meta-value" group="<?php echo csvm_convert_to_slug( $header ); ?>" mapping-value="{<?php echo csvm_convert_to_slug($sub_header); ?>}"><p><?php echo $sub_header; ?></p></a>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
	<input type="submit" class="button button-primary csvm-button">

</form>