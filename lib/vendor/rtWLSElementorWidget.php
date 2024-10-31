<?php

class rtWLSElementorWidget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'rt-wp-logo-slider';
	}

	public function get_title() {
		return __( 'Logo Slider', 'rt-wp-logo-slider' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {
        global $rtWLS;
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Logo Slider', 'rt-wp-logo-slider' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'short_code_id',
			array(
				'type'    => \Elementor\Controls_Manager::SELECT2,
				'id'      => 'style',
				'label'   => __( 'ShortCode', 'rt-wp-logo-slider' ),
				'options' => $rtWLS->getWlsShortCodeList()
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if(isset($settings['short_code_id']) && !empty($settings['short_code_id']) && $id = absint($settings['short_code_id'])){
			echo do_shortcode( '[logo-slider id="' . $id . '"]' );
		}else{
			echo "Please select a post grid";
		}
	}
}