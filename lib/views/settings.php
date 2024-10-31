<?php global $rtWLS; ?>

<div class="wrap">
    <div id="upf-icon-edit-pages" class="icon32 icon32-posts-page"><br /></div>
    <h2><?php _e('Wp Logo Slider Settings', 'rt-wp-logo-slider'); ?></h2>
    <h3><?php _e('General settings', 'rt-wp-logo-slider');?>
        <a style="margin-left: 15px; font-size: 15px;" href="https://radiustheme.com/setup-configure-wp-logo-showcase-wordpress/" target="_blank"><?php _e('Documentation',  'rt-wp-logo-slider') ?></a>
    </h3>
    <div class="tlp-content-holder">
        <div class="tch-left">
            <div class="rt-setting-wrapper">
                <div class="rt-response"></div>
                <form id="rt-wls-settings-form" onsubmit="rtWLSSettings(this); return false;">

                    <div class="rt-tab-container">
                        <ul class="rt-tab-nav">
                            <li><a href="#s-wls-general"><?php _e('General Settings', 'rt-wp-logo-slider' );?></a></li>
                            <li><a href="#s-wls-custom-css"><?php _e('Custom CSS', 'rt-wp-logo-slider' );?></a></li>
                        </ul>
                        <div id="s-wls-general" class="rt-setting-holder rt-tab-content">
					        <?php echo $rtWLS->rtFieldGenerator($rtWLS->rtWLSGeneralSettings(), true); ?>
                        </div>
                        <div id="s-wls-custom-css" class="rt-setting-holder rt-tab-content">
					        <?php echo $rtWLS->rtFieldGenerator($rtWLS->rtWLSCustomCss(), true); ?>
                        </div>
                    </div>

                    <p class="submit"><input type="submit" name="submit" class="button button-primary rtSaveButton" value="Save Changes"></p>

			        <?php wp_nonce_field( $rtWLS->nonceText(), $rtWLS->nonceId() ); ?>
                </form>
                <div class="rt-response"></div>
            </div>
        </div>

        <div class="tch-right">
            <div id="pro-feature" class="postbox">
                <div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle ui-sortable-handle"><span>Wp Logo Slider Pro</span></h3>
                <div class="inside">
                    <ol>
                        <li>21 Different Layouts(Grid, Slider and Filtering)</li>
                        <li>Carousel Slider with multiple features.</li>
                        <li>Custom Logo Re-sizing.</li>
                        <li>Unlimited Shortcode Generator.</li>
                        <li>Drag & Drop Layout builder.</li>
                        <li>Drag & Drop Logo ordering.</li>
                        <li>Custom Link for each Logo.</li>
                        <li>Category wise Isotope Filtering.</li>
                        <li>Tooltip Enable/Disable option.</li>
                        <li>Box Highlight Enable/Disable.</li>
                        <li>Center Mode available.</li>
                        <li>RTL Supported.</li>
                        <li>Multi-Language available.</li>
                        <li>Widget Ready.</li>
                    </ol>
                    <p></p><a href="https://www.radiustheme.com/downloads/wp-logo-slider-pro-wordpress/" class="get-pro-btn button button-primary" target="_blank">Get Pro Version</a></p>
                </div>
            </div>
        </div>
    </div>


    <div class="rt-help">
        <p class="rt-help-link"><a class="button-primary" href="http://demo.radiustheme.com/wordpress/plugins/logo-slider/" target="_blank"><?php _e('Demo', 'rt-wp-logo-slider' );?></a> <a class="button-primary" href="https://www.radiustheme.com/setup-configure-wp-logo-slider-free-version-wordpress/" target="_blank"><?php _e('Documentation', 'rt-wp-logo-slider' );?></a> </p>
    </div>


</div>
