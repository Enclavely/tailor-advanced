<?php

if ( ! function_exists( 'tailor_add_background_video_html_attributes' ) ) {

	/**
	 * Adds background video HTML attributes to sections.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html_atts
	 * @param array $atts
	 * @param string $tag
	 *
	 * @return string $html_attributes
	 */
	function tailor_add_background_video_html_attributes( $html_atts, $atts, $tag ) {
		if ( 'tailor_section' == $tag && ! empty( $atts['background_video'] ) ) {
			$html_atts['class'][] = 'has-background-video';
		}
		return $html_atts;
	}

	add_filter( 'tailor_shortcode_html_attributes', 'tailor_add_background_video_html_attributes', 10, 6 );
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
	function tailor_add_background_video_html( $html, $outer_html, $inner_html, $html_atts, $atts, $content, $tag ) {
		if ( 'tailor_section' == $tag && array_key_exists( 'background_video', $atts ) ) {
			$video = tailor_get_attachment_video_src( $atts['background_video'] );
			if ( $video ) {
				switch ( $video[1] ) {
					case 'youtube':
						$section_video = '<div class="tailor-video tailor-video--youtube" data-video-id="' . esc_attr( $video[0] ) . '"></div>';
						break;

					case 'vimeo':
						$section_video = '<div class="tailor-video tailor-video--vimeo" data-video-id="' . esc_attr( $video[0] ) . '"></div>';
						break;

					default:
						$section_video = sprintf(
							'<video class="tailor-video tailor-video--%1$s" preload autoplay loop muted >' .
								'<source src="%2$s" type="video/%1$s"/> %3$s' .
							'</video>',
							esc_attr( $video[1] ),  // Video type
							esc_url( $video[0] ),   // Video URL
							__( 'Your browser does not support the video tag.  Please consider upgrading to a modern browser.', 'tailor-advanced' )
						);
						break;
				}

				$section_background =   '<div class="tailor-section__background">' .
				                            $section_video .
				                            '<div class="tailor-video-overlay"></div>' .
				                        '</div>';

				$inner_html = '<div class="tailor-section__content">%s</div>' . $section_background;
				$html = sprintf( $outer_html, sprintf( $inner_html, $content ) );
			}
		}
		return $html;
	}

	add_filter( 'tailor_shortcode_html', 'tailor_add_background_video_html', 10, 7 );
}

if ( ! function_exists( 'tailor_add_background_video_css_rules' ) ) {

	/**
	 * Adds background video CSS rules.
	 *
	 * @since 1.0.0
	 *
	 * @param array $css_rules
	 * @param array $atts
	 * @param Tailor_Element $element
	 *
	 * @return array $css_rules
	 */
	function tailor_add_background_video_css_rules( $css_rules, $atts, $element ) {

		if ( ! empty( $atts['background_video'] ) && ! empty( $atts['overlay_color'] ) ) {
			$css_rules[] = array(
				'setting'           =>  'overlay_color',
				'selectors'         =>  array( '.tailor-video-overlay' ),
				'declarations'      =>  array(
					'background-color'  =>  esc_attr( $atts['overlay_color'] ),
				),
			);
		}

		return $css_rules;
	}

	add_filter( 'tailor_element_css_rule_sets_tailor_section', 'tailor_add_background_video_css_rules', 10, 3 );
}

if ( ! function_exists( 'tailor_get_attachment_video_src' ) ) {

	/**
	 * Returns true if the video ID corresponds to a video in the Media Library.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $video_id
	 *
	 * @return bool
	 */
	function tailor_is_media_library_video( $video_id ) {
		return is_numeric( $video_id ) && wp_get_attachment_url( $video_id );
	}
}


if ( ! function_exists( 'tailor_get_attachment_video_src' ) ) {

	/**
	 * Returns the URL/ID for the selected video.
	 * 
	 * Supports videos from the Media Library, as well as YouTube and Vimeo videos.
	 * 
	 * @since 1.0.0
	 * 
	 * @param mixed $attachment_id
	 * 
	 * @return array $video
	 */
	function tailor_get_attachment_video_src( $attachment_id ) {
		if ( is_numeric( $attachment_id ) ) {
			$src = wp_get_attachment_url( $attachment_id );
			$mime_type = tailor_get_mime_type( $src );
			
			if ( $mime_type ) {
				$video_type = substr( $mime_type, strpos( $mime_type, '/' ) + 1, strlen( $mime_type ) );
			}
			else {
				$video_type = false;
			}
		}
		else {
			$url = $attachment_id;
			$video_type = tailor_get_external_video_type( $url );
			if ( $video_type && function_exists( "tailor_get_{$video_type}_id" ) ) {
				$src = call_user_func( "tailor_get_{$video_type}_id", $url );
			}
			else {
				$src = false;
			}
		}

		if ( $src && $video_type ) {
			$video = array( $src, $video_type );
		}
		else {
			$video = false;
		}

		/**
		 * Filters the video URL/ID.
		 *
		 * @since 1.0.0
		 *
		 * @param array|false $video
		 * @param int $attachment_id
		 */
		return apply_filters( 'tailor_get_attachment_video_src', $video, $video_type, $attachment_id );
	}
}

if ( ! function_exists( 'tailor_get_mime_type' ) ) {

	/**
	 * Returns the MIME type of the specified resource.
	 * 
	 * @since 1.0.0
	 * 
	 * @param $url
	 *
	 * @return mixed|void
	 */
	function tailor_get_mime_type( $url ) {
		$parts = explode( '.', $url );
		$extension = array_pop( $parts );
		$mimes = get_allowed_mime_types();

		$mime_type = false;
		foreach ( $mimes as $ext_preg => $mime ) {
			$ext_preg = '!^(' . $ext_preg . ')$!i';
			if ( preg_match( $ext_preg, $extension ) ) {
				$mime_type = $mime;
				continue;
			}
		}

		/**
		 * Filters the attachment mime type.
		 *
		 * @since 1.0.0
		 *
		 * @param string $mime_type
		 * @param string $url
		 */
		return apply_filters( 'tailor_get_external_video_type', $mime_type, $url );
	}
}

if ( ! function_exists( 'tailor_get_external_video_type' ) ) {

	/**
	 * Returns the external video type.
	 * 
	 * @since 1.0.0
	 * 
	 * @param $url
	 *
	 * @return mixed|void
	 */
	function tailor_get_external_video_type( $url ) {
		if ( $mime_type = tailor_get_mime_type( $url ) ) {
			$video_type = substr( $mime_type, strpos( $mime_type, '/' ) + 1, strlen( $mime_type ) );
			
			// Must be a video of a supported type
			//if ( ! in_array( $mime_type, array() ) ) {
			//	$video_type = false;
			//}
		}
		else if ( false != strpos( $url, 'vimeo.com' ) ) {
			$video_type = 'vimeo';
		}
		else if ( false != strpos( $url, 'youtube.com/watch' ) || false != strpos( $url, 'youtu.be/') ) {
			$video_type = 'youtube';
		}
		else {
			$video_type = false;
		}
		
		/**
		 * Filters the external video type.
		 *
		 * @since 1.0.0
		 *
		 * @param string $video_type
		 * @param string $url
		 */
		return apply_filters( 'tailor_get_external_video_type', $video_type, $url );
	}
}

if ( ! function_exists( 'tailor_get_youtube_id' ) ) {

	/**
	 * Returns the ID of a given YouTube video.
	 * 
	 * @since 1.0.0
	 * 
	 * @param string $url
	 *
	 * @return mixed|void
	 */
	function tailor_get_youtube_id( $url = '' ) {
		$explode_at = strpos( $url, 'youtu.be/') !== false ? "/" : "v=";
		$parts = explode( $explode_at, $url );
		$video_id = end( $parts );

		/**
		 * Filter the YouTube URL before it is returned.
		 *
		 * @since 1.0.0
		 *
		 * @param $video_id
		 * @param $url
		 */
		return apply_filters( 'tailor_get_youtube_id', $video_id, $url );
	}
}

if ( ! function_exists( 'tailor_get_vimeo_id' ) ) {

	/**
	 * Returns the ID of a given Vimeo.
	 * 
	 * @since 1.0.0
	 * 
	 * @param string $url
	 *
	 * @return mixed|void
	 */
	function tailor_get_vimeo_id( $url = '' ) {
		$parts = explode( '/', trim( $url ) );
		$video_id = end( $parts );

		/**
		 * Filter the Vimeo ID.
		 *
		 * @since 1.0.0
		 *
		 * @param $video_id
		 * @param $url
		 */
		return apply_filters( 'tailor_get_vimeo_id', $video_id, $url );
	}
}