<?php
/**
 * Plugin Name: MACS Fieldmanager Fields
 * Description: Custom fieldmanager field types.
 * Plugin URI: https://capgemini.com
 * Author: Capgemini MACS PL
 * Author URI: https://capgemini.com
 * License: GPLv2
 *
 * @author  Rob O'Rourke <rob@hmn.md>
 * @author  Lech Dulian <lech.dulian@capgemini.com>
 */

/**
 * Required libraries.
 */
require_once WP_PLUGIN_DIR . '/fieldmanager/fieldmanager.php';

/**
 * Map.
 */
require_once __DIR__ . '/inc/map/class-fieldmanager-map.php';

/**
 * Time field
 */
require_once __DIR__ . '/inc/time/class-fieldmanager-time.php';

/**
 * Datepicker Time Opt field
 */
require_once __DIR__ . '/inc/datepicker_time_opt/class-fieldmanager-datepicker-time-opt.php';

/**
 * Comma separated field
 */
require_once __DIR__ . '/inc/comma-separated/class-fieldmanager-comma-separated.php';
