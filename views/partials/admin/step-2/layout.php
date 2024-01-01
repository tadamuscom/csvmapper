<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly ?>
<h2><?php echo __( 'Step 2 - Import Mapping', 'csvmapper' ); ?></h2>
<?php
switch ( $import->type ) {
	case 'posts':
	case 'custom-table':
		new CSVM_View( 'partials/admin/step-2/step-2-table-map', compact( 'import' ) );

		break;
	case 'user-meta':
	case 'post-meta':
		wp_enqueue_script( 'csvmapper-meta-map' );

		new CSVM_View( 'partials/admin/step-2/step-2-meta-map', compact( 'import' ) );
}
?>