<?php

if ( ! function_exists( 'tailor_shortcode_image_element' ) ) {

    /**
     * Defines the shortcode rendering function for the Image element.
     *
     * @param array $atts
     * @param string $content
     * @param string $tag
     * @return string
     */
    function tailor_shortcode_image_element( $atts, $content = null, $tag ) {

	    /**
	     * Filter the default shortcode attributes.
	     *
	     * @param array
	     */
	    $default_atts = apply_filters( 'tailor_shortcode_default_atts_' . $tag, array() );
	    $atts = shortcode_atts( $default_atts, $atts, $tag );
	    $html_atts = array(
		    'id'            =>  $atts['id'],
		    'class'         =>  explode( ' ', "tailor-element tailor-image {$atts['class']}" ),
		    'data'          =>  array(),
	    );

	    // External images
	    if ( 'external' == $atts['type'] && ! empty( $atts['src'] ) ) {
		    $content = '<img src="' . esc_url( $atts['src'] ) . '"/>';
		    if ( 'none' != $atts['image_link_external'] ) {
			    if ( 'lightbox' == $atts['image_link_external'] ) {
				    $content = '<a class="tailor-image__link is-lightbox-image" href="' . esc_url( $atts['src'] ) . '">' . $content . '</a>';
			    }
			    else {
				    $content = '<a href="' . esc_url( $atts['src'] ) . '">' . $content . '</a>';
			    }
			    if ( ! empty( $atts['caption'] ) ) {
				    $content =  '<figure class="wp-caption">' .
				                    $content .
				                    '<figcaption class="wp-caption-text">' . $atts['caption'] . '</figcaption>' .
				                '</figure>';
			    }
		    }
	    }

	    // Media Library images
	    else if ( 'external' != $atts['type'] && is_numeric( $atts['image'] ) ) {
		    $attachment_id = $atts['image'];
		    $content = wp_get_attachment_image( $attachment_id, $atts['image_size'] );
		    if ( 'none' != $atts['image_link'] ) {
			    if ( 'file' == $atts['image_link']  || 'lightbox' == $atts['image_link'] ) {
				    $href = wp_get_attachment_url( $attachment_id );
			    }
			    else {
				    $href = get_attachment_link( $attachment_id );
			    }
			    if ( 'lightbox' == $atts['image_link'] ) {
				    $content = '<a class="tailor-image__link is-lightbox-image" href="' . esc_url( $href ) . '">' . $content . '</a>';
			    }
			    else {
				    $content = '<a href="' . esc_url( $href ) . '">' . $content . '</a>';
			    }
			    if ( ! empty( $atts['caption'] ) ) {
				    $content =  '<figure class="wp-caption">' .
				                    $content .
				                    '<figcaption class="wp-caption-text">' . $atts['caption'] . '</figcaption>' .
				                '</figure>';
			    }
		    }
	    }
	    else {
		    $content = sprintf(
			    '<p class="tailor-notification tailor-notification--warning">%s</p>',
			    __( 'Please select an image to display', 'tailor-advanced' )
		    );
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
	    $inner_html = '%s';
	    $content = do_shortcode( $content );
	    $html = sprintf( $outer_html, sprintf( $inner_html, $content ) );

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

    add_shortcode( 'tailor_image', 'tailor_shortcode_image_element' );
}