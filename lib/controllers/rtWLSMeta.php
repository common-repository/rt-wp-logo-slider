<?php
/**
 * WLS Meta Class
 *
 *
 * @package WP_LOGO_SHOWCASE
 * @since 1.0
 * @author RadiusTheme
 */

if(!class_exists('rtWLSMeta')):

    class rtWLSMeta
    {
        /**
         * WLS Meta generator construct function
         */
        function __construct()
        {
            // actions
            add_action('admin_enqueue_scripts',	array($this, 'admin_enqueue_scripts'));
            add_action('save_post', array($this, 'save_post'), 10, 2);
            add_action('do_meta_boxes', array($this,'wls_logo_image_box'));
            add_filter( 'manage_edit-wpl_sp_columns', array($this, 'arrange_wpl_sp_columns'));
            add_action( 'manage_wpl_sp_posts_custom_column', array($this,'manage_wpl_sp_columns'), 10, 2);
	        add_action( 'edit_form_after_title', array($this, 'wpl_sp_after_title') );

        }

	    function wpl_sp_after_title($post){
		    global $rtWLS;
		    if( $rtWLS->post_type !== $post->post_type) {
			    return;
		    }
		    $html = null;
		    $html .= '<div class="postbox" style="margin-bottom: 0;"><div class="inside">';
		    $html .= '<p style="text-align: center;"><a style="color: red; text-decoration: none; font-size: 14px;" href="https://www.radiustheme.com/downloads/wp-logo-slider-pro-wordpress/" target="_blank">Please check the pro features</a></p>';
		    $html .= '</div></div>';

		    echo $html;
	    }


        /**
         * @param $columns
         * @return array
         */
        public function arrange_wpl_sp_columns( $columns ) {
            $column_thumbnail = array( 'wls_logo_thumb' => __( 'Logo Image', 'rt-wp-logo-slider' ) );
            $column_url = array( 'wls_logo_client_url' => __( 'Client Website Url', 'rt-wp-logo-slider' ) );

            return array_slice( $columns, 0, 2, true ) + $column_thumbnail + $column_url + array_slice( $columns, 1, null, true );
        }


        /**
         * @param $column
         * @param $id
         */
        public function manage_wpl_sp_columns( $column, $id ) {

            switch ( $column ) {
                case 'wls_logo_thumb':
                    if( function_exists('the_post_thumbnail') ){

                        $post_thumbnail_id = get_post_thumbnail_id($id);
                        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');
                        $post_thumbnail_img= $post_thumbnail_img[0];
                        if($post_thumbnail_img !='')
                            echo '<img src="' . $post_thumbnail_img . '" />';
                        else
                            echo 'No logo added.';
                    }
                    else{
                        echo 'No logo added.';
                    }
                    break;
                case 'wls_logo_client_url':
                    if($column == 'wls_logo_client_url'){
                        echo get_post_meta( $id, '_wls_site_url', true );;
                    }
                default:
                    break;
            }

        }

        /**
         *  Logo image box
         */
        function wls_logo_image_box() {
            global $rtWLS;
            remove_meta_box( 'postimagediv', $rtWLS->post_type, 'side' );
            add_meta_box('postimagediv', __('Logo Image'), 'post_thumbnail_meta_box', $rtWLS->post_type, 'normal', 'high');
        }

        /**
         *  Admin Script
         */
        function admin_enqueue_scripts()
        {
            global $pagenow, $typenow, $rtWLS;

            // validate page
            if( !in_array( $pagenow, array('post.php', 'post-new.php', 'edit.php') ) ) return;
            if( $typenow != $rtWLS->post_type ) return;

            wp_dequeue_script( 'autosave' );

	        $select2Id = 'rt-select2';
	        if(class_exists( 'WPSEO_Admin_Asset_Manager' ) && class_exists( 'Avada' )){
		        $select2Id = 'yoast-seo-select2';
	        }elseif (class_exists( 'WPSEO_Admin_Asset_Manager' )){
		        $select2Id = 'yoast-seo-select2';
	        }elseif (class_exists( 'Avada' )){
		        $select2Id = 'select2-avada-js';
	        }
            // scripts
            wp_enqueue_script(array(
                'jquery',
                'jquery-ui-core',
                'jquery-ui-sortable',
                'ace_code_highlighter_js',
                'ace_mode_js',
	            $select2Id,
                'rt-wls-admin',
            ));

            // styles
            wp_enqueue_style(array(
                'rt-select2',
                'rt-wls-admin',
            ));

            $nonce = wp_create_nonce( $rtWLS->nonceText() );
            wp_localize_script( 'rt-wls-admin', 'wls',
                array(
                    'nonceID' => $rtWLS->nonceId(),
                    'nonce' => $nonce,
                    'ajaxurl' => admin_url( 'admin-ajax.php' )
                ) );

            add_action('admin_head', array($this,'admin_head'));
        }

        /**
         *  Add meta info Box
         */
        function admin_head(){
            global $rtWLS;
            add_meta_box(
                'rt_wls_logo_info_meta',
                __('Logo Information', 'rt-wp-logo-slider'),
                array($this,'rt_wls_logo_meta_information'),
                $rtWLS->post_type,
                'normal',
                'high');
        }

        /**
         * Meta info function
         * @param $post
         */
        function rt_wls_logo_meta_information($post){
            global $rtWLS;

            wp_nonce_field( $rtWLS->nonceText(), $rtWLS->nonceId() );
            $html = null;
            $html .='<div class="rt-wls-meta-holder">';
                $html .= $rtWLS->rtFieldGenerator($rtWLS->rtLogoMetaFields(), true);
            $html .='</div>';
            echo $html;
        }


        /**
         * Save logo meta data
         * @param $post_id
         * @param $post
         * @return mixed
         */
        function save_post($post_id, $post){

            if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
            global $rtWLS;
            if( !$rtWLS->verifyNonce() ) return $post_id;

            if ($rtWLS->post_type != $post->post_type) return $post_id;

            $mates = $rtWLS->rtLogoMetaNames();
            foreach($mates as $field){
                $rValue = !empty($_REQUEST[$field['name']]) ? $_REQUEST[$field['name']] : null;
                $value = $rtWLS->sanitize($field, $rValue);
                if(empty($field['multiple'])){
                    update_post_meta($post_id, $field['name'], $value);
                }else{
                    delete_post_meta($post_id, $field['name']);
                    if(is_array($value) && !empty($value)){
                        foreach($value as $item){
                            add_post_meta($post_id, $field['name'], $item);
                        }
                    }
                }
            }

        } // end function

    }

endif;