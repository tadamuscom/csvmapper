<?php

/**
 * Partial for the table map
 *
 * @package csvmapper
 * @author Tadamus <hello@tadamus.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$headers = $import->get_headers();
$columns = $import->get_db_table_columns( $import->table );
?>

<div class="csvm-table-map-wrap">
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
		<input type="hidden" name="import_id" value="<?php echo esc_attr( $import->id ); ?>">
		<input type="hidden" name="action" value="csvm-table-mapping">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'csvm-table-mapping' ); ?>">

		<?php
		foreach ( $columns as $column ) :
			if ( $column === 'post_type' ) {
				continue;
			}
			?>
			<div class="csvm-form-group">
				<div class="csvm-map-group">
					<div class="csvm-map-cell">
						<p for="csv-header-<?php echo  esc_attr( csvm_convert_to_slug( $column ) ); ?>"><?php echo esc_attr( csvm_convert_to_slug( $column ) ); ?></p>
					</div>
					<div class="csvm-map-cell">
						<p>-></p>
					</div>
					<div class="csvm-map-cell">
						<div class="csvm-meta-group">
							<div class="csvm-form-group">
								<label for="<?php echo esc_attr( csvm_convert_to_slug( $column ) ); ?>"><?php echo __( 'Value', 'csvmapper' ); ?></label>
								<div class="csvm-inline-form-group">
									<input type="text" name="value-<?php echo esc_attr( csvm_convert_to_slug( $column ) ); ?>" id="<?php echo esc_attr( csvm_convert_to_slug( $column ) ); ?>" value="<?php echo esc_attr( CSVM_Table_Validator::get_default( 'posts', $column ) ); ?>">
									<a href="javascript:void(0)" id="value-settings-<?php echo esc_attr( csvm_convert_to_slug( $column ) ); ?>" class="csvm-open-field-list" group="<?php echo esc_attr( csvm_convert_to_slug( $column ) ); ?>">
										<span>{$}</span>
									</a>
								</div>
								<div id="csvm-field-list-<?php echo esc_attr( csvm_convert_to_slug( $column ) ); ?>" class="csvm-field-list csvm-d-none">
									<?php foreach ( $headers as $header ) : ?>
										<p>
										<a href="javascript:void(0)" class="csvm-field-list-link" csvm-slug="<?php echo esc_attr( csvm_convert_to_slug( $header ) ); ?>" group="<?php echo esc_attr( csvm_convert_to_slug( $column ) ); ?>" mapping-value="<?php echo esc_attr( csvm_convert_to_slug( $header ) ); ?>"><?php echo esc_html( $header ); ?></a>
										</p>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<input type="submit" class="button button-primary csvm-button">

	</form>
</div>