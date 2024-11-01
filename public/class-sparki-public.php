<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://sparki.app
 * @since      1.0.0
 *
 * @package    Sparki
 * @subpackage Sparki/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sparki
 * @subpackage Sparki/public
 * @author     Johannes <development@sparki.app>
 */
class Sparki_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sparki_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sparki_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sparki_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sparki_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$baseURL = 'https://integrations.sparki.app';

		function startsWith( $haystack, $needle ) {
			$length = strlen( $needle );
			return substr( $haystack, 0, $length ) === $needle;
		}
		if (startsWith($_SERVER['HTTP_HOST'],'localhost')) {
			$baseURL = 'http://localhost:3002';
		}
		$options = get_option( 'sparki_options' );
		wp_enqueue_script( $this->plugin_name.'-active-integration', esc_attr( $baseURL.'/api/v1/'.$options['sparki_current_integration'].'/wordpress.js' ), array( 'jquery' ), $this->version, false );
	}

}
