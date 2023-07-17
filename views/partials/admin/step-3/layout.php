<div class="csvm-step-three-wrap">
	<h3>Summary</h3>
	<div class="csvm-summary">
		<div class="csvm-summary-row">
			<p class="csvm-summary-subtitle"><b><?php echo __( 'File Path', 'csvmapper' ); ?></b></p>
			<p><?php echo $import->file_path; ?></p>
		</div>
		<div class="csvm-summary-row">
			<p class="csvm-summary-subtitle"><b><?php echo __( 'File URL', 'csvmapper' ); ?></b></p>
			<p><?php echo $import->file_url; ?></p>
		</div>
		<div class="csvm-summary-row">
			<p class="csvm-summary-subtitle"><b><?php echo __( 'Headers', 'csvmapper' ); ?></b></p>
			<ul>
				<?php foreach( $import->headers as $header ): ?>
					<li><?php echo $header; ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="csvm-summary-row">
			<p class="csvm-summary-subtitle"><b><?php echo __( 'Type', 'csvmapper' ); ?></b></p>
			<p><?php echo $import->type; ?></p>
		</div>
		<?php if( $import->type === 'post-meta' || $import->type === 'user-meta' ): ?>
		<div class="csvm-summary-row">
			<p class="csvm-summary-subtitle"><b><?php echo __( 'IDs', 'csvmapper' ); ?></b></p>
			<ul>
			<?php foreach( $import->ids as $id ): ?>
				<li><?php echo $id; ?></li>
			<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
		<?php if( $import->type === 'custom-table' || $import->type === 'posts' ): ?>
		<div class="csvm-summary-row">
			<p class="csvm-summary-subtitle"><b><?php echo __( 'Table', 'csvmapper' ); ?></b></p>
			<p><?php echo $import->table; ?></p>
		</div>
		<?php endif; ?>
		<div class="csvm-summary-row">
			<p class="csvm-summary-subtitle"><b><?php echo __( 'Template', 'csvmapper' ); ?></b></p>
			<div class="csvm-summary-template-wrap">
				<?php foreach( $import->template as $key => $value ): ?>
						<div class="csvm-summary-template-row">
							<div class="csvm-summary-template-col">
								<p><?php echo $key; ?></p>
							</div>
							<div class="csvm-summary-template-col">
								<p>=></p>
							</div>
							<div class="csvm-summary-template-col">
								<p><?php echo $value; ?></p>
							</div>
						</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="csvm-step-three-form-wrap">
		<h3 id="csvm-step-three-heading">Last Settings</h3>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" id="csvm-step-three-form">
            <input id="import_id" type="hidden" name="import_id" value="<?php echo $import->id; ?>">
            <input type="hidden" name="action" value="csvm-last-step">
            <input id="form-nonce" type="hidden" name="nonce" value="<?php echo wp_create_nonce('csvm-last-step'); ?>">
			<div class="csvm-form-group">
				<label for="csvm-execution-type"><?php echo __( 'Execution Type', 'csvmapper' ); ?></label>
				<select name="csvm-execution-type" id="csvm-execution-type">
					<option disabled selected><?php echo __( 'Select the execution type', 'csvmapper' ); ?></option>
					<option value="php"><?php echo __( 'Migrate through PHP', 'csvmapper' ); ?></option>
					<option value="wp-cron"><?php echo __( 'Migrate through WP-CRON', 'csvmapper' ); ?></option>
                    <option disabled><?php echo __( 'Migrate through AJAX (Coming Soon)', 'csvmapper' ); ?></option>
				</select>
			</div>
			<div class="csvm-form-group csvm-d-none" id="csvm-number-of-rows">
				<label for="csvm-number-of-rows-select"><?php echo __( 'Number of rows per process', 'csvmapper' ); ?></label>
				<select name="csvm-number-of-rows" id="csvm-number-of-process-select">
					<option disabled selected><?php echo __( 'Select the number of rows', 'csvmapper' ); ?></option>
					<option value="1"><?php echo __( '1', 'csvmapper' ); ?></option>
					<option value="5"><?php echo __( '5', 'csvmapper' ); ?></option>
					<option value="10"><?php echo __( '10', 'csvmapper' ); ?></option>
					<option value="50"><?php echo __( '50', 'csvmapper' ); ?></option>
					<option value="100"><?php echo __( '100', 'csvmapper' ); ?></option>
					<option value="500"><?php echo __( '500', 'csvmapper' ); ?></option>
				</select>
			</div>
			<div class="csvm-form-group">
				<input type="submit" class="button button-primary csvm-button" value="<?php echo __( 'Start Import', 'csvmapper' ); ?>">
			</div>
		</form>

        <?php new CSVM_View( 'partials/admin/loader', false, false ); ?>

        <p id="csvm-progress-logger" class="csvm-d-none"></p>

	</div>
</div>