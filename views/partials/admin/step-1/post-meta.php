<?php

/**
 * Partial for the post meta option
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

?>
<div class="csvm-d-none" id="csvm-post-meta-wrap">
	<div class="csvm-form-group">
		<label for="csvm-post-ids"><?php echo __( 'Post IDs  (separated by commas)', 'csvmapper' ); ?></label>
		<input type="text" name="csvm-post-ids" id="csvm-post-ids" placeholder="Add IDs">
	</div>
</div>