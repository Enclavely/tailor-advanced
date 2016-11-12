<?php

/**
 * Tailor Image element class.
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( class_exists( 'Tailor_Element' ) && ! class_exists( 'Tailor_Image_Element' ) ) {

    /**
     * Tailor Image element class.
     */
    class Tailor_Image_Element extends Tailor_Element {

	    /**
	     * Registers element settings, sections and controls.
	     *
	     * @access protected
	     */
	    protected function register_controls() {

		    $this->add_section( 'general', array(
			    'title'                 =>  __( 'General', 'tailor-advanced' ),
			    'priority'              =>  10,
		    ) );

		    $this->add_section( 'colors', array(
			    'title'                 =>  __( 'Colors', 'tailor-advanced' ),
			    'priority'              =>  20,
		    ) );

		    $this->add_section( 'attributes', array(
			    'title'                 =>  __( 'Attributes', 'tailor-advanced' ),
			    'priority'              =>  30,
		    ) );

		    $priority = 0;
		    $this->add_setting( 'type', array(
			    'sanitize_callback'     =>  'tailor_sanitize_text',
		    ) );
		    $this->add_control( 'type', array(
			    'label'                 =>  __( 'Type', 'tailor-advanced' ),
			    'type'                  =>  'select',
			    'choices'               =>  array(
				    'library'               =>  __( 'Media Library', 'tailor-advanced' ),
				    'external'              =>  __( 'External image', 'tailor-advanced' ),
			    ),
			    'section'               =>  'general',
			    'priority'              =>  $priority += 10,
		    ) );

		    $this->add_setting( 'image', array(
			    'sanitize_callback'     =>  'tailor_sanitize_number',
		    ) );
		    $this->add_control( 'image', array(
			    'label'                 =>  __( 'Image', 'tailor-advanced' ),
			    'type'                  =>  'image',
			    'section'               =>  'general',
			    'priority'              =>  $priority += 10,
			    'dependencies'          =>  array(
				    'type'                  =>  array(
					    'condition'             =>  'not',
					    'value'                 =>  'external',
				    ),
			    ),
		    ) );

		    $this->add_setting( 'src', array(
			    'sanitize_callback'     =>  'tailor_sanitize_number',
		    ) );
		    $this->add_control( 'src', array(
			    'label'                 =>  __( 'Image', 'tailor-advanced' ),
			    'type'                  =>  'url',
			    'section'               =>  'general',
			    'priority'              =>  $priority += 10,
			    'input_attrs'           =>  array(
				    'placeholder'           =>  'http://',
			    ),
			    'dependencies'          =>  array(
				    'type'                  =>  array(
					    'condition'             =>  'equals',
					    'value'                 =>  'external',
				    ),
			    ),
		    ) );

		    $this->add_setting( 'caption', array(
			    'sanitize_callback'     =>  'tailor_sanitize_text',
		    ) );
		    $this->add_control( 'caption', array(
			    'label'                 =>  __( 'Caption', 'tailor-advanced' ),
			    'type'                  =>  'text',
			    'section'               =>  'general',
			    'priority'              =>  $priority += 10,
		    ) );

		    $general_control_types = array(
			    'horizontal_alignment',
			    'horizontal_alignment_tablet',
			    'horizontal_alignment_mobile',
			    'max_width',
			    'max_width_tablet',
			    'max_width_mobile',
			    'image_link',
			    'image_size',
		    );
		    $general_control_arguments = array(
			    'horizontal_aligmment'  =>  array(
				    'setting'               =>  array(
					    'default'               =>  'center',
				    ),
			    ),
			    'image_size'            =>  array(
				    'setting'               =>  array(
					    'default'               =>  'full',
				    ),
				    'control'               =>  array(
					    'dependencies'          =>  array(
						    'type'                  =>  array(
							    'condition'             =>  'not',
							    'value'                 =>  'external',
						    ),
					    ),
				    ),
			    ),
			    'image_link'            =>  array(
				    'control'               =>  array(
					    'dependencies'          =>  array(
						    'type'                  =>  array(
							    'condition'             =>  'not',
							    'value'                 =>  'external',
						    ),
					    ),
				    ),
			    ),
			    'max_width'             =>  array(
				    'control'               =>  array(
					    'description'           =>  __( 'The maximum image width as a percentage', 'tailor-advanced' ),
					    'type'                  =>  'range',
					    'input_attrs'           =>  array(
						    'min'                   =>  '1',
						    'step'                  =>  '1',
						    'max'                   =>  '100',
					    ),
				    ),
			    ),
		    );
		    $priority = tailor_control_presets( $this, $general_control_types, $general_control_arguments, $priority );

		    $this->add_setting( 'image_link_external', array(
			    'sanitize_callback'     =>  'tailor_sanitize_text',
		    ) );
		    $this->add_control( 'image_link_external', array(
			    'label'                 =>  __( 'Image link', 'tailor-advanced' ),
			    'type'                  =>  'select',
			    'choices'               =>  array(
				    'none'                  =>  __( 'None', 'tailor-advanced' ),
				    'file'                  =>  __( 'Image', 'tailor-advanced' ),
				    'lightbox'              =>  __( 'Lightbox', 'tailor-advanced' ),
			    ),
			    'section'               =>  'general',
			    'priority'              =>  $priority += 10,
			    'dependencies'          =>  array(
				    'type'                  =>  array(
					    'condition'             =>  'equals',
					    'value'                 =>  'external',
				    ),
			    ),
		    ) );

		    $priority = 0;
		    $color_control_types = array(
			    'color',
			    'link_color',
			    'heading_color',
			    'background_color',
			    'border_color',
		    );
		    $color_control_arguments = array();
		    $priority = tailor_control_presets( $this, $color_control_types, $color_control_arguments, $priority );

		    $this->add_setting( 'caption_color', array(
			    'sanitize_callback'     =>  'tailor_sanitize_color',
			    'refresh'               =>  array(
				    'method'                =>  'js',
			    ),
		    ) );
		    $this->add_control( 'caption_color', array(
			    'label'                 =>  __( 'Caption color', 'tailor-advanced' ),
			    'type'                  =>  'colorpicker',
			    'section'               =>  'colors',
			    'priority'              =>  $priority += 10,
		    ) );


		    $priority = 0;
		    $attribute_control_types = array(
			    'class',
			    'padding',
			    'padding_tablet',
			    'padding_mobile',
			    'margin',
			    'margin_tablet',
			    'margin_mobile',
			    'border_style',
			    'border_width',
			    'border_width_tablet',
			    'border_width_mobile',
			    'border_radius',
			    'shadow',
		    );
		    $attribute_control_arguments = array();
		    tailor_control_presets( $this, $attribute_control_types, $attribute_control_arguments, $priority );
	    }

	    /**
	     * Returns custom CSS rules for the element.
	     *
	     * @param $atts
	     * @return array
	     */
	    public function generate_css( $atts ) {
		    $css_rules = array();
		    $excluded_control_types = array(
			    'max_width',
			    'max_width_tablet',
			    'max_width_mobile',
		    );
		    $css_rules = tailor_css_presets( $css_rules, $atts, $excluded_control_types );

		    $screen_sizes = array(
			    '',
			    'tablet',
			    'mobile',
		    );

		    foreach ( $screen_sizes as $screen_size ) {
			    $postfix = empty( $screen_size ) ? '' : "_{$screen_size}";
			    if ( ! empty( $atts[ ( 'max_width' . $postfix ) ] ) ) {
				    $value = tailor_get_numeric_value( $atts[ ( 'max_width' . $postfix ) ] );
				    $css_rules[] = array(
					    'setting'               =>  ( 'max_width' . $postfix ),
					    'media'                 =>  $screen_size,
					    'selectors'             =>  array( 'img' ),
					    'declarations'          =>  array(
						    'max-width'             =>  esc_attr( ( $value . '%' ) ),
					    ),
				    );
			    }
		    }

		    if ( ! empty( $atts['caption_color'] ) ) {
			    $css_rules[] = array(
				    'setting'               =>  'caption_color',
				    'selectors'             =>  array( '.wp-caption-text' ),
				    'declarations'          =>  array(
					    'color'                 =>  esc_attr( $atts['caption_color'] ),
				    ),
			    );
		    }

		    return $css_rules;
	    }
    }
}