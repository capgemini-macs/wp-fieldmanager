<?php
/**
 * Class file for Fieldmanager_Datepicker_Time_Opt (Datepicker with optional time)
 *
 * The original Fieldmanager_Datepicker forces time field by always printing time from timestamp
 * even if time is not explicitly set by user.
 *
 * This field returns array with timestamp and additional key [has_time] with boolean value
 * that keeps info on whether the time field was actually filled.
 */
namespace CG\Fieldmanager;

/**
 * A JavaScript date-picker which submits dates as Unix timestamps with optional time field.
 */
class Fieldmanager_Datepicker_Time_Opt extends Fieldmanager_Datepicker {

	/**
	 * Collect time info or just date info? Defaults to just date info.
	 *
	 * @var bool
	 */
	public $use_time = true;

	/**
	 * Generate HTML for the form element itself. Generally should be just one tag, no wrappers.
	 *
	 * @param mixed $value The value of the element.
	 * @return string HTML for the element.
	 */
	public function form_element( $value ) {

		$value = [
			'date'     => isset( $value['date'] ) ? (int) $value['date'] : '',
			'has_time' => isset( $value['has_time'] ) ? (bool) $value['has_time'] : false,
		];

		$old_value = [
			'date'     => $value['date'],
			'has_time' => $value['has_time'],
		];

		// If we're storing the local time, in order to make the form work as expected, we have
		// to alter the timestamp. This isn't ideal, but there currently isn't a good way around
		// it in WordPress.

		ob_start();
		include 'template-datepicker-time-opt.php';

		// Reset the timestamp.
		$value = $old_value;
		return ob_get_clean();
	}

	/**
	 * Convert date to timestamp.
	 *
	 * @param mixed $value         The new value for the field.
	 * @param mixed $current_value The current value for the field.
	 * @return array [time] Unix timestamp, [has_time] bool.
	 */
	public function presave( $value, $current_value = array() ) {
		$time_to_parse = sanitize_text_field( $value['date'] );

		$final_value = [
			'date'     => $time_to_parse,
			'has_time' => isset( $value['hour'] ) && is_numeric( $value['hour'] ),
		];

		if ( isset( $value['hour'] ) && is_numeric( $value['hour'] ) && $this->use_time ) {
			$hour   = intval( $value['hour'] );
			$minute = ( isset( $value['minute'] ) && is_numeric( $value['minute'] ) ) ? intval( $value['minute'] ) : 0;
			if ( 0 === $hour && $this->use_am_pm ) {
				$hour = 12;
			}
			$time_to_parse .= ' ' . $hour;
			$time_to_parse .= ':' . str_pad( $minute, 2, '0', STR_PAD_LEFT );
			$time_to_parse .= ' ' . sanitize_text_field( $value['ampm'] );
		}

		if ( empty( $time_to_parse ) ) {
			/*
			 * Return before converting to an integer for compatibility with
			 * Fieldmanager's checks for empty values. See #563.
			 */
			return $final_value;
		}

		if ( $this->store_local_time ) {
			$final_value['date'] = get_gmt_from_date( $time_to_parse, 'U' );
		} else {
			$final_value['date'] = intval( strtotime( $time_to_parse ) );
		}

		return $final_value;
	}

	/**
	 * Get hour for rendering in field.
	 *
	 * @param int $value Unix timestamp.
	 * @return string Value of hour.
	 */
	public function get_hour( $value ) {
		return ! empty( $value['date'] ) && ! empty( $value['has_time'] ) ? date( $this->use_am_pm ? 'g' : 'G', $value['date'] ) : '';
	}

	/**
	 * Get minute for rendering in field.
	 *
	 * @param int $value Unix timestamp.
	 * @return string Value of minute.
	 */
	public function get_minute( $value ) {
		return ! empty( $value['date'] ) && ! empty( $value['has_time'] ) ? date( 'i', $value['date'] ) : '';
	}

	/**
	 * Get 'am' or 'pm' for rendering in field.
	 *
	 * @param int $value Unix timestamp.
	 * @return string 'am', 'pm', or ''.
	 */
	public function get_am_pm( $value ) {
		return ! empty( $value ) && ! empty( $value['has_time'] ) ? date( 'a', $value['date'] ) : '';
	}

}
