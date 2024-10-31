<?php
if (!defined('WPINC')) {
    die;
}

if (!class_exists('rtWLSGutenBurg')):

    class rtWLSGutenBurg
    {
        protected $version;

        function __construct() {
            $this->version = (defined('WP_DEBUG') && WP_DEBUG) ? time() : WPL_SP_PLUGIN_VERSION;
            add_action('enqueue_block_assets', array($this, 'block_assets'));
            add_action('enqueue_block_editor_assets', array($this, 'block_editor_assets'));
            if (function_exists('register_block_type')) {
                register_block_type('radiustheme/wpls', array(
                    'render_callback' => array($this, 'render_shortcode'),
                ));
            }
        }

        static function render_shortcode($atts) {
	        if(!empty($atts['scId']) && $id = absint($atts['scId'])){
		        return do_shortcode( '[logo-slider id="' . $id . '"]' );
	        }
        }


        function block_assets() {
            wp_enqueue_style('wp-blocks');
        }

        function block_editor_assets() {
            global $rtWLS;
            // Scripts.
            wp_enqueue_script(
                'rt-wpls-gb-block-js',
                $rtWLS->assetsUrl . "js/wpls-blocks.min.js",
                array('wp-blocks', 'wp-i18n', 'wp-element'),
                $this->version,
                true
            );
            wp_localize_script('rt-wpls-gb-block-js', 'wpls', array(
	            'short_codes' => $rtWLS->getWlsShortCodeList(),
                'icon'        => $rtWLS->assetsUrl . 'images/icon-scg.png',
            ));
            wp_enqueue_style('wp-edit-blocks');
        }
    }

endif;