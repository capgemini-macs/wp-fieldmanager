<?php
/**
 * Fieldmanager Google Map field.
 */
namespace CG\Fieldmanager;

class Fieldmanager_Map extends Fieldmanager_Field {

	/**
	 * Specific to CapGemini.
	 *
	 * @var string
	 */
	public $api_key;

	/**
	 * Tells Fieldmanager to save the fields to their own meta key
	 * as well as the group or assigned name for use in WP_Query.
	 *
	 * @var bool|string
	 */
	public $index = true;

	public function __construct( $label = '', $options = [] ) {
		$this->index        = 'coords';
		$this->index_filter = [ $this, 'index_filter' ];
		$this->sanitize     = [ $this, 'sanitize' ];
		parent::__construct( $label, $options );

		$this->api_key = apply_filters( 'fm_google_maps_api_key', '' );

		$maps_js_url = 'https://maps.googleapis.com/maps/api/js';

		if ( empty( $this->api_key ) ) {
			return;
		}

		$maps_js_url = add_query_arg(
			[
				'key' => rawurlencode( $this->api_key ),
			],
			$maps_js_url 
		);

		// Scripts & styles
		wp_register_script( 'fm-google-maps', $maps_js_url, [], null, false );
		fm_add_script( 'fieldmanager_map', 'fieldmanager-map.js', [ 'jquery', 'fm-google-maps', 'fieldmanager_script' ], '0.0.1', false, '', [], plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Hidden form element
	 *
	 * @param mixed $value
	 * @return string HTML
	 */
	public function form_element( $value = '' ) {
		if ( empty( $value ) || ! is_array( $value ) ) {
			$value = [
				'lat' => 0.0,
				'lng' => 0.0,
			];
		}

		return sprintf(
			'<div class="fm-element fm-map" data-id="%3$s">
				<label for="%3$s-lat">%6$s <input class="fm-map-lat" type="number" step="any" min="-90" max="90" name="%2$s[lat]" id="%3$s-lat" value="%4$s" %1$s /></label>
				<label for="%3$s-lng">%7$s <input class="fm-map-lng" type="number" step="any" min="-180" max="180" name="%2$s[lng]" id="%3$s-lng" value="%5$s" %1$s /></label>
			</div>',
			$this->get_element_attributes(),
			esc_attr( $this->get_form_name() ),
			esc_attr( $this->get_element_id() ),
			esc_attr( floatval( $value['lat'] ) ),
			esc_attr( floatval( $value['lng'] ) ),
			esc_html__( 'Latitude', 'capgemini' ),
			esc_html__( 'Longitude', 'capgemini' )
		);
	}

	/**
	 * Presave function, which handles sanitization and validation.
	 *
	 * @param array $value Array of lat & lng values
	 * @return array
	 */
	public function presave( $value, $current_value = [] ) {
		// It's possible that some elements (Grid is one) would be arrays at
		// this point, but those elements must override this function. Let's
		// make sure we're dealing with one value here.
		if ( ! is_array( $value ) ) {
			$this->_unauthorized_access( __( 'presave() in the Fieldmanager_Map class should get an array.', 'fieldmanager' ) );
		}

		foreach ( $this->validate as $func ) {
			if ( ! call_user_func( $func, $value ) ) {
				$this->_failed_validation(
					sprintf(
						__( 'Input "%1$s" is not valid for field "%2$s" ', 'fieldmanager' ),
						(string) $value,
						$this->label
					) 
				);
			}
		}

		return call_user_func( $this->sanitize, $value );
	}

	/**
	 * Ensure values are floats.
	 * TODO: ensure valid coord ranges.
	 *
	 * @param array $value
	 * @return array
	 */
	public function sanitize( $value ) {
		if ( ! is_array( $value ) ) {
			return [
				'lat' => 0.0,
				'lng' => 0.0,
			];
		}

		return array_map( 'floatval', $value );
	}

	/**
	 * Save values as separate post meta for queries. Hack to get unique
	 * relevant keys.
	 *
	 * @param $value
	 * @return mixed
	 */
	public function index_filter( $value ) {
		// Hack to save lat & lng keys for queries.
		$this->index = $this->name . '-' . ( '-lat' === substr( $this->index, -4 ) ? 'lng' : 'lat' );
		if ( $this->seq ) {
			$this->index .= '-' . $this->seq;
		}

		return $value;
	}

}
