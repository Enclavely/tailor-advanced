<?php

if ( ! function_exists( 'tailor_html_add_class' ) ) {

	/**
	 * Adds a class to an HTML string.
	 * 
	 * @since 1.0.0
	 * 
	 * @param $html_string
	 * @param $class
	 *
	 * @return mixed
	 */
	function tailor_html_add_class( $html_string, $class ) {
		if ( preg_match( '/class="([^"]*)"/', $html_string, $matches ) ) {
			$classes = explode( ' ', $matches[1]);
			if ( ! in_array( $class, $classes ) ) {
				$classes[] = $class;
				$html_string = str_replace( $matches[0], sprintf( 'class="%s"', implode(' ', $classes ) ), $html_string );
			}
		}
		else {
			$html_string = preg_replace( '/(\<.+\s)/', sprintf( '$1class="%s" ', $class ), $html_string );
		}
		return $html_string;
	}
}

if ( ! function_exists( 'tailor_add_background_video_html' ) ) {

	/**
	 * Adds background video HTML to sections.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html
	 * @param string $outer_html
	 * @param string $inner_html
	 * @param string $html_atts
	 * @param string $atts
	 * @param string $content
	 * @param string $tag
	 *
	 * @return string $html_attributes
	 */
	function tailor_add_section_class_name(  $html_atts, $atts, $tag ) {
		if ( 'tailor_section' == $tag && array_key_exists( 'width', $atts ) ) {
			if ( $atts['width'] == 'stretch' ) {
				$html_atts['class'][] = 'js-stretch';
			}
			else if ( $atts['width'] == 'stretch_content' ) {
				$html_atts['class'][] = 'js-stretch-content';
			}
		}
		return $html_atts;
	}

	add_filter( 'tailor_shortcode_html_attributes', 'tailor_add_section_class_name', 10, 7 );
}