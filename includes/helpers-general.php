<?php

if ( ! function_exists( 'tailor_add_custom_controls' ) ) {

	/**
	 * Adds custom settings and controls to elements.
	 *
	 * @since 1.0.0
	 *
	 * @param Tailor_Element $element
	 */
	function tailor_add_custom_controls( $element ) {

		//
		// General settings
		//
		$priority = 0;
		foreach ( (array) $element->controls() as $control ) { /* var $control Tailor_Control */
			if ( $control->section == 'general' ) {
				$priority += 10;
			}
		}

		$element->add_setting( 'animation', array(
			'sanitize_callback'     =>  'tailor_sanitize_number',
			'refresh'               =>  array(
				'method'                =>  'js',
			),
		) );
		$element->add_control( 'animation', array(
			'label'                 =>  __( 'Entrance animation', 'tailor-advanced' ),
			'type'                  =>  'select',
			'choices'               =>  array(
				''                  =>  __( 'None', 'tailor-advanced' ),

				// Fading
				__( 'Fading', 'tailor-advanced' ) =>  array(
					'fadeIn'            =>  __( 'Fade in', 'tailor-advanced' ),
					'fadeInDown'        =>  __( 'Fade in down', 'tailor-advanced' ),
					'fadeInDownBig'     =>  __( 'Fade in down big', 'tailor-advanced' ),
					'fadeInLeft'        =>  __( 'Fade in left', 'tailor-advanced' ),
					'fadeInLeftBig'     =>  __( 'Fade in left big', 'tailor-advanced' ),
					'fadeInRight'       =>  __( 'Fade in right', 'tailor-advanced' ),
					'fadeInRightBig'    =>  __( 'Fade in right big', 'tailor-advanced' ),
					'fadeInUp'          =>  __( 'Fade in up', 'tailor-advanced' ),
					'fadeInUpBig'       =>  __( 'Fade in up big', 'tailor-advanced' ),
				),

				// Attention seekers
				__( 'Attention seekers', 'tailor-advanced' ) =>  array(
					'bounce'            =>  __( 'Bounce', 'tailor-advanced' ),
					'flash'             =>  __( 'Flash', 'tailor-advanced' ),
					'headShake'         =>  __( 'Head shake', 'tailor-advanced' ),
					'jello'             =>  __( 'Jello', 'tailor-advanced' ),
					'pulse'             =>  __( 'Pulse', 'tailor-advanced' ),
					'rubberBand'        =>  __( 'Rubber band', 'tailor-advanced' ),
					'swing'             =>  __( 'Swing', 'tailor-advanced' ),
					'tada'              =>  __( 'Tada', 'tailor-advanced' ),
					'wobble'            =>  __( 'Wobble', 'tailor-advanced' ),
				),

				// Bouncing
				__( 'Bouncing', 'tailor-advanced' ) =>  array(
					'bounceIn'          =>  __( 'Bounce in', 'tailor-advanced' ),
					'bounceInDown'      =>  __( 'Bounce in down', 'tailor-advanced' ),
					'bounceInLeft'      =>  __( 'Bounce in left', 'tailor-advanced' ),
					'bounceInRight'     =>  __( 'Bounce in right', 'tailor-advanced' ),
					'bounceInUp'        =>  __( 'Bounce in up', 'tailor-advanced' ),
				),

				// Flipping
				__( 'Flipping', 'tailor-advanced' ) =>  array(
					'flip'              =>  __( 'Flip', 'tailor-advanced' ),
					'flipInX'           =>  __( 'Flip in X', 'tailor-advanced' ),
					'flipInY'           =>  __( 'Flip in Y', 'tailor-advanced' ),
				),

				// Rotating
				__( 'Rotating', 'tailor-advanced' ) =>  array(
					'rotateIn'          =>  __( 'Rotate in', 'tailor-advanced' ),
					'rotateInDownLeft'  =>  __( 'Rotate in down left', 'tailor-advanced' ),
					'rotateInDownRight' =>  __( 'Rotate in down right', 'tailor-advanced' ),
					'rotateInUpLeft'    =>  __( 'Rotate in up left', 'tailor-advanced' ),
					'rotateInUpRight'   =>  __( 'Rotate in up right', 'tailor-advanced' ),
				),

				// Sliding
				__( 'Sliding', 'tailor-advanced' ) =>  array(
					'slideInDown'       =>  __( 'Slide in down', 'tailor-advanced' ),
					'slideInLeft'       =>  __( 'Slide in left', 'tailor-advanced' ),
					'slideInRight'      =>  __( 'Slide in right', 'tailor-advanced' ),
					'slideInUp'         =>  __( 'Slide in up', 'tailor-advanced' ),
				),

				// Zooming
				__( 'Zooming', 'tailor-advanced' ) =>  array(
					'zoomIn'            =>  __( 'Zoom in', 'tailor-advanced' ),
					'zoomInDown'        =>  __( 'Zoom in down', 'tailor-advanced' ),
					'zoomInLeft'        =>  __( 'Zoom in left', 'tailor-advanced' ),
					'zoomInRight'       =>  __( 'Zoom in right', 'tailor-advanced' ),
					'zoomInUp'          =>  __( 'Zoom in up', 'tailor-advanced' ),
				),

				// Specials
				__( 'Specials', 'tailor-advanced' ) =>  array(
					'lightSpeedIn'      =>  __( 'Light speed in', 'tailor-advanced' ),
					'rollIn'            =>  __( 'Roll in', 'tailor-advanced' ),
				),
			),
			'priority'              =>  $priority += 10,
			'section'               =>  'general',
		) );

		$element->add_setting( 'animation_timing', array(
			'sanitize_callback'     =>  'tailor_sanitize_number',
			'refresh'               =>  array(
				'method'                =>  'js',
			),
		) );
		$element->add_control( 'animation_timing', array(
			'label'                 =>  __( 'Animation timing', 'tailor-advanced' ),
			'type'                  =>  'input-group',
			'choices'               =>  array(
				'duration'              =>  array(
					'label'                 =>  __( 'Duration', 'tailor-advanced' ),
					'type'                  =>  'number',
					'unit'                  =>  'ms',
				),
				'delay'                 =>  array(
					'label'                 =>  __( 'Delay', 'tailor-advanced' ),
					'type'                  =>  'number',
					'unit'                  =>  'ms',
				),
			),
			'dependencies'          =>  array(
				'animation'             => array(
					'condition'             =>  'not',
					'value'                 =>  '',
				),
			),
			'priority'              =>  $priority += 10,
			'section'               =>  'general',
		) );

		$element->add_setting( 'animation_repeat', array(
			'sanitize_callback'     =>  'tailor_sanitize_number',
			'refresh'               =>  array(
				'method'                =>  'js',
			),
		) );
		$element->add_control( 'animation_repeat', array(
			'label'                 =>  __( 'Repeat animation?', 'tailor-advanced' ),
			'type'                  =>  'switch',
			'dependencies'          =>  array(
				'animation'             => array(
					'condition'             =>  'not',
					'value'                 =>  '',
				),
			),
			'priority'              =>  $priority += 10,
			'section'               =>  'general',
		) );
		
		if ( 'content' == $element->type ) {
			$element->add_setting( 'animation_hover', array(
				'sanitize_callback'     =>  'tailor_sanitize_number',
			) );
			$element->add_control( 'animation_hover', array(
				'label'                 =>  __( 'Hover animation', 'tailor-advanced' ),
				'type'                  =>  'select',
				'choices'               =>  array(
					''                  =>  __( 'None', 'tailor-advanced' ),

					// Transitions
					__( 'Transitions', 'tailor-advanced' ) =>  array(
						'grow'                      =>  __( 'Grow', 'tailor-advanced' ),
						'shrink'                    =>  __( 'Shrink', 'tailor-advanced' ),
						'pulse'                     =>  __( 'Pulse', 'tailor-advanced' ),
						'pulse-grow'                =>  __( 'Pulse grow', 'tailor-advanced' ),
						'pulse-shrink'              =>  __( 'Pulse shrink', 'tailor-advanced' ),
						'push'                      =>  __( 'Push', 'tailor-advanced' ),
						'bounce-in'                 =>  __( 'Bounce in', 'tailor-advanced' ),
						'bounce-out'                =>  __( 'Bounce out', 'tailor-advanced' ),
						'rotate'                    =>  __( 'Rotate', 'tailor-advanced' ),
						'grow-rotate'               =>  __( 'Grow rotate', 'tailor-advanced' ),
						'float'                     =>  __( 'Float', 'tailor-advanced' ),
						'sink'                      =>  __( 'Sink', 'tailor-advanced' ),
						'bob'                       =>  __( 'Bob', 'tailor-advanced' ),
						'hang'                      =>  __( 'Hang', 'tailor-advanced' ),
						'skew'                      =>  __( 'Skew', 'tailor-advanced' ),
						'skew-forward'              =>  __( 'Skew forward', 'tailor-advanced' ),
						'skew-backward'             =>  __( 'Skew backward', 'tailor-advanced' ),
						'wobble-horizontal'         =>  __( 'Wobble horizontal', 'tailor-advanced' ),
						'wobble-vertical'           =>  __( 'Wobble vertical', 'tailor-advanced' ),
						'wobble-to-bottom-right'    =>  __( 'Wobble to bottom right', 'tailor-advanced' ),
						'wobble-to-top-right'       =>  __( 'Wobble to top right', 'tailor-advanced' ),
						'wobble-top'                =>  __( 'Wobble top', 'tailor-advanced' ),
						'wobble-bottom'             =>  __( 'Wobble bottom', 'tailor-advanced' ),
						'wobble-skew'               =>  __( 'Wobble skew', 'tailor-advanced' ),
						'buzz'                      =>  __( 'Buzz', 'tailor-advanced' ),
						'buzz-out'                  =>  __( 'Buzz out', 'tailor-advanced' ),
						'forward'                   =>  __( 'Forward', 'tailor-advanced' ),
						'backward'                  =>  __( 'Backward', 'tailor-advanced' ),
					),
				),
				'priority'              =>  $priority += 10,
				'section'               =>  'general',
			) );
		}

		$element->add_setting( 'hidden', array(
			'sanitize_callback'     =>  'tailor_sanitize_text',
		) );
		$element->add_control( 'hidden', array(
			'label'                 =>  __( 'Visibility', 'tailor-advanced' ),
			'description'                 =>  __( 'Select the screen sizes on which to hide this element', 'tailor-advanced' ),
			'type'                  =>  'select-multi',
			'choices'               =>  tailor_get_media_queries(),
			'priority'              =>  $priority += 10,
			'section'               =>  'general',
		) );

		//
		// Color settings
		//
		$priority = 0;
		foreach ( (array) $element->controls() as $control ) { /* var $control Tailor_Control */
			if ( $control->section == 'colors' ) {
				$priority += 10;
			}
		}

		$element->add_setting( 'shadow_color', array(
			'sanitize_callback'     =>  'tailor_sanitize_color',
			'refresh'               =>  array(
				'method'                =>  'js',
			),
		) );
		$element->add_control( 'shadow_color', array(
			'label'                 =>  __( 'Shadow color', 'tailor-advanced' ),
			'type'                  =>  'colorpicker',
			'rgba'                  =>  '1',
			'dependencies'          =>  array(
				'shadow'                => array(
					'condition'             =>  'equals',
					'value'                 =>  '1',
				),
			),
			'priority'              =>  $priority += 10,
			'section'               =>  'colors',
		) );

		//
		// Attribute settings
		//
		$shadow_control = false;
		foreach ( (array) $element->controls() as $control ) { /* var $control Tailor_Control */
			if ( $control->setting->id == 'shadow' ) {
				$shadow_control = $control;
				break;
			}
		}

		if ( $shadow_control ) {
			$priority = (int) $shadow_control->priority;

			$element->add_setting( 'shadow_horizontal', array(
				'sanitize_callback'     =>  'tailor_sanitize_number',
				'default'               =>  '0',
				'refresh'               =>  array(
					'method'                =>  'js',
				),
			) );
			$element->add_control( 'shadow_horizontal', array(
				'label'                 =>  __( 'Horizontal shadow', 'tailor-advanced' ),
				'type'                  =>  'range',
				'input_attrs'           =>  array(
					'min'                   =>  '-50',
					'step'                  =>  '1',
					'max'                   =>  '50',
				),
				'dependencies'          =>  array(
					'shadow'                => array(
						'condition'             =>  'equals',
						'value'                 =>  '1',
					),
				),
				'priority'              =>  $priority += 1,
				'section'               =>  'attributes',
			) );

			$element->add_setting( 'shadow_vertical', array(
				'sanitize_callback'     =>  'tailor_sanitize_number',
				'default'               =>  '2',
				'refresh'               =>  array(
					'method'                =>  'js',
				),
			) );
			$element->add_control( 'shadow_vertical', array(
				'label'                 =>  __( 'Vertical shadow', 'tailor-advanced' ),
				'type'                  =>  'range',
				'input_attrs'           =>  array(
					'min'                   =>  '-50',
					'step'                  =>  '1',
					'max'                   =>  '50',
				),
				'dependencies'          =>  array(
					'shadow'                => array(
						'condition'             =>  'equals',
						'value'                 =>  '1',
					),
				),
				'priority'              =>  $priority += 1,
				'section'               =>  'attributes',
			) );

			$element->add_setting( 'shadow_blur', array(
				'sanitize_callback'     =>  'tailor_sanitize_number',
				'default'               =>  '6',
				'refresh'               =>  array(
					'method'                =>  'js',
				),
			) );
			$element->add_control( 'shadow_blur', array(
				'label'                 =>  __( 'Blur', 'tailor-advanced' ),
				'type'                  =>  'range',
				'input_attrs'           =>  array(
					'min'                   =>  '0',
					'step'                  =>  '1',
					'max'                   =>  '100',
				),
				'dependencies'          =>  array(
					'shadow'                => array(
						'condition'             =>  'equals',
						'value'                 =>  '1',
					),
				),
				'priority'              =>  $priority += 1,
				'section'               =>  'attributes',
			) );

			$element->add_setting( 'shadow_spread', array(
				'sanitize_callback'     =>  'tailor_sanitize_number',
				'default'               =>  '0',
				'refresh'               =>  array(
					'method'                =>  'js',
				),
			) );
			$element->add_control( 'shadow_spread', array(
				'label'                 =>  __( 'Spread', 'tailor-advanced' ),
				'type'                  =>  'range',
				'input_attrs'           =>  array(
					'min'                   =>  '0',
					'step'                  =>  '1',
					'max'                   =>  '100',
				),
				'dependencies'          =>  array(
					'shadow'                => array(
						'condition'             =>  'equals',
						'value'                 =>  '1',
					),
				),
				'priority'              =>  $priority += 1,
				'section'               =>  'attributes',
			) );
		}

		$element->add_setting( 'id', array(
			'sanitize_callback'     =>  'tailor_sanitize_text',
			'refresh'               =>  array(
				'method'                =>  'js',
			),
		) );
		$element->add_control( 'id', array(
			'label'                 =>  __( 'ID', 'tailor-advanced' ),
			'type'                  =>  'text',
			'priority'              =>  5,
			'section'               =>  'attributes',
		) );
	}

	add_action( 'tailor_element_register_controls', 'tailor_add_custom_controls' );
}

if ( ! function_exists( 'tailor_add_section_background_video_controls' ) ) {

	/**
	 * Adds advanced controls to Section elements.
	 *
	 * @since 1.0.1
	 *
	 * @param Tailor_Section_Element $section_element
	 */
	function tailor_add_advanced_section_controls( $section_element ) {

		// Add the 'Advanced' label
		$section_element->badge = __( 'Advanced', 'tailor-advanced' );

		// Add the overlay color setting/control
		$section_element->add_setting( 'width', array(
			'sanitize_callback'     =>  'tailor_sanitize_text',
		) );
		$section_element->add_control( 'width', array(
			'label'                 =>  __( 'Width', 'tailor-advanced' ),
			'type'                  =>  'select',
			'choices'               =>  array(
				''                      =>  __( 'Standard', 'tailor-advanced' ),
				'stretch'               =>  __( 'Stretched background', 'tailor-advanced' ),
				'stretch_content'       =>  __( 'Stretched background and content', 'tailor-advanced' ),
			),
			'priority'              =>  5,
			'section'               =>  'general',
		) );

		// Add the overlay color setting/control
		$section_element->add_setting( 'overlay_color', array(
			'sanitize_callback'     =>  'tailor_sanitize_color',
			'refresh'               =>  array(
				'method'                =>  'js',
			),
		) );
		$section_element->add_control( 'overlay_color', array(
			'label'                 =>  __( 'Video overlay color', 'tailor-advanced' ),
			'type'                  =>  'colorpicker',
			'rgba'                  =>  1,
			'priority'              =>  70,
			'section'               =>  'colors',
			'dependencies'          =>  array(
				'background_video'      =>  array(
					'condition'             =>  'not',
					'value'                 =>  '',
				),
			),
		) );

		$priority = 120;

		// Add the background video setting/control
		$section_element->add_setting( 'background_video', array(
			'sanitize_callback'     =>  'tailor_sanitize_number',
		) );
		$section_element->add_control( 'background_video', array(
			'label'                 =>  __( 'Background video', 'tailor-advanced' ),
			'description'           =>  __( 'The selected background image will be used as the fallback image', 'tailor-advanced' ),
			'type'                  =>  'video',
			'priority'              =>  $priority += 10,
			'section'               =>  'attributes',
			'dependencies'          =>  array(
				'background_type'       => array(
					'condition'             =>  'equals',
					'value'                 =>  'video',
				),
			),
		) );

		// Update control dependencies
		$max_width_control = $section_element->get_control( 'max_width' );
		$max_width_control->dependencies = array(
			'width'                 => array(
				'condition'             =>  'equals',
				'value'                 =>  '',
			),
		);

		$parallax_control = $section_element->get_control( 'parallax' );
		$parallax_control->dependencies = array(
			'background_image'      => array(
				'condition'             =>  'not',
				'value'                 =>  '',
			),
			'background_video'      => array(
				'condition'             =>  'equals',
				'value'                 =>  '',
			),
		);

		// Update image attribute control dependencies
		$background_image_controls = array(
			'background_repeat',
			'background_position',
			'background_attachment',
			'background_size'
		);

		foreach ( $background_image_controls as $control_type ) {
			$control = $section_element->get_control( $control_type );
			$control-> dependencies = array(
				'background_image'      => array(
					'condition'             =>  'not',
					'value'                 =>  '',
				),
				'background_video'      => array(
					'condition'             =>  'equals',
					'value'                 =>  '',
				),
				'parallax'              => array(
					'condition'             =>  'not',
					'value'                 =>  '1',
				),
			);
		}
	}

	add_action( 'tailor_element_register_controls_tailor_section', 'tailor_add_advanced_section_controls' );
}

if ( ! function_exists( 'tailor_add_box_shadow_css_rules' ) ) {

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
	function tailor_add_box_shadow_css_rules( $css_rules, $atts, $element ) {
		if ( ! empty( $atts['shadow'] ) ) {
			$h = empty( $atts['shadow_horizontal'] ) ? '0' : ( tailor_get_numeric_value( $atts['shadow_horizontal'] ) . 'px' );
			$v = empty( $atts['shadow_vertical'] ) ? '0' : ( tailor_get_numeric_value( $atts['shadow_vertical'] ) . 'px' );
			$blur = empty( $atts['shadow_blur'] ) ? '6px' : ( tailor_get_numeric_value( $atts['shadow_blur'] ) . 'px' );
			$spread = empty( $atts['shadow_spread'] ) ? '0' : ( tailor_get_numeric_value( $atts['shadow_spread'] ) . 'px' );
			$color = empty( $atts['shadow_color'] ) ? 'rgba(0, 0, 0, 0.1)' : $atts['shadow_color'];
			$css_rules[] = array(
				'setting'               =>  'shadow',
				'selectors'             =>  array(),
				'declarations'          =>  array(
					'box-shadow'            =>  "{$h} {$v} {$blur} {$spread} {$color}",
				),
			);
		}

		return $css_rules;
	}

	add_filter( 'tailor_element_css_rule_sets', 'tailor_add_box_shadow_css_rules', 10, 3 );
}