<div class="csvm-d-none" id="csvm-post-wrap">
	<div class="csvm-form-group">
		<label for="post-type">Post Type</label>
		<select name="post-type" id="post-type">
			<?php foreach(get_post_types() as $post_type): ?>
				<option value="<?php echo $post_type; ?>"><?php echo $post_type; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>