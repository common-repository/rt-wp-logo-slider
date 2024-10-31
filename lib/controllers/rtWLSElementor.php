<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'rtWLSElementor' ) ):

	class rtWLSElementor {
		function __construct() {
			if ( did_action( 'elementor/loaded' ) ) {
				add_action( 'elementor/widgets/widgets_registered', array( $this, 'init' ) );
			}
		}

		function init() {
		    global $rtWLS;
			require_once( $rtWLS->libPath . '/vendor/rtWLSElementorWidget.php' );

			// Register widget
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new rtWLSElementorWidget() );
		}
	}

endif;