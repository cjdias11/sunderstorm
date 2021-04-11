<?php
/*
Plugin URI: https://wpstorelocator.co/add-ons/search-widget/
Plugin Name: WP Store Locator - Widget
Description: Enable users to search from a widget for nearby store locations. Shows the results on the store locator page.
Author: Tijmen Smit
Author URI: https://wpstorelocator.co/
Version: 1.2
Text Domain: wpsl-widget
Domain Path: /languages/
License: GPL v3

Copyright (C) 2015 Tijmen Smit - tijmen@wpstorelocator.co

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class WPSL_Widgets {
    
    public $min_version = '2.1.0';

    /**
     * Class constructor
     */          
    function __construct() {

        $this->define_constants();
        $this->maybe_update_wpsl();

        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
    }

    /**
     * Set the plugin constants.
     *
     * @since 1.0.0
     * @return void
     */
    public function define_constants() {

        if ( !defined( 'WPSL_WIDGET_BASENAME' ) )
            define( 'WPSL_WIDGET_BASENAME', plugin_basename( __FILE__ ) );

        if ( !defined( 'WPSL_WIDGET_PLUGIN_URL' ) )
            define( 'WPSL_WIDGET_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

        if ( !defined( 'WPSL_WIDGET_PLUGIN_DIR' ) )
            define( 'WPSL_WIDGET_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        
        if ( !defined( 'WPSL_WIDGET_VERSION_NUM' ) )
            define( 'WPSL_WIDGET_VERSION_NUM', '1.2.0' );
    }

    /**
     * Make sure WPSL meets the min required version,
     * before including the required files and registering the wpsl widget.
     *
     * @since 1.0.0
     * @return void
     */
    public function maybe_update_wpsl() {

        if ( defined( 'WPSL_VERSION_NUM' ) ) {
            if ( version_compare( WPSL_VERSION_NUM, $this->min_version, '<' ) ) {
                add_action( 'all_admin_notices', array( $this, 'update_wpsl_notice' ) );
            } else {
                $this->includes();
                $this->setup_license();
                
                add_action( 'widgets_init',   array( $this, 'register_wpsl_widget' ) );
            }
        }
    }

    /**
     * Show a notice telling the user to update 
     * WPSL before they can use the widget.
     *
     * @since 1.0.0
     * @return void
     */
    public function update_wpsl_notice() {
        echo '<div class="error"><p>' . sprintf( __( 'The Search Widget add-on requires at least version %s of WP Store Locator. Please upgrade to the %slatest version%s.', 'wpsl-widget' ), $this->min_version, '<a href="https://wordpress.org/plugins/wp-store-locator/">', '</a>' ) . '</p></div>';
    }

    /**
     * Include the required files.
     *
     * @since 1.0.0
     * @return void
     */
    public function includes() {
        require_once( WPSL_WIDGET_PLUGIN_DIR . 'inc/wpsl-widget-functions.php' );
        require_once( WPSL_WIDGET_PLUGIN_DIR . 'inc/class-widget.php' );
        require_once( WPSL_WIDGET_PLUGIN_DIR . 'inc/wpsl-widget-shortcode.php' );
    }

    /**
     * Handle the addon license.
     *
     * @since 1.0.0
     * @return void
     */
    public function setup_license() {
        if ( class_exists( 'WPSL_License_Manager' ) ) {
            $license = new WPSL_License_Manager( 'Search Widget', WPSL_WIDGET_VERSION_NUM, 'Tijmen Smit', __FILE__ );
        }
    }

    /**
     * Register the search widget.
     *
     * @since 1.0.0
     * @return void
     */
    public function register_wpsl_widget() {
        register_widget( 'WPSL_Search_Widget' );
    }

    /**
     * Load the translations from the language folder.
     *
     * @since 1.0.0
     * @return void
     */
    public function load_plugin_textdomain() {

        $domain = 'wpsl-widget';
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

        // Load the language file from the /wp-content/languages/wp-store-locator-widget folder, custom + update proof translations.
        load_textdomain( $domain, WP_LANG_DIR . '/wp-store-locator-widget/' . $domain . '-' . $locale . '.mo' );

        // Load the language file from the /wp-content/plugins/wp-store-locator-widget/languages/ folder.
        load_plugin_textdomain( $domain, false, dirname( WPSL_WIDGET_BASENAME ) . '/languages/' );
    }        
}

/**
 * Get started.
 *
 * @since 1.0.0
 * @return void
 */
function wpsl_widget_init() {
    
    // Make sure WP Store Locator itself is active.
    if ( !class_exists( 'WP_Store_locator' ) ) {
        return;
    }
    
    new WPSL_Widgets();    
}

add_action( 'plugins_loaded', 'wpsl_widget_init' );    