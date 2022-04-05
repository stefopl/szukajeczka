<?php
/**
 * Plugin Name: Disable notice deprecated with elementor
 * Version: 1.0.1
 * Author: Artur Stefański
 */
new Disable_Notice_Deprecated_With_Elementor;

class Disable_Notice_Deprecated_With_Elementor {

	public function __construct() {

		add_action( 'deprecated_function_run', array( $this, 'deprecated_function_run' ), 10, 3 );

	}

	function deprecated_function_run( $function, $replacement, $version ) {

		if ( $function == "_register_controls" ) {
			add_filter( 'deprecated_function_trigger_error', '__return_false' );
		}

	}

}