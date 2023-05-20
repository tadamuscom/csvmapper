<div class="csvm-meta-map-wrap">
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
		<div class="csvm-meta-boxes">
			<div class="csvm-meta-box">
				<div class="csvm-form-group">
					<label for="meta-field-0">Meta Name</label>
					<div class="csvm-inline-form-group">
						<input type="text" id="meta-field-0" name="meta-field-0">
						<a href="javascript:void(0)" class="csvm-open-field-list">
							<span>{$}</span>
						</a>
					</div>
				</div>
				<div class="csvm-form-group">
					<label for="meta-value-0">Meta Value</label>
					<div class="csvm-inline-form-group">
						<input type="text" id="meta-value-0" name="meta-value-0">
						<a href="javascript:void(0)" class="csvm-open-field-list">
							<span>{$}</span>
						</a>
					</div>
				</div>
				<div class="csvm-meta-boxes-controls">
					<p>-</p>
					<p>+</p>
				</div>
			</div>
		</div>
		<div class="csvm-form-group">
			<input type="submit" class="button button-primary csvm-button">
		</div>
	</form>
</div>