<?php
/**
 * Partial for the posts option
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

?>
<div class="csvm-d-none" id="csvm-post-wrap">
	<div class="csvm-form-group">
		<label for="csvm-post-type"><?php echo esc_html__( 'Post Type', 'csvmapper' ); ?></label>
		<select name="csvm-post-type" id="csvm-post-type">
			<?php foreach ( get_post_types() as $current_post_type ) : ?>
				<option value="<?php echo esc_attr( $current_post_type ); ?>"><?php echo esc_html( $current_post_type ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>
