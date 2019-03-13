<?php

/**
 * A custom Time field that stores time without date.
 * That gives us a possibility to make time field optional, as time is not parsed from the same timestamp as the date.
 * @package CapGemini
 */
class CG_FM_Time_Field extends Fieldmanager_Field {

	/**
	 * @var boolean
	 * If true, and $use_time == true, and $date_element = 'dropdown', will render an 'AM' and 'PM' dropdown
	 */
	public $use_am_pm = true;

	/**
	 * @var boolean
	 * By default in WordPress, strtotime() assumes GMT. If $store_local_time is true, FM will use the
	 * site's timezone setting when generating the timestamp. Note that `date()` will return GMT times
	 * for the stamp no matter what, so if you store the local time, `date( 'H:i', $time )` will return
	 * the offset time. Use this option if the exact timestamp is important, e.g. to schedule a wp-cron
	 * event.
	 */
	public $store_local_time = false;

	/**
	 * Override field_class.
	 *
	 * @var string
	 */
	public $field_class = 'time';

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
		parent::__construct( $label, $options );
	}

	/**
	 * Generate HTML for the form element itself. Generally should be just one tag, no wrappers.
	 *
	 * @param mixed string[]|string the value of the element.
	 * @return string HTML for the element.
	 */
	public function form_element( $value ) {
		$value = absint( $value );
		$old_value = $value;
		// If we're storing the local time, in order to make the form work as expected, we have
		// to alter the timestamp. This isn't ideal, but there currently isn't a good way around
		// it in WordPress.
		if ( $this->store_local_time ) {
			$value += get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
		}
		ob_start();
		?>
		<span class="fm-datepicker-time-wrapper">
			<input class="fm-element fm-datepicker-time" type="text" value="<?php echo esc_attr( $this->get_hour( $value ) ); ?>" name="<?php echo esc_attr( $this->get_form_name( '[hour]' ) ); ?>" />
			:
			<input class="fm-element fm-datepicker-time" type="text" value="<?php echo esc_attr( $this->get_minute( $value ) ); ?>" name="<?php echo esc_attr( $this->get_form_name( '[minute]' ) ); ?>" />
			<?php if ( $this->use_am_pm ) : ?>
				<select class="fm-element" name="<?php echo esc_attr( $this->get_form_name( '[ampm]' ) ); ?>">
					<option value="am"<?php selected( $this->get_am_pm( $value ), 'am' ); ?>>A.M.</option>
					<option value="pm"<?php selected( $this->get_am_pm( $value ), 'pm' ); ?>>P.M.</option>
				</select>
			<?php endif; ?>
		</span>
		<?php
		// Reset the timestamp
		$value = $old_value;
		return ob_get_clean();
	}

	/**
	 * Convert date to timestamp
	 * @param $value
	 * @param $current_value
	 * @return int unix timestamp
	 */
	public function presave( $value, $current_value = array() ) {
		$time_to_parse = '';
		if ( ! empty( $value['hour'] ) && is_numeric( $value['hour'] ) ) {
			$hour = intval( $value['hour'] );
			$minute = ( isset( $value['minute'] ) && is_numeric( $value['minute'] ) ) ? intval( $value['minute'] ) : 0;
			if ( 0 === $hour && $this->use_am_pm ) {
				$hour = 12;
			}
			$time_to_parse .= $hour;
			$time_to_parse .= ':' . str_pad( $minute, 2, '0', STR_PAD_LEFT );
			$time_to_parse .= ' ' . sanitize_text_field( $value['ampm'] );
		}
		if ( $this->store_local_time ) {
			return get_gmt_from_date( $time_to_parse, 'g:i a' );
		} else {
			return intval( strtotime( $time_to_parse ) );
		}
	}

	/**
	 * Get hour for rendering in field
	 * @param int $value unix timestamp
	 * @return string value of hour
	 */
	public function get_hour( $value ) {
		return ! empty( $value ) ? date( $this->use_am_pm ? 'g' : 'G', $value ) : '';
	}

	/**
	 * Get minute for rendering in field
	 * @param int $value unix timestamp
	 * @return string value of hour
	 */
	public function get_minute( $value ) {
		return ! empty( $value ) ? date( 'i', $value ) : '';
	}

	/**
	 * Get am or pm for rendering in field
	 * @param int $value unix timestamp
	 * @return string 'am', 'pm', or ''
	 */
	public function get_am_pm( $value ) {
		return ! empty( $value ) ? date( 'a', $value ) : '';
	}

}
