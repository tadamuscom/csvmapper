<?php global $wpdb; ?>
<div class="csvm-d-none" id="csvm-custom-table-wrap">
	<div class="csvm-form-group">
		<label for="csvm-custom-table"><?php echo __( 'Database Table', 'csvmapper' ); ?></label>
		<select name="csvm-custom-table" id="csvm-custom-table">
			<?php foreach($wpdb->tables as $table): ?>
				<option value="<?php echo $table; ?>"><?php echo $table; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>