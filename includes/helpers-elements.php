<?php

if ( ! function_exists( 'tailor_load_advanced_elements' ) ) {

	/**
	 * Loads advanced element definitions.
	 *
	 * @since 1.0.0
	 */
	function tailor_load_advanced_elements() {
		require_once tailor_advanced()->plugin_dir() . 'includes/elements/class-image.php';
		require_once tailor_advanced()->plugin_dir() . 'includes/elements/class-restricted-content.php';
	}

	add_action( 'tailor_load_elements', 'tailor_load_advanced_elements', 20 );
}

if ( ! function_exists( 'tailor_register_advanced_elements' ) ) {

	/**
	 * Registers advanced elements.
	 *
	 * @since 1.0.0
	 *
	 * @param $element_manager Tailor_Elements
	 */
	function tailor_register_advanced_elements( $element_manager ) {

		$element_manager->add_element( 'tailor_image', array(
			'label'             =>  __( 'Image', 'tailor-advanced' ),
			'description'       =>  __( 'Display a single image.', 'tailor-advanced' ),
			'badge'             =>  __( 'Advanced', 'tailor-advanced' ),
		) );

		$element_manager->add_element( 'tailor_restricted_content', array(
			'label'             =>  __( 'Restricted', 'tailor-advanced' ),
			'description'       =>  __( 'Display content to registered users.', 'tailor-advanced' ),
			'badge'             =>  __( 'Advanced', 'tailor-advanced' ),
			'type'              =>  'wrapper',
			'dynamic'           =>  true,
		) );
	}

	add_action( 'tailor_register_elements', 'tailor_register_advanced_elements', 99 );
}

if ( ! function_exists( 'tailor_add_advanced_element_shortcodes' ) ) {

	/**
	 * Adds advanced element shortcodes.
	 *
	 * @since 1.0.0
	 */
	function tailor_add_advanced_element_shortcodes() {
		require_once tailor_advanced()->plugin_dir() . 'includes/shortcodes/shortcode-image.php';
		require_once tailor_advanced()->plugin_dir() . 'includes/shortcodes/shortcode-restricted-content.php';
	}

	add_action( 'tailor_load_elements', 'tailor_add_advanced_element_shortcodes', 20 );
}