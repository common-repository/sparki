<?php

/**
 * Fired during plugin activation
 *
 * @link       https://sparki.app
 * @since      1.0.0
 *
 * @package    Sparki
 * @subpackage Sparki/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Sparki
 * @subpackage Sparki/includes
 * @author     Johannes <development@sparki.app>
 */
class Sparki_Activator {
	public function __construct() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sparki-loader.php';

		$this->loader = new Sparki_Loader();
	}
	
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
	}
}
