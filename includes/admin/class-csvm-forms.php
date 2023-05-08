<?php

if( ! class_exists( 'CSVM_Forms' ) ){
    class CSVM_Forms{
        public function __construct()
        {
            add_action( 'admin_post_csvm-file-upload', array( $this, 'upload_form_callback' ) );
			add_action( 'admin_post_csvm-import-mapping', array( $this, 'mapping_form_callback' ) );
        }

	    /**
	     * The callback method for the upload form
	     *
	     * @since 1.0
	     *
	     * @return void
	     */
	    public function upload_form_callback(): void
	    {
			if(!empty($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'csvm-file-upload')){
				if($_FILES['csv-upload']['type'] === 'text/csv'){
					$file = wp_handle_upload( $_FILES['csv-upload'], array(
						'test_form' => false,
						'test_size' => true,
					) );

					$import = new CSVM_Import();
					$import->process($file);
					$import->type = $_POST['csv-import-type'];
					$import->save();

					csvm_redirect( admin_url( 'admin.php?page=csvmapper' ) . '&import_id=' . $import->id );
				}else{
					csvm_redirect( admin_url( 'admin.php?page=csvmapper' ), 'error', __('The only files allowed are CSV files', 'csvmapper' ) );
				}

				return;
			}
		}

	    public function mapping_form_callback(): void
	    {
		    if( !empty( $_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'csvm-import-mapping' ) ){
				$names = array();
				$values = array();

				foreach ( $_POST as $key => $value ){
					if( empty( $value ) ){
						csvm_redirect( admin_url( 'admin.php?page=csvmapper&import_id=' . $_POST['import_id'] ), 'error', __('All fields must be filled', 'csvmapper') );

						return;
					}

					if( str_starts_with( $key, 'meta-name' ) ){
						$new_key = str_replace( 'meta-name-', '' ,$key );
						$names[$new_key] = $value;
					}

					if( str_starts_with( $key, 'meta-value' ) ){
						$new_key = str_replace( 'meta-value-', '' ,$key );
						$values[$new_key] = $value;
					}
				}

			    $import = new CSVM_Import($_POST['import_id']);
			    $headers = $import->get_headers();

				echo '<pre>';
				print_r($_POST);
				echo '</pre>';
		    }
		}
    }

    new CSVM_Forms();
}