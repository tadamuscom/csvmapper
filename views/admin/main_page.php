<h1>CSV Mapper</h1>
<?php

if( ! isset( $_GET['import_id'] ) ){
	new CSVM_View('partials/admin/step-1');
}else{
	$import  = new CSVM_Import( $_GET['import_id'] );

}