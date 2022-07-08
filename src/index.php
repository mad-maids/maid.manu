<?php
header("Content-Type: text/html; charset=UTF-8");

define( 'ENVIRONMENT' , isset( $_SERVER['APP_ENV'] ) ? $_SERVER['APP_ENV'] : 'development' );

const APP_VERSION = '0.2.1';

switch ( ENVIRONMENT )
{
	case 'development':
		//error_reporting(~E_ERROR); 
		error_reporting(E_ERROR); 
		ini_set( 'display_errors', 1);
	break;

	case 'testing':
	case 'production':
		ini_set( 'display_errors', 0 );
		if ( version_compare( PHP_VERSION, '5.3', '>=' ) )
		{
			error_reporting( E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED );
		}
		else
		{
			error_reporting( E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE );
		}
	break;

	default:
		header( 'HTTP/1.1 503 Service Unavailable.', TRUE, 503 );
		echo 'The application environment is not set correctly.';
		exit( 1 );
}

define( 'SELF', pathinfo( __FILE__, PATHINFO_BASENAME ) );

define( 'FCPATH', dirname( __FILE__  ).DIRECTORY_SEPARATOR );

define( 'CONFIGPATH', FCPATH.'config'.DIRECTORY_SEPARATOR );

define( 'CONTPATH', FCPATH.'controllers'.DIRECTORY_SEPARATOR );

define( 'MODELPATH', FCPATH.'models'.DIRECTORY_SEPARATOR );

define( 'HLPPATH', FCPATH.'helpers'.DIRECTORY_SEPARATOR );

define( 'VIEWPATH', FCPATH.'view'.DIRECTORY_SEPARATOR );

foreach ( array_merge( glob( CONFIGPATH."*.php" ), glob( HLPPATH."*.php" ), glob( CONTPATH."*.php" ), glob( MODELPATH."*.php" ) ) as $filename )
{
	require_once $filename;
}
_sanitize_globals();
set_error_handler( function ( $errno, $errstr, $errfile, $errline ) {
	$ignore = ( $errno & error_reporting() ) == 0;
    if( !$ignore ){
    	$error = error_get_last();
    	set_status_header( 500 );
    	show_php_error( $errno, $errstr, $errfile, $errline );
    }
    return TRUE;
});

foreach ($router as $url => $action) {
	$status = route( $url, function( ...$args ) use ( $url, $action ) {
		$class = 'Controller\\'.$action[0];
		if ( class_exists( $class, FALSE ) && method_exists( $class, $action[1]) ) {
			$controller = new $class;
			call_user_func_array( [ $controller, $action[1] ], $args );
			exit;
		}
	});

	if( $status ) break;
}

show_404();
