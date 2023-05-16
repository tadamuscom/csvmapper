<h1>CSV Mapper</h1>
<?php

if( ! isset( $_GET['import_id'] ) ){
	wp_enqueue_script( 'csvmapper-first-step' );

	new CSVM_View('partials/admin/step-1/layout');
}else{
	wp_enqueue_script( 'csvmapper-mapping' );

	$import  = new CSVM_Import( $_GET['import_id'] );

	new CSVM_View('partials/admin/step-2/layout', compact('import'));
}