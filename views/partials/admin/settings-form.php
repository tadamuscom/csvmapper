<?php
/**
 * Partial for the settings form
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

?>
<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="action" value="csvm-settings">
	<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'csvm-settings' ) ); ?>">
	<div class="csvm-form-group">
		<label for="csvm-cron-interval-number"><?php echo esc_html__( 'WP Cron Interval', 'csvmapper' ); ?></label>
		<div class="csvm-double-field-form-group">
			<input type="number" name="csvm-cron-interval-number" id="csvm-cron-interval-number" value="<?php echo ( get_option( 'csvm_cron_interval_number' ) && ! empty( get_option( 'csvm_cron_interval_number' ) ) ) ? esc_attr( get_option( 'csvm_cron_interval_number' ) ) : '1'; ?>" <?php echo ( ! get_option( 'csvm_enable_cron_task' ) || get_option( 'csvm_enable_cron_task' ) === 'false' ) ? 'disabled="true"' : ''; ?>>
			<select name="csvm-cron-interval-period" id="csvm-cron-interval-period" <?php echo ( ! get_option( 'csvm_enable_cron_task' ) || get_option( 'csvm_enable_cron_task' ) === 'false' ) ? 'disabled="true"' : ''; ?>>
				<option value="seconds" <?php echo ( get_option( 'csvm_cron_interval_interval' ) === 'seconds' ) ? 'selected' : ''; ?>>Seconds</option>
				<option value="minutes" <?php echo ( get_option( 'csvm_cron_interval_interval' ) === 'minutes' ) ? 'selected' : ''; ?>>Minutes</option>
				<option value="hours" <?php echo ( get_option( 'csvm_cron_interval_interval' ) === 'hours' ) ? 'selected' : ''; ?>>Hours</option>
				<option value="days" <?php echo ( get_option( 'csvm_cron_interval_interval' ) === 'days' ) ? 'selected' : ''; ?>>Days</option>
			</select>
		</div>
	</div>
	<div class="csvm-form-group">
		<input type="checkbox" name="csvm-enable-cron" id="csvm-enable-cron" value="true" <?php echo ( get_option( 'csvm_enable_cron_task' ) && get_option( 'csvm_enable_cron_task' ) === 'true' ) ? 'checked' : ''; ?>>
		<label for="csvm-enable-cron" class="csvm-inline-label"><?php echo esc_html__( 'Enable WP Cron task', 'csvmapper' ); ?></label>
	</div>
	<input type="submit" class="button button-primary csvm-button">
</form>
