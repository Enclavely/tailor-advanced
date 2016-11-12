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