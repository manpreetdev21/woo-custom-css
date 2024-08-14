<?php 

$class_array = array();

foreach( glob( WOO_EDITING__DIR. 'include/main/*.php' ) as $file ) {
	include_once $file;
	$class_array[] = strtoupper( basename( str_replace( '.php', '', $file ) ) );	
}

foreach( array_unique( $class_array ) as $class_name ) {
	new $class_name;
}