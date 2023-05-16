<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
	<input type="hidden" name="import_id" value="<?php echo $_GET['import_id'] ?>">
	<input type="hidden" name="action" value="csvm-table-mapping">
	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('csvm-table-mapping'); ?>">

	<?php foreach($columns as $column): ?>
		<div class="csvm-form-group">
			<div class="csvm-map-group">
				<div class="csvm-map-cell">
					<p for="csv-header-<?php echo csvm_convert_to_slug( $column ); ?>"><?php echo csvm_convert_to_slug( $column ); ?></p>
				</div>
				<div class="csvm-map-cell">
					<p>-></p>
				</div>
				<div class="csvm-map-cell">
					<div class="csvm-meta-group">
						<div class="csvm-form-group">
							<label for="value-<?php echo csvm_convert_to_slug( $column ); ?>"><?php echo __( 'Value', 'csvmapper' ); ?></label>
							<div class="csvm-inline-form-group">
								<input type="text" name="value-<?php echo csvm_convert_to_slug( $column ); ?>" id="value-<?php echo csvm_convert_to_slug( $column ); ?>">
								<a href="javascript:void(0)" id="value-settings-<?php echo csvm_convert_to_slug( $column ); ?>" class="csvm-open-field-list" group="<?php echo csvm_convert_to_slug( $column ); ?>">
									<span>{$}</span>
								</a>
							</div>
							<div id="csvm-field-list-<?php echo csvm_convert_to_slug( $column ); ?>" class="csvm-field-list csvm-d-none">
								<?php foreach($headers as $header): ?>
									<a href="javascript:void(0)" class="csvm-field-list-link" csvm-slug="<?php echo csvm_convert_to_slug( $header ); ?>" group="<?php echo csvm_convert_to_slug( $column ); ?>" mapping-value="{<?php echo csvm_convert_to_slug($header); ?>}"><p><?php echo $header; ?></p></a>
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