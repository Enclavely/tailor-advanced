<?php

if ( ! function_exists( 'tailor_add_animation_timing_css_rules' ) ) {

	/**
	 * Adds animation timing CSS rules.
	 *
	 * @since 1.0.0
	 *
	 * @param array $css_rules
	 * @param array $atts
	 * @param Tailor_Element $element
	 * 
	 * @return array $css_rules
	 */
	function tailor_add_animation_timing_css_rules( $css_rules, $atts, $element ) {
		if ( ! empty( $atts['animation'] ) && ! empty( $atts['animation_timing'] ) ) {
			$animation_timing = explode( ',', $atts['animation_timing'] );
			$declarations = array();

			if ( ! empty( $animation_timing[0] ) ) {
				$declarations['animation-duration'] = esc_attr( $animation_timing[0] . 'ms' );
			}
			if ( ! empty( $animation_timing[1] ) ) {
				$declarations['animation-delay'] = esc_attr( $animation_timing[1] . 'ms' );
			}

			if ( ! empty( $declarations ) ) {
				$css_rules[] = array(
					'setting'               =>  'animation_timing',
					'selectors'             =>  array(),
					'declarations'          =>  $declarations,
				);
			}
		}

		return $css_rules;
	}

	add_filter( 'tailor_element_css_rule_sets', 'tailor_add_animation_timing_css_rules', 10, 3 );
}

if ( ! function_exists( 'tailor_add_animation_html_attributes' ) ) {

	/**
	 * Adds animation-related HTML attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html_atts
	 * @param array $atts
	 * @param string $tag
	 *
	 * @return string $html_attributes
	 */
	function tailor_add_animation_html_attributes( $html_atts, $atts, $tag ) {
		if ( array_key_exists( 'animation', $atts ) && '' != $atts['animation'] ) {
			$html_atts['data']['animation'] = esc_attr( $atts['animation'] );
		}
		if ( array_key_exists( 'animation_repeat', $atts ) && '1' == $atts['animation_repeat'] ) {
			$html_atts['data']['animation-repeat'] = '1';
		}
		return $html_atts;
	}

	add_filter( 'tailor_shortcode_html_attributes', 'tailor_add_animation_html_attributes', 10, 6 );
}

if ( ! function_exists( 'tailor_add_hover_animation_class' ) ) {

	/**
	 * Adds the hover animation class to content elements.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html
	 * @param string $outer_html
	 * @param string $inner_html
	 * @param string $html_atts
	 * @param array $atts
	 * @param string $content
	 * @param string $tag
	 *
	 * @return string $html
	 */
	function tailor_add_hover_animation_class( $html, $outer_html, $inner_html, $html_atts, $atts, $content, $tag ) {
		if ( ! empty( $atts['animation_hover'] ) ) {
			if ( '%s' == $inner_html ) {
				$inner_html = '<div class="tailor-' . esc_attr( $atts['animation_hover'] ) . '">%s</div>';
			}
			else {
				$inner_html = tailor_html_add_class( $inner_html, ( 'tailor-' . $atts['animation_hover'] ) );
			}
		}
		return sprintf( $outer_html, sprintf( $inner_html, $content ) );
	}

	add_filter( 'tailor_shortcode_html', 'tailor_add_hover_animation_class', 10, 7 );
}