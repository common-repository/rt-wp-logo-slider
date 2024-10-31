<?php
/**
 * Wp service showcase plugin initiate Class
 *
 *
 * @package WP_LOGO_SHOWCASE
 * @since 1.0
 * @author RadiusTheme
 */

if ( ! class_exists( 'rtWLSInit' ) ):
	class rtWLSInit {

		/**
		 *    Plugin Init Construct
		 */
		function __construct() {
			add_action( 'init', array( $this, 'init' ), 1 );
			add_action( 'widgets_init', array( $this, 'initWidget' ) );
			add_action( 'plugins_loaded', array( $this, 'wls_load_text_domain' ) );
			register_activation_hook( WPL_SP_PLUGIN_ACTIVE_FILE_NAME, array( $this, 'activate' ) );
			register_deactivation_hook( WPL_SP_PLUGIN_ACTIVE_FILE_NAME, array( $this, 'deactivate' ) );
            add_filter( 'plugin_action_links_' . WPL_SP_PLUGIN_ACTIVE_FILE_NAME,
                array( $this, 'rt_plugin_active_link_marketing' ) );
		}

		function initWidget() {
			global $rtWLS;
			$rtWLS->loadWidget( $rtWLS->widgetsPath );
		}


		/**
		 *    Initiate all required registration for post type and category and the style and script
		 *    Init @hock for plugin init
		 */
		function init() {
			// Create logo post type
			$labels = array(
				'name'               => __( 'Logos', 'rt-wp-logo-slider' ),
				'singular_name'      => __( 'Logo', 'rt-wp-logo-slider' ),
				'add_new'            => __( 'Add New Logo', 'rt-wp-logo-slider' ),
				'menu_name'          => __( 'Logo Slider', 'rt-wp-logo-slider' ),
				'all_items'          => __( 'All Logos', 'rt-wp-logo-slider' ),
				'add_new_item'       => __( 'Add New Logo', 'rt-wp-logo-slider' ),
				'edit_item'          => __( 'Edit Logo', 'rt-wp-logo-slider' ),
				'new_item'           => __( 'New Logo', 'rt-wp-logo-slider' ),
				'view_item'          => __( 'View Logo', 'rt-wp-logo-slider' ),
				'search_items'       => __( 'Search Logos', 'rt-wp-logo-slider' ),
				'not_found'          => __( 'No Logos found', 'rt-wp-logo-slider' ),
				'not_found_in_trash' => __( 'No Logos found in Trash', 'rt-wp-logo-slider' ),
			);

			global $rtWLS;

			register_post_type( $rtWLS->post_type, array(
				'labels'          => $labels,
				'public'          => true,
				'show_ui'         => true,
				'_builtin'        => false,
				'capability_type' => 'page',
				'hierarchical'    => false,
				'menu_icon'       => $rtWLS->assetsUrl . 'images/menu-icon.png',
				'rewrite'         => true,
				'query_var'       => false,
				'supports'        => array(
					'title',
					'thumbnail',
					'page-attributes'
				),
				'show_in_menu'    => true
			) );

			$category_labels = array(
				'name'                       => _x( 'Category', 'rt-wp-logo-slider' ),
				'singular_name'              => _x( 'Category', 'rt-wp-logo-slider' ),
				'menu_name'                  => __( 'Categories', 'rt-wp-logo-slider' ),
				'all_items'                  => __( 'All Category', 'rt-wp-logo-slider' ),
				'parent_item'                => __( 'Parent Category', 'rt-wp-logo-slider' ),
				'parent_item_colon'          => __( 'Parent Category', 'rt-wp-logo-slider' ),
				'new_item_name'              => __( 'New Category Name', 'rt-wp-logo-slider' ),
				'add_new_item'               => __( 'Add New Category', 'rt-wp-logo-slider' ),
				'edit_item'                  => __( 'Edit Category', 'rt-wp-logo-slider' ),
				'update_item'                => __( 'Update Category', 'rt-wp-logo-slider' ),
				'view_item'                  => __( 'View Category', 'rt-wp-logo-slider' ),
				'separate_items_with_commas' => __( 'Separate Categories with commas', 'rt-wp-logo-slider' ),
				'add_or_remove_items'        => __( 'Add or remove Categories', 'rt-wp-logo-slider' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'rt-wp-logo-slider' ),
				'popular_items'              => __( 'Popular Categories', 'rt-wp-logo-slider' ),
				'search_items'               => __( 'Search Categories', 'rt-wp-logo-slider' ),
				'not_found'                  => __( 'Not Found', 'rt-wp-logo-slider' ),
			);
			$category_args   = array(
				'labels'            => $category_labels,
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud'     => true,
			);

			register_taxonomy( $rtWLS->taxonomy['category'], array( $rtWLS->post_type ), $category_args );

			$sc_args = array(
				'label'               => __( 'Shortcode', 'rt-wp-logo-slider' ),
				'description'         => __( 'Wp logo slider Shortcode generator', 'rt-wp-logo-slider' ),
				'labels'              => array(
					'all_items'          => __( 'Shortcode Generator', 'rt-wp-logo-slider' ),
					'menu_name'          => __( 'Shortcode', 'rt-wp-logo-slider' ),
					'singular_name'      => __( 'Shortcode', 'rt-wp-logo-slider' ),
					'edit_item'          => __( 'Edit Shortcode', 'rt-wp-logo-slider' ),
					'new_item'           => __( 'New Shortcode', 'rt-wp-logo-slider' ),
					'view_item'          => __( 'View Shortcode', 'rt-wp-logo-slider' ),
					'search_items'       => __( 'Shortcode Locations', 'rt-wp-logo-slider' ),
					'not_found'          => __( 'No Shortcode found.', 'rt-wp-logo-slider' ),
					'not_found_in_trash' => __( 'No Shortcode found in trash.', 'rt-wp-logo-slider' )
				),
				'supports'            => array( 'title' ),
				'public'              => false,
				'rewrite'             => false,
				'show_ui'             => true,
				'show_in_menu'        => 'edit.php?post_type=' . $rtWLS->post_type,
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => false,
				'capability_type'     => 'page',
			);
			register_post_type( $rtWLS->shortCodePT, $sc_args );


			// register all required style and script for this plugin
			$scripts = array();
			$styles  = array();

			$scripts[] = array(
				'handle' => 'rt-actual-height-js',
				'src'    => $rtWLS->assetsUrl . "vendor/jquery.actual.min.js",
				'deps'   => array( 'jquery' ),
				'footer' => true
			);

			$scripts[] = array(
				'handle' => 'rt-owl-carousel2',
				'src'    => $rtWLS->assetsUrl . "vendor/owl-carousel2/owl.carousel.js",
				'deps'   => array( 'jquery' ),
				'footer' => true
			);
			$scripts[] = array(
				'handle' => 'rt-images-load',
				'src'    => $rtWLS->assetsUrl . "vendor/imagesloaded.pkgd.min.js",
				'deps'   => array( 'jquery' ),
				'footer' => true
			);
			$scripts[] = array(
				'handle' => 'rt-isotope',
				'src'    => $rtWLS->assetsUrl . "vendor/isotope.pkgd.min.js",
				'deps'   => array( 'jquery', 'rt-images-load' ),
				'footer' => true
			);
			$scripts[] = array(
				'handle' => 'rt-wls',
				'src'    => $rtWLS->assetsUrl . "js/wplogoslider.js",
				'deps'   => array( 'jquery' ),
				'footer' => true
			);

			$styles['rt-owl-carousel2-default'] = $rtWLS->assetsUrl . 'vendor/owl-carousel2/assets/owl.theme.default.css';
			$styles['rt-owl-carousel2']         = $rtWLS->assetsUrl . 'vendor/owl-carousel2/assets/owl.carousel.css';
			$styles['rt-wls']                   = $rtWLS->assetsUrl . 'css/wplogoslider.css';
			if ( is_admin() ) {
				$scripts[] = array(
					'handle' => 'ace_code_highlighter_js',
					'src'    => $rtWLS->assetsUrl . "vendor/ace/ace.js",
					'deps'   => null,
					'footer' => true
				);
				$scripts[] = array(
					'handle' => 'ace_mode_js',
					'src'    => $rtWLS->assetsUrl . "vendor/ace/mode-css.js",
					'deps'   => array( 'ace_code_highlighter_js' ),
					'footer' => true
				);

				$scripts[] = array(
					'handle' => 'wls-sortable',
					'src'    => $rtWLS->assetsUrl . "js/wls-sortable.js",
					'deps'   => array( 'jquery' ),
					'footer' => true
				);


				$scripts[] = array(
					'handle' => 'rt-select2',
					'src'    => $rtWLS->assetsUrl . "vendor/select2/select2.min.js",
					'deps'   => array( 'jquery' ),
					'footer' => false
				);

				$scripts[]                = array(
					'handle' => 'rt-wls-admin',
					'src'    => $rtWLS->assetsUrl . "js/wls-admin.js",
					'deps'   => array( 'jquery' ),
					'footer' => true
				);
				$styles['rt-select2']     = $rtWLS->assetsUrl . 'vendor/select2/select2.min.css';
				$styles['rt-wls-preview'] = $rtWLS->assetsUrl . 'css/wls-preview.css';
				$styles['rt-wls-admin']   = $rtWLS->assetsUrl . 'css/wls-admin.css';
			}


			foreach ( $scripts as $script ) {
				wp_register_script( $script['handle'], $script['src'], $script['deps'], $rtWLS->options['version'],
					$script['footer'] );
			}
			foreach ( $styles as $k => $v ) {
				wp_register_style( $k, $v, false, $rtWLS->options['version'] );
			}

			// admin only
			if ( is_admin() ) {
				add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			}
		}

		/**
		 *    Create admin menu for logo showcase
		 */
		function admin_menu() {
			global $rtWLS;
			add_submenu_page( 'edit.php?post_type=' . $rtWLS->post_type, __( 'Settings', 'wp-logo-slider' ),
				__( 'Settings', 'wp-logo-slider' ), 'administrator', 'wls_settings',
				array( $this, 'rt_wls_settings' ) );
		}

		function rt_wls_settings() {
			global $rtWLS;
			$rtWLS->render( 'settings' );
		}


		/**
		 *    Register text domain for WLS
		 */
		public function wls_load_text_domain() {
			load_plugin_textdomain( 'wp-logo-slider', false, WPL_SP_PLUGIN_LANGUAGE_PATH );
		}

		/**
		 *    Run when plugin in activated
		 */
		function activate() {
			$this->insertDefaultData();
		}

		function deactivate() {
			// Not thing to now
		}

		/**
		 *    Insert some default data on plugin activation
		 */
		private function insertDefaultData() {
			global $rtWLS;
			update_option( $rtWLS->options['installed_version'], $rtWLS->options['version'] );
			if ( ! get_option( $rtWLS->options['settings'] ) ) {
				update_option( $rtWLS->options['settings'], $rtWLS->defaultSettings );
			}
		}

        public function rt_plugin_active_link_marketing( $links ) {
            $links[] = '<a target="_blank" href="' . esc_url( 'http://demo.radiustheme.com/wordpress/plugins/logo-slider/' ) . '">Demo</a>';
            $links[] = '<a target="_blank" href="' . esc_url( 'https://www.radiustheme.com/setup-configure-wp-logo-slider-free-version-wordpress/' ) . '">Documentation</a>';
            $links[] = '<a target="_blank" style="color: #39b54a;font-weight: 700;" href="'. esc_url( 'https://www.radiustheme.com/downloads/wp-logo-slider-pro-wordpress/' ) .'">Get Pro</a>';

            return $links;
        }

	}
endif;