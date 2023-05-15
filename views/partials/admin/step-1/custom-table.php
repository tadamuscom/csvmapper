<?php global $wpdb; ?>
<div class="csvm-d-none" id="csvm-custom-table-wrap">
	<div class="csvm-form-group">
		<label for="post-type">Database Table</label>
		<select name="post-type" id="post-type">
			<?php foreach($wpdb->tables as $table): ?>
				<option value="<?php echo $table; ?>"><?php echo $table; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>