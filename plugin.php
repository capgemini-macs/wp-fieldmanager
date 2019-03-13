<?php
/**
 * Plugin Name: CapGemini Fieldmanager Fields
 * Description: Custom fieldmanager field types.
 * Plugin URI: https://hmn.md
 * Author: Human Made
 * Author URI: https://hmn.md
 * License: GPLv2
 *
 * @package CapGemini
 * @author  Rob O'Rourke <rob@hmn.md>
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
