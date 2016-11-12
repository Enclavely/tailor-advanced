<?php

if ( ! function_exists( 'tailor_shortcode_restricted_content_element' ) ) {

    /**
     * Defines the shortcode rendering function for the Restricted Content element.
     *
     * @param array $atts
     * @param string $content
     * @param string $tag
     * @return string
     */
    function tailor_shortcode_restricted_content_element( $atts, $content = null, $tag ) {
	    /**
	     * Filter the default shortcode attributes.
	     *
	     * @param array
	     */
	    $default_atts = apply_filters( 'tailor_shortcode_default_atts_' . $tag, array() );
	    $atts = shortcode_atts( $default_atts, $atts, $tag );
	    $html_atts = array(
		    'id'            =>  $atts['id'],
		    'class'         =>  explode( ' ', "tailor-element tailor-restricted-content {$atts['class']}" ),
		    'data'          =>  array(),
	    );
	    
	    if ( is_user_logged_in() ) {
		    $inner_html = do_shortcode( $content );
	    }
	    else {
		    $inner_html = '';
		    if ( ! empty( $atts['title'] ) ) {
			    $inner_html = sprintf( '<h3>%s</h3>', esc_html__( $atts['title'] ) );
		    }
		    $inner_html .= wp_login_form( array( 'echo' => false ) );
	    }

	    /**
	     * Filter the HTML attributes for the element.
	     *
	     * @param array $html_attributes
	     * @param array $atts
	     * @param string $tag
	     */
	    $html_atts = apply_filters( 'tailor_shortcode_html_attributes', $html_atts, $atts, $tag );
	    $html_atts['class'] = implode( ' ', (array) $html_atts['class'] );
	    $html_atts = tailor_get_attributes( $html_atts );

	    $outer_html = "<div {$html_atts}>%s</div>";
	    $html = sprintf( $outer_html, $inner_html );

	    /**
	     * Filter the HTML for the element.
	     *
	     * @param string $html
	     * @param string $outer_html
	     * @param string $inner_html
	     * @param string $html_atts
	     * @param array $atts
	     * @param string $content
	     * @param string $tag
	     */
	    $html = apply_filters( 'tailor_shortcode_html', $html, $outer_html, $inner_html, $html_atts, $atts, $content, $tag );

	    return $html;
    }

    add_shortcode( 'tailor_restricted_content', 'tailor_shortcode_restricted_content_element' );
}