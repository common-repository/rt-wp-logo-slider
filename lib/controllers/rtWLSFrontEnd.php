<?php

if(!class_exists('rtWLSFrontEnd')):

	class rtWLSFrontEnd{
		function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'front_end_enqueue_scripts' ) );
			add_filter( 'body_class', array( $this, 'wls_browser_body_class' ) );
		}
		/**
		 *	Include default style for front end
		 */
		function front_end_enqueue_scripts(){
			wp_enqueue_style('rt-wls');
			wp_enqueue_style('rt-wls-ie');
			wp_style_add_data( 'rt-wls-ie', 'conditional', 'IE' );
			global $rtWLS;
			$settings = get_option( $rtWLS->options['settings'] );
			$cCss     = ! empty( $settings['custom_css'] ) ? trim( $settings['custom_css'] ) : null;
			if ( $cCss ) {
				wp_add_inline_style('rt-wls',$cCss);
			}
		}

		function wls_browser_body_class($classes) {
			global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
			if($is_lynx) $classes[] = 'wls_lynx';
			elseif($is_gecko) $classes[] = 'wls_gecko';
			elseif($is_opera) $classes[] = 'wls_opera';
			elseif($is_NS4) $classes[] = 'wls_ns4';
			elseif($is_safari) $classes[] = 'wls_safari';
			elseif($is_chrome) $classes[] = 'wls_chrome';
			elseif($is_IE) {
				$classes[] = 'wls_ie';
				if(preg_match('/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $_SERVER['HTTP_USER_AGENT'], $browser_version))
					$classes[] = 'ie'.$browser_version[1];
			} else $classes[] = 'wls_unknown';
			if($is_iphone) $classes[] = 'wls_iphone';
			if ( stristr( $_SERVER['HTTP_USER_AGENT'],"mac") ) {
				$classes[] = 'wls_osx';
			} elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"linux") ) {
				$classes[] = 'wls_linux';
			} elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"windows") ) {
				$classes[] = 'wls_windows';
			}
			return $classes;
		}
	}

endif;