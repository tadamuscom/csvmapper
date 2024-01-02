<?php
/**
 * Partial for the custom table option
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

global $wpdb;
?>
<div class="csvm-d-none" id="csvm-custom-table-wrap">
	<div class="csvm-form-group">
		<label for="csvm-custom-table"><?php echo esc_html__( 'Database Table', 'csvmapper' ); ?></label>
		<select name="csvm-custom-table" id="csvm-custom-table">
			<?php foreach ( $wpdb->tables as $table ) : ?>
				<option value="<?php echo esc_attr( $table ); ?>"><?php echo esc_html( $table ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>
