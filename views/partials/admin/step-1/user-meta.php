<?php

/**
 * Partial for the user meta option
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

?>
<div class="csvm-d-none" id="csvm-user-meta-wrap">
	<div class="csvm-form-group">
		<label for="csvm-user-ids"><?php echo __( 'User IDs  (separated by commas)', 'csvmapper' ); ?></label>
		<input type="text" name="csvm-user-ids" id="csvm-user-ids" placeholder="Add IDs">
	</div>
</div>