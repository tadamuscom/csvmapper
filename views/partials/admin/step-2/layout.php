<h2><?php echo __( 'Step 2 - Import Mapping', 'csvmapper' ); ?></h2>
<?php
	switch ($import->type){
		case 'posts':
		case 'custom-table':
			$headers = $import->get_headers();
			$columns = $import->get_db_table_columns($import->table);

			new CSVM_View('partials/admin/step-2/step-2-table-map', compact(array('headers', 'columns')));

			break;
	}
?>