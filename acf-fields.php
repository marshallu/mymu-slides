<?php
/**
 * Required Advanced Custom Fields information for the plugin.
 *
 * @package mymu-slides
 */
if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_6021769b5d661',
		'title' => 'MyMU Slides',
		'fields' => array(
			array(
				'key' => 'field_602176af4bf60',
				'label' => 'Add to MyMU Slideshow',
				'name' => 'mymu_slides_add_to_mymu_slideshow',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui' => 0,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array(
				'key' => 'field_60228b7c960ee',
				'label' => 'Expire Date',
				'name' => 'mymu_slides_expire_date',
				'type' => 'date_time_picker',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_602176af4bf60',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'display_format' => 'd/m/Y g:i a',
				'return_format' => 'd/m/Y g:i a',
				'first_day' => 1,
			),
			array(
				'key' => 'field_6033dc7b3ef14',
				'label' => 'Order By',
				'name' => 'order_by',
				'type' => 'text',
				'instructions' => 'This field will override the default order by value. The lower the number the earlier it will appear in the list. -10 will appear before 0 or 10.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => 0,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'attachment',
					'operator' => '==',
					'value' => 'image',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => false,
		'modified' => 1638977541,
	));

	endif;
