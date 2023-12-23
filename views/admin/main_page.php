<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<h1>CSV Mapper</h1>
<?php

if( ! isset( $_GET['import_id'] ) ){
	wp_enqueue_script( 'csvmapper-first-step' );

	new CSVM_View('partials/admin/step-1/layout', false, true);
}else if( ! empty( $_GET['step'] == 2 ) ){
	wp_enqueue_script( 'csvmapper-mapping' );

	$import  = new CSVM_Import( sanitize_text_field( $_GET['import_id'] ) );

	new CSVM_View('partials/admin/step-2/layout', compact('import'), false);
}else if (! empty ( $_GET['step'] == 3 )){
	wp_enqueue_script( 'csvmapper-third-step' );

	$import  = new CSVM_Import( sanitize_text_field( $_GET['import_id'] ) );

	new CSVM_View('partials/admin/step-3/layout', compact('import'), true);
}