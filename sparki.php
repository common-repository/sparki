<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           Sparki
 * @link              https://sparki.app
 * @author            sparki.app
 * @copyright         2022 - Johannes Sanders
 * @license           GNU General Public License, version 3
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Sparki
 * Plugin URI:        https://sparki.app
 * Description:       Sparki is a communication platform for real estate agents to reduce/remove the need to talk to prospects. Customers can directly schedule inbound appointments for house visits or intakes without involvement from you.
 * Version:           1.0.0
 * Requires PHP:      5.6
 * Author:            Johannes Sanders
 * Author URI:        https://johannes.work
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       sparki
 * Domain Path:       /languages
 * 
 * Sparki is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SPARKI_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sparki-activator.php
 */
function activate_sparki() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sparki-activator.php';
	Sparki_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sparki-deactivator.php
 */
function deactivate_sparki() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sparki-deactivator.php';
	Sparki_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sparki' );
register_deactivation_hook( __FILE__, 'deactivate_sparki' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sparki.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sparki() {

	$plugin = new Sparki();
	$plugin->run();

}
run_sparki();


function sparki_settings_init() {
    // Register a new setting for "sparki" page.
    register_setting( 'sparki', 'sparki_options' );
 
    // Register a new section in the "sparki" page.
    add_settings_section(
        'sparki_section_developers',
        __( 'Integration configuration', 'sparki' ), 'sparki_section_developers_callback',
        'sparki'
    );
 
    // Register a new field in the "sparki_section_developers" section, inside the "sparki" page.
    add_settings_field(
        'sparki_current_integration', // As of WP 4.6 this value is used only internally.
                                // Use $args' active_integration to populate the id inside the callback.
            __( 'Active integration', 'sparki' ),
        'sparki_current_integration_cb',
        'sparki',
        'sparki_section_developers',
        array(
            'active_integration'         => 'sparki_current_integration',
            'class'             => 'sparki_row',
            'sparki_custom_data' => 'custom',
        )
    );
}
 
/**
 * Register our sparki_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'sparki_settings_init' );
 
 
/**
 * Custom option and settings:
 *  - callback functions
 */
 
 
/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function sparki_section_developers_callback( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'To activate Sparki you will need to connect to a Sparki account.', 'sparki' ); ?></p>
    <?php
}
 
/**
 * Pill field callbakc function.
 *
 * WordPress has magic interaction with the following keys: active_integration, class.
 * - the "active_integration" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function sparki_current_integration_cb( $args ) {
	$baseURL = 'https://sparki.app';
	$integrationServerURI = '/dashboard/integrations/setup-wordpress?callback=';
	$callbackURL = sanitize_url('//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	if ($_SERVER['HTTPS']) {
		$callbackURL = 'https:'.$callbackURL;
	} else {
		$callbackURL = 'http:'.$callbackURL;
	}
    function startsWith( $haystack, $needle ) {
        $length = strlen( $needle );
        return substr( $haystack, 0, $length ) === $needle;
    }
	if (startsWith($_SERVER['HTTP_HOST'],'localhost')) {
		$baseURL = 'http://localhost:3001';
	}
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'sparki_options' );
    ?>

	<?php 
	if (isset($options[$args['active_integration']]) && $options[$args['active_integration']] !== '') {  
		$customizeURI = esc_attr('/dashboard/integrations/'.$options[$args['active_integration']].'/customizations');	
	?>
		<p><b>
			<?php echo esc_html_e( 'ID: ', 'sparki' ).esc_attr( $options[ $args['active_integration'] ] ); ?>
		</b></p><br/>
		<a class="button" href="<?php echo esc_attr( $baseURL.$integrationServerURI).esc_url($callbackURL); ?>" target="_blank">
			<?php echo esc_html_e( 'Change', 'sparki' ) ?>
		</a>
		<button class="button button-danger" id="sparki_disconnect" type="button">
			<?php echo esc_html_e( 'Disconnect', 'sparki' ) ?>
		</button>
		<p class="description">
			<?php echo esc_html_e( 'Your integration is active and will load for all routes except for the ones specified ', 'sparki' ); ?>
			<a href="<?php echo esc_attr( $baseURL.$customizeURI ); ?>" target="_blank">
				<?php echo esc_html_e( 'here' ) ?>.
			</a>
		</p>

	<?php } else { ?>
		<a class="button-primary" href="<?php echo esc_attr( $baseURL.$integrationServerURI.$callbackURL ); ?>\"" target="_blank">
			<?php echo esc_html_e( 'Connect now', 'sparki' ) ?>
		</a>
	<?php } ?>
	<input
		value="<?php echo esc_attr( $options[ $args['active_integration'] ] ); ?>"
		id="<?php echo esc_attr( $args['active_integration'] ); ?>"
		data-custom="<?php echo esc_attr( $args['sparki_custom_data'] ); ?>"
		name="sparki_options[<?php echo esc_attr( $args['active_integration'] ); ?>]">
    </input>

    <script type="application/javascript">
        (function( $ ) {
            'use strict';

            var urlParams = new URLSearchParams(window.location.search);
            var saveLoop = () => {
                if ($('#sparki_current_integration') && urlParams.get('id')) {
                    if ($('#sparki_current_integration').val() !== urlParams.get('id')) {
                        $('#sparki_current_integration').val(urlParams.get('id'));
                        $('#submit')[0].click();
                    } 
                    clearInterval(saveIdInterval);
                }
            }
            if (urlParams.has('id')) {
                var saveIdInterval = setInterval(saveLoop, 200);
            }
            var disconnectLoop = () => {
                var myEl = document.getElementById('sparki_disconnect');
                if (myEl) {
                    myEl.addEventListener('click', function() {
                        const url = window.location.href;
                        var baseURL = url.slice(0, url.indexOf('?'))
                        const reversedULR = url.split("").reverse().join('')
                        var queryParams = reversedULR.slice(
                            0, 
                            reversedULR.indexOf('?')
                        ).split("").reverse().join('')

                        var URLParts = queryParams.split('&')
                        var newURL = baseURL + "?" + URLParts.filter((part) => !part.startsWith('id=')).join('&')
                        $('#sparki_current_integration').val("");
                        $('[name="_wp_http_referer"]').val(newURL)
                        $('#submit')[0].click();
                    }, false);
                    clearInterval(disconnectEventListenerInterval);
                }
            }
            var disconnectEventListenerInterval = setInterval(disconnectLoop, 200);

        })( jQuery );
    </script>
    <?php
}
 
/**
 * Add the top level menu page.
 */
function sparki_options_page() {
    add_menu_page(
        'Sparki',
        'Sparki',
        'manage_options',
        'sparki',
        'sparki_options_page_html'
    );
}
 
 
/**
 * Register our sparki_options_page to the admin_menu action hook.
 */
add_action( 'admin_menu', 'sparki_options_page' );
 
 
/**
 * Top level menu callback function
 */
function sparki_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
 
    // add error/update messages
 
    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'sparki_messages', 'sparki_message', __( 'Settings Saved', 'sparki' ), 'updated' );
    }
 
    // show error/update messages
    settings_errors( 'sparki_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "sparki"
            settings_fields( 'sparki' );
            // output setting sections and their fields
            // (sections are registered for "sparki", each field is registered to a specific section)
            do_settings_sections( 'sparki' );
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}