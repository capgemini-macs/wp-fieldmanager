<?php

/**
 * Single-line text field.
 */
class Fieldmanager_Comma_Separated extends Fieldmanager_Field {

	/**
	 * Override field_class.
	 *
	 * @var string
	 */
	public $field_class = 'comma_separated';

	/**
	 * Override constructor to set default size.
	 *
	 * @param string $label   The form label.
	 * @param array  $options The form options.
	 */
	public function __construct( $label = '', $options = array() ) {

		$this->attributes = array(
			'size' => '50',
		);

		$this->template = __DIR__ . '/template-comma-separated.php';
		parent::__construct( $label, $options );

		// Scripts & styles
		// wp_register_script( 'fm-google-maps', $maps_js_url, [], null, false );
		fm_add_script( 'fieldmanager_comma_separated', 'fm-comma-separated.js', [ 'jquery', 'fieldmanager_script' ], '0.0.1', false, '', [], plugins_url( '/', __FILE__ ) );
		// }
	}

}
