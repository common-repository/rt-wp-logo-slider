<?php
/**
 * WLS Options Class
 *
 *
 * @package WP_LOGO_SHOWCASE
 * @since 1.0
 * @author RadiusTheme
 */

if ( ! class_exists( 'rtWLSOptions' ) ):

	class rtWLSOptions {

		/**
		 * Generate Getting field option
		 * @return array
		 */
		function rtWLSGeneralSettings() {
			global $rtWLS;
			$settings = get_option( $rtWLS->options['settings'] );

			return array(
				'image_resize' => array(
					'type'  => 'checkbox',
					'name'  => 'image_resize',
					'id'    => 'wls_image_resize',
					'label' => __( 'Enable Image Re-Size', 'rt-wp-logo-slider' ),
					'value' => isset( $settings['image_resize'] ) ? trim( $settings['image_resize'] ) : null
				),
				'image_width'  => array(
					'type'        => 'number',
					'name'        => 'image_width',
					'id'          => "wls_image_width",
					'label'       => __( "Image Width", 'rt-wp-logo-slider' ),
					'holderClass' => 'wls_image_resize_field hidden',
					'default'     => 250,
					'holderID'    => 'wls_image_width_holder',
					'value'       => isset( $settings['image_width'] ) ? (int) ( $settings['image_width'] ) : null
				),
				'image_height' => array(
					'type'        => 'number',
					'name'        => 'image_height',
					'id'          => "wls_image_height",
					'label'       => __( "Image Height", 'rt-wp-logo-slider' ),
					'holderClass' => 'wls_image_resize_field hidden',
					'default'     => 190,
					'holderID'    => 'wls_image_height_holder',
					'value'       => isset( $settings['image_height'] ) ? (int) ( $settings['image_height'] ) : null
				),
				'image_crop'   => array(
					'type'        => 'select',
					'name'        => 'image_crop',
					'id'          => "image_crop",
					'label'       => __( "Image Crop", 'rt-wp-logo-slider' ),
					'class'       => "rt-select2",
					'holderID'    => 'wls_image_crop_holder',
					'holderClass' => 'wls_image_resize_field hidden',
					'options'     => array( false => "Soft Crop", true => "Hard Crop" ),
					'value'       => isset( $settings['image_crop'] ) ? (int) ( $settings['image_crop'] ) : null
				),
			);

		}

		/**
		 * Generate Custom css Field for setting page
		 * @return array
		 */
		function rtWLSCustomCss() {
			global $rtWLS;
			$settings = get_option( $rtWLS->options['settings'] );

			return array(
				'custom_css' => array(
					'type'        => 'custom_css',
					'name'        => 'custom_css',
					'id'          => 'custom-css',
					'holderClass' => 'full',
					'value'       => isset( $settings['custom_css'] ) ? trim( $settings['custom_css'] ) : null,
				),
			);
		}

		/**
		 * Layout array
		 *
		 * @return array
		 */
		function scLayout() {
			return array(
				'grid-layout1'     => __( 'Grid Layout', 'rt-wp-logo-slider' ),
				'carousel-layout1' => __( 'Carousel Slider Layout', 'rt-wp-logo-slider' ),
			);
		}

		/**
		 * Layout item list
		 *
		 * @return array
		 */
		function scLayoutItems() {
			return array(
				'title'       => __( "Title", 'rt-wp-logo-slider' ),
				'logo'        => __( "Logo", 'rt-wp-logo-slider' ),
				'description' => __( "Description", 'rt-wp-logo-slider' )
			);
		}


		/**
		 * Style field
		 *
		 * @return array
		 */
		function scStyleItems() {
			$items = $this->scLayoutItems();
			unset( $items['logo'] );

			return $items;
		}

		/**
		 * Align options
		 *
		 * @return array
		 */
		function scWlsAlign() {
			return array(
				'left'   => __( "Left", 'rt-wp-logo-slider' ),
				'center' => __( "Center", 'rt-wp-logo-slider' ),
				'right'  => __( "Right", 'rt-wp-logo-slider' ),
			);
		}

		/**
		 * FontSize Options
		 * @return array
		 */
		function scWlsFontSize() {

			$size = array();

			for ( $i = 14; $i <= 60; $i ++ ) {
				$size[ $i ] = "{$i} px";
			}

			return $size;
		}

		/**
		 * Order By Options
		 *
		 * @return array
		 */
		function scOrderBy() {
			return array(
				'menu_order' => __( "Menu Order", 'rt-wp-logo-slider' ),
				'title'      => __( "Name", 'rt-wp-logo-slider' ),
				'date'       => __( "Date", 'rt-wp-logo-slider' )
			);
		}

		/**
		 * Order Options
		 *
		 * @return array
		 */
		function scOrder() {
			return array(
				'ASC'  => __( "Ascending", 'rt-wp-logo-slider' ),
				'DESC' => __( "Descending", 'rt-wp-logo-slider' ),
			);
		}

		function scLayoutItemsMetas() {
			global $rtWLS;

			return array(
				'_wls_items' => array(
					'type'      => 'checkbox',
					'label'     => __( 'Field Selection', 'rt-wp-logo-slider' ),
					'name'      => '_wls_items',
					'alignment' => 'vertical',
					'default'   => array( 'logo' ),
					'multiple'  => true,
					'options'   => $rtWLS->scLayoutItems()
				)
			);
		}

		/**
		 * Style field options
		 *
		 * @return array
		 */
		function scStyleFields() {
			return array(
				'primary_color'          => array(
					'type'  => 'colorpicker',
					'name'  => 'wls_primary_color',
					'label' => __( 'Primary color', 'rt-wp-logo-slider' ),
				)
			);
		}


		/**
		 * Column Options
		 *
		 * @return array
		 */
		function scColumns() {
			return array(
				1 => __( "1 Column", 'rt-wp-logo-slider' ),
				2 => __( "2 Column", 'rt-wp-logo-slider' ),
				3 => __( "3 Column", 'rt-wp-logo-slider' ),
				4 => __( "4 Column", 'rt-wp-logo-slider' ),
				5 => __( "5 Column", 'rt-wp-logo-slider' ),
				6 => __( "6 Column", 'rt-wp-logo-slider' ),
			);
		}

		/**
		 * Link type options
		 *
		 * @return array
		 */
		function scLinkTypes() {
			return array(
				'new_window' => __( "Open in new window", 'rt-wp-logo-slider' ),
				'self'       => __( "Open in same window", 'rt-wp-logo-slider' ),
				'no_link'    => __( "No link", 'rt-wp-logo-slider' ),
			);
		}

		/**
		 * Filter Options
		 *
		 * @return array
		 */
		function scFilterMetaFields() {
			global $rtWLS;

			return array(
				'wls_limit'        => array(
					"name"        => "wls_limit",
					"label"       => __( "Limit", 'rt-wp-logo-slider' ),
					"type"        => "number",
					"class"       => "full",
					"description" => __( 'The number of posts to show. Set empty to show all found posts.',
						'rt-wp-logo-slider' )
				),
				'wls_categories'   => array(
					"name"        => "wls_categories",
					"label"       => __( "Categories", 'rt-wp-logo-slider' ),
					"type"        => "select",
					"class"       => "rt-select2",
					"id"          => "wls_categories",
					"multiple"    => true,
					"description" => __( 'Select the category you want to filter, Leave it blank for All category',
						'rt-wp-logo-slider' ),
					"options"     => $rtWLS->getAllWlsCategoryList()
				),
				'wls_order_by'     => array(
					"name"    => "wls_order_by",
					"label"   => __( "Order By", 'rt-wp-logo-slider' ),
					"type"    => "select",
					"class"   => "rt-select2",
					"default" => "date",
					"options" => $this->scOrderBy()
				),
				'wls_order'        => array(
					"name"      => "wls_order",
					"label"     => __( "Order", 'rt-wp-logo-slider' ),
					"type"      => "radio",
					"class"     => "rt-select2",
					"options"   => $this->scOrder(),
					"default"   => "DESC",
					"alignment" => "vertical",
				),
			);
		}

		/**
		 * ShortCode Layout Options
		 *
		 * @return array
		 */
		function scLayoutMetaFields() {
			global $rtWLS;

			return array(
				'wls_layout'                  => array(
					'name'    => 'wls_layout',
					'type'    => 'select',
					'id'      => 'wls_layout',
					'label'   => __( 'Layout', 'rt-wp-logo-slider' ),
					'class'   => 'rt-select2',
					'options' => $this->scLayout()
				),
				'wls_desktop_column'          => array(
					'name'        => 'wls_desktop_column',
					'type'        => 'select',
					'label'       => __( 'Display per row', 'rt-wp-logo-slider' ),
					'id'          => 'wls_desktop_column',
					"holderClass" => "wls_column_options_holder",
					'class'       => 'rt-select2',
					'default'     => 4,
					'options'     => $this->scColumns()
				),
				'wls_carousel_slidesToScroll' => array(
					"name"        => "wls_carousel_slidesToScroll",
					"label"       => __( "Slides To Scroll", 'rt-wp-logo-slider' ),
					"holderClass" => "hidden wls_carousel_options_holder",
					"type"        => "number",
					'default'     => 3,
					"description" => __( 'Number of logo to to scroll, Recommended > same as  slides to show',
						'rt-wp-logo-slider' ),
				),
				'wls_carousel_speed'          => array(
					"name"        => "wls_carousel_speed",
					"label"       => __( "Speed", 'rt-wp-logo-slider' ),
					"holderClass" => "hidden wls_carousel_options_holder",
					"type"        => "number",
					'default'     => 2000,
					"description" => __( 'Auto play Speed in milliseconds', 'rt-wp-logo-slider' ),
				),
				'wls_carousel_options'        => array(
					"name"        => "wls_carousel_options",
					"label"       => __( "Carousel Options", 'rt-wp-logo-slider' ),
					"holderClass" => "hidden wls_carousel_options_holder",
					"type"        => "checkbox",
					"multiple"    => true,
					"alignment"   => "vertical",
					"options"     => $rtWLS->carouselProperty(),
					"default"     => array( 'autoplay', 'arrows', 'dots', 'responsive', 'infinite' ),
				)
			);
		}


		/**
		 * Carousel Property
		 *
		 * @return array
		 */
		function carouselProperty() {
			return array(
				'autoplay'       => __( 'Auto Play', 'rt-wp-logo-slider' ),
				'arrows'         => __( 'Arrow nav button', 'rt-wp-logo-slider' ),
				'dots'           => __( 'Dots', 'rt-wp-logo-slider' ),
				'pauseOnHover'   => __( 'Pause on hover', 'rt-wp-logo-slider' ),
				'adaptiveHeight' => __( 'Adaptive height', 'rt-wp-logo-slider' ),
				'loop'           => __( 'Loop', 'rt-wp-logo-slider' ),
				'lazyLoad'       => __( 'Lazy Load (progressive)', 'rt-wp-logo-slider' )
			);
		}

		/**
		 * Custom Meta field for logo post type
		 *
		 * @return array
		 */
		function rtLogoMetaFields() {
			return array(
				'site_url'         => array(
					'type'        => 'url',
					'name'        => '_wls_site_url',
					'label'       => __( 'Client website URL', 'wp-logo-slider' ),
					'placeholder' => __( "Client URL e.g: http://example.com", 'rt-wp-logo-slider' ),
					'description' => "Link to open when image is clicked (if links are active)"
				),
				'logo_description' => array(
					'type'        => 'textarea',
					'name'        => '_wls_logo_description',
					'class'       => 'rt-textarea',
					'esc_html'    => true,
					'label'       => __( 'Logo Description', 'rt-wp-logo-slider' ),
					'placeholder' => __( "Logo description", 'rt-wp-logo-slider' )
				)
			);
		}
	}

endif;
