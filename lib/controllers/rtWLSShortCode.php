<?php
if ( ! class_exists( 'rtWLSShortCode' ) ):

	class rtWLSShortCode {
		private $scA = array();

		function __construct() {
			add_shortcode( 'logo-slider', array( $this, 'wls_short_code' ) );
		}

		function register_scripts() {
			$script = array();
			$iso    = false;
			$caro   = false;
			foreach ( $this->scA as $sc ) {
				if ( isset( $sc ) && is_array( $sc ) ) {
					if ( $sc['isIsotope'] ) {
						$iso = true;
					}
					if ( $sc['isCarousel'] ) {
						$caro = true;
					}
				}
			}
			if ( count( $this->scA ) ) {
				array_push( $script, 'jquery' );
				array_push( $script, 'rt-actual-height-js' );
				array_push( $script, 'rt-images-load' );
				if ( $iso ) {
					array_push( $script, 'rt-isotope' );
				}
				if ( $caro ) {
					array_push( $script, 'rt-owl-carousel2' );
				}
				array_push( $script, 'rt-wls' );
				wp_enqueue_script( $script );
				wp_enqueue_style(array(
					'rt-owl-carousel2-default',
					'rt-owl-carousel2',
				));
			}
		}

		/**
		 * ShortCode Generate
		 *
		 * @param $atts
		 *
		 * @return null|string
		 */
		function wls_short_code( $atts ) {
			global $rtWLS;
			$html = null;
			$arg  = array();
			$atts = shortcode_atts( array(
				'id'    => null,
				'title' => null,
			), $atts, 'logo-showcase' );
			$scID = $atts['id'];
			if ( $scID && ! is_null( get_post( $scID ) ) ) {
				$scMeta = get_post_meta( $scID );

				$layout = ( isset( $scMeta['wls_layout'][0] ) ? $scMeta['wls_layout'][0] : 'grid-layout' );
				if ( ! in_array( $layout, array_keys( $rtWLS->scLayout() ) ) ) {
					$layout = 'grid-layout1';
				}
				$isIsotope  = preg_match( '/isotope/', $layout );
				$isCarousel = preg_match( '/carousel/', $layout );

				$col       = ( isset( $scMeta['wls_column'][0] ) ? intval( $scMeta['wls_column'][0] ) : 4 );
				$colDesk   = ( isset( $scMeta['wls_desktop_column'][0] ) ? intval( $scMeta['wls_desktop_column'][0] ) : 4 );
				$colTab    = ( isset( $scMeta['wls_tab_column'][0] ) ? intval( $scMeta['wls_tab_column'][0] ) : 2 );
				$colMobile = ( isset( $scMeta['wls_mobile_column'][0] ) ? intval( $scMeta['wls_mobile_column'][0] ) : 1 );
				if ( ! in_array( $col, array_keys( $rtWLS->scColumns() ) ) ) {
					$col = 4;
				}
				if ( ! in_array( $colDesk, array_keys( $rtWLS->scColumns() ) ) ) {
					$colDesk = 4;
				}
				if ( ! in_array( $colTab, array_keys( $rtWLS->scColumns() ) ) ) {
					$colTab = 2;
				}
				if ( ! in_array( $colMobile, array_keys( $rtWLS->scColumns() ) ) ) {
					$colMobile = 1;
				}
				$arg['linkType'] = ( isset( $scMeta['wls_link_type'][0] ) ? $scMeta['wls_link_type'][0] : "new_window" );


				/* Argument create */
				$args              = array();
				$itemIdsArgs       = array();
				$args['post_type'] = $rtWLS->post_type;

				// Common filter
				/* post__in */
				$post__in = ( isset( $scMeta['wls_post__in'][0] ) ? $scMeta['wls_post__in'][0] : null );
				if ( $post__in ) {
					$post__in         = explode( ',', $post__in );
					$args['post__in'] = $post__in;
				}
				/* post__not_in */
				$post__not_in = ( isset( $scMeta['wls_post__not_in'][0] ) ? $scMeta['wls_post__not_in'][0] : null );
				if ( $post__not_in ) {
					$post__not_in         = explode( ',', $post__not_in );
					$args['post__not_in'] = $post__not_in;
				}

				/* LIMIT */
				$limit                  = ( ! empty( $scMeta['wls_limit'][0] ) ? ( $scMeta['wls_limit'][0] === '-1' ? 10000000 : (int) $scMeta['wls_limit'][0] ) : 10000000 );
				$args['posts_per_page'] = $limit;

				// Taxonomy
				$taxQ = array();
				$cats = ( ! empty( $scMeta['wls_categories'] ) ? $scMeta['wls_categories'] : array() );
				if ( ! empty( $cats ) ) {
					$taxQ[] = array(
						'taxonomy' => $rtWLS->taxonomy['category'],
						'field'    => 'term_id',
						'terms'    => $cats
					);
				}

				if ( ! empty( $taxQ ) ) {
					$args['tax_query'] = $itemIdsArgs['tax_query'] = $taxQ;
				}

				// Order
				$order_by = ( ! empty( $scMeta['wls_order_by'][0] ) ? $scMeta['wls_order_by'][0] : null );
				$order    = ( ! empty( $scMeta['wls_order'][0] ) ? $scMeta['wls_order'][0] : null );
				if ( $order ) {
					$args['order'] = $order;
				}
				if ( $order_by ) {
					$args['orderby'] = $order_by;
				}

				$containerDataAttr = " data-desktop-col='{$colDesk}'  data-tab-col='{$colTab}'  data-mobile-col='{$colMobile}'";
				$deskItem          = $colDesk;
				$tabItem           = $colTab;
				$mobileItem        = $colMobile;
				$colDesk           = $colDesk == 5 ? '24' : round( 12 / $colDesk );
				$colTab            = $colTab == 5 ? '24' : round( 12 / $colTab );
				$colMobile         = $colMobile == 5 ? '24' : round( 12 / $colMobile );
				$arg['grid']       = "rt-col-md-{$colDesk} rt-col-sm-{$colTab} rt-col-xs-{$colMobile}";
				$arg['class']      = 'equal-height';

				$arg['styleClass'] = null;
				if ( ! empty( $scMeta['wls_tooltip'][0] ) ) {
					$arg['styleClass'] .= ' wls-tooltip';
				}
				if ( ! empty( $scMeta['wls_box_highlight'][0] ) ) {
					$arg['styleClass'] .= ' wls-boxhighlight';
				}
				if ( ! empty( $scMeta['wls_grayscale'][0] ) ) {
					$arg['styleClass'] .= ' wls-grayscale';
				}

				if ( $isIsotope ) {
					$arg['class'] .= ' isotope-item';
				}

				$arg['items'] = ! empty( $scMeta['_wls_items'] ) ? $scMeta['_wls_items'] : array();
				/* Some Custom option */
				$logoQuery = new WP_Query( $args );
				if ( $logoQuery->have_posts() ) {
					$rand          = mt_rand();
					$carouselClass = null;
					$isotopeClass  = null;
					$carouselDir   = $carouselAttribute = null;
					if ( $isCarousel ) {
						$carouselClass  = "wpls-carousel owl-theme owl-carousel";
						$slidesToScroll = ( ! empty( $scMeta['wls_carousel_slidesToScroll'][0] ) ? (int) $scMeta['wls_carousel_slidesToScroll'][0] : 3 );
						$speed          = ( ! empty( $scMeta['wls_carousel_speed'][0] ) ? (int) $scMeta['wls_carousel_speed'][0] : 2000 );
						$options        = array();
						if ( ! empty( $scMeta['wls_carousel_options'] ) && is_array( $scMeta['wls_carousel_options'] ) ) {
							$options = $scMeta['wls_carousel_options'];
						}
						$carouselAttribute = "data-slider='{
                        \"slidesToDesk\": {$deskItem},
                        \"slidesToShowTab\": {$tabItem},
                        \"slidesToShowMobile\": {$mobileItem},
                        \"slidesToScroll\": {$slidesToScroll},
                        \"speed\": {$speed},
                        \"loop\": " . ( in_array( 'loop', $options ) ? 'true' : 'false' ) . ",
                        \"dots\": " . ( in_array( 'dots', $options ) ? 'true' : 'false' ) . ",
                        \"nav\": " . ( in_array( 'arrows', $options ) ? 'true' : 'false' ) . ",
                        \"lazyLoad\": " . ( in_array( 'lazyLoad', $options ) ? 'true' : 'false' ) . ",
                        \"pauseOnHover\": " . ( in_array( 'pauseOnHover', $options ) ? 'true' : 'false' ) . ",
                        \"autoplay\": " . ( in_array( 'autoplay', $options ) ? 'true' : 'false' ) . ",
                        \"centerMode\": " . ( in_array( 'centerMode', $options ) ? 'true' : 'false' ) . ",
                        \"adaptiveHeight\": " . ( in_array( 'adaptiveHeight', $options ) ? 'true' : 'false' ) . ",
                        \"rtl\": " . ( in_array( 'rtl', $options ) ? 'true' : 'false' ) . "
                        }'";

						$carouselDir = ( in_array( 'rtl', $options ) ? ' dir="rtl"' : null );
					}
					if ( $isIsotope ) {
						$isotopeClass = "wpls-isotope";
					}
					$containerID = "rt-container-" . $rand;
					$html        .= $rtWLS->layoutStyle( $rand, $scMeta, $scID);
					$settings    = get_option( $rtWLS->options['settings'] );
					$imgReSize   = ( ! empty( $settings['image_resize'] ) ? true : false );
					$imgSize     = array();
					if ( $imgReSize ) {
						$imgSize['width']  = isset( $settings['image_width'] ) ? (int) ( $settings['image_width'] ) : 180;
						$imgSize['height'] = isset( $settings['image_height'] ) ? (int) ( $settings['image_height'] ) : 90;
						$imgSize['crop']   = isset( $settings['image_crop'] ) ? ( $settings['image_crop'] ? true : false ) : false;
					}
					$html .= "<div class='rt-container-fluid rt-wpls' id='{$containerID}' {$containerDataAttr}>";
					$html .= "<div class='rt-row {$layout} {$carouselClass}' {$carouselAttribute} {$carouselDir}>";
					if ( $isIsotope ) {
						$terms = get_terms( $rtWLS->taxonomy['category'], array(
							'hide_empty' => true,
						) );
						$html  .= '<div class="button-group filter-button-group option-set wls-isotope-button">
											<button data-filter="*" class="selected">' . __( 'Show all',
								'rt-wp-logo-slider' ) . '</button>';
						if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
							foreach ( $terms as $term ) {
								if ( empty( $cats ) ) {
									$html .= "<button data-filter='.{$term->slug}'>" . $term->name . "</button>";
								} else {
									if ( in_array( $term->term_id, $cats ) ) {
										$html .= "<button data-filter='.{$term->slug}'>" . $term->name . "</button>";
									}
								}
							}
						}
						$html .= '</div>';
						$html .= "<div class='{$isotopeClass}' id='{$isotopeClass}-{$rand}'>";
					}

					while ( $logoQuery->have_posts() ) : $logoQuery->the_post();

						/* Argument for single member */
						$arg['pID']         = $pID = get_the_ID();
						$arg['title']       = get_the_title();
						$arg['url']         = get_post_meta( $pID, '_wls_site_url', true );
						$arg['description'] = get_post_meta( $pID, '_wls_logo_description', true );
						$arg['alt_text']    = get_post_meta( $pID, '_wls_logo_alt_text', true );
						$arg['img_src']     = get_post_meta( $pID, '_wls_logo_img_url', true );
                        $imgClass = 'wls-logo';
						if ( empty( $arg['img_src'] ) ) {
							if ( has_post_thumbnail() ) {
                                $img            = wp_get_attachment_image( get_post_thumbnail_id(), 'full', '', [ 'class' => $imgClass, 'title' => $arg['title'] ] );
								$imgS           = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
								$arg['img_src'] = $img;
								if ( $imgReSize && ! empty( $imgSize ) ) {
									$c       = ( ! empty( $imgSize['crop'] ) ? true : false );
									$cropImg = $rtWLS->rtImageReSize( $imgS[0], $imgSize['width'], $imgSize['height'], $c );
									if ( $cropImg ) {
										$arg['img_src'] = "<img title='{$arg['title']}' src='{$cropImg}' width='{$imgSize['width']}' height='{$imgSize['height']}' class='{$imgClass}' alt='{$arg['alt_text']}'>";
									}
								}
							}
						}
						if ( ! empty( $arg['img_src'] ) ) {
							$html .= $rtWLS->render( 'layouts/' . $layout, $arg, true );
						}

					endwhile;
					wp_reset_postdata();
					if ( $isIsotope ) {
						$html .= '</div>'; // end isotope item holder
					}

					$html .= '</div>'; //end row
					$html .= '</div>';// end container


					$scriptGenerator               = array();
					$scriptGenerator['isCarousel'] = $isCarousel;
					$scriptGenerator['isIsotope']  = $isIsotope;
					$this->scA[]                   = $scriptGenerator;
					add_action( 'wp_footer', array( $this, 'register_scripts' ) );

				} else {
					$html .= "<p>" . __( 'No logo found', 'rt-wp-logo-slider' ) . "</p>";
				}
			} else {
				$html .= "<p>" . __( 'No short code found', 'rt-wp-logo-slider' ) . "</p>";
			}

			return $html;
		}
	}
endif;