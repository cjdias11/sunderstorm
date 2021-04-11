<?php
/**
 * Check if a search was made from the wpsl search widget.
 * 
 * If this is the case, then the searched location
 * is returned and shown in the value="" field in the template.
 *
 * @since 1.0.0
 * @return string $widget_search Empty or the value from the widget search.
 */
function wpsl_widget_search_input() {
    
    $widget_search = isset( $_REQUEST['wpsl-widget-search'] ) ? ( esc_attr( $_REQUEST['wpsl-widget-search'] ) ) : '';
    
    return $widget_search;
}

add_filter( 'wpsl_search_input', 'wpsl_widget_search_input' );

/**
 * Check if a category filter was selected in the wpsl search widget.
 * 
 * If this is the case, then we set the selected category to 
 * selected in the store locator itself.
 *
 * @since 1.0.0
 * @param  string $term_id           The id of the current term
 * @return string $selected_category Empty or selected="selected"
 */
function wpsl_selected_widget_category( $term_id ) {
    
    $widget_category   = isset( $_REQUEST['wpsl-widget-categories'] ) ? ( absint( $_REQUEST['wpsl-widget-categories'] ) ) : '';
    $selected_category = selected( $widget_category, $term_id, false );
    
    return $selected_category;
}

add_filter( 'wpsl_selected_category', 'wpsl_selected_widget_category' );

/**
 * Add the 'wpsl-widget' class to the store locator template.
 * 
 * This class is used in wpsl-gmap.js to determine 
 * if a search should be automatically triggerd with the
 * value from the search widget.
 *
 * @since 1.0.0
 * @return array $classes Empty or a collection of classes that are placed on the outer store locator div.
 */
function wpsl_widget_css_class( $classes ) {
    
    if ( isset( $_REQUEST['wpsl-widget-search'] ) ) {
        $classes[] = 'wpsl-widget';
    }
    
    return $classes;
}

add_filter( 'wpsl_template_css_classes', 'wpsl_widget_css_class' );

/**
 * Check if we need to include the wpsl-widget.js file
 *
 * @since 1.1.0
 * @param array $settings The widget settings
 * @return void
 */
function wpsl_maybe_include_widget_js( $settings ) {

    global $wpsl, $wpsl_settings, $post;

    $js_settings = array();
    $min         = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    // Enable autocomplete for the widget input field?
    if ( $settings['autocomplete'] ) {
        $js_settings['autoComplete'] = true;

        // This makes sure the auto complete library is included in the wpsl_get_gmap_api_params func.
        $GLOBALS['wpsl_settings']['autocomplete'] = true;

        // Check if we need to restrict the auto complete results.
        if ( $wpsl_settings['api_region'] && $wpsl_settings['api_geocode_component'] ) {
            $js_settings['geocodeComponents'] = apply_filters( 'wpsl_geocode_components', array(
                'country' => strtoupper( $wpsl_settings['api_region'] )
            ) );
        }
    }

    // Enable the HTML 5 Geolocation API to determine the users current location?
    if ( $settings['auto_locate'] || $settings['manually_locate'] ) {

        /**
         * This is used in JS to filter out the correct data from the Geocode API.
         *
         * By default if the users location is successfully determined, then it will show the
         * zipcode. You can change this with the wpsl_geolocation_filter_pattern filter
         * to for example only show the city, the formatted address ( street + city + zip + country )
         * or just the street name.
         */
        $filter_pattern = array( 'postal_code', 'postal_code_prefix,postal_code' );

        if ( $settings['auto_locate'] ) {
            $js_settings['autoLocate'] = true;
        }

        $js_settings['geoLocationTimout'] = apply_filters( 'wpsl_geolocation_timeout', 7500 );
        $js_settings['filterPattern']     = apply_filters( 'wpsl_geolocation_filter_pattern', $filter_pattern );
    }

    /**
     * Always include the JS script so we can check if the input field is still empty,
     * and if so, show a red border around it.
     */
    wp_enqueue_script( 'wpsl-widget', WPSL_WIDGET_PLUGIN_URL . 'js/wpsl-widget' . $min . '.js', array( 'jquery' ), WPSL_WIDGET_VERSION_NUM, true );

    // If the autocomplete or auto locate options are enabled, then include more scripts / JS settings vars.
    if ( $js_settings ) {
        wp_enqueue_script( 'wpsl-gmap', ( 'https://maps.google.com/maps/api/js' . wpsl_get_gmap_api_params( 'browser_key' ) . '' ), '', null, true );
        wp_localize_script( 'wpsl-widget', 'wpslWidgetSettings', $js_settings );

        // Don't include the geolocation errors var if we are on a page that contains the wpsl shortcode. They are already included.
        if ( isset( $js_settings['geoLocationTimout'] ) && !has_shortcode( $post->post_content, 'wpsl' ) ) {
            wp_localize_script( 'wpsl-widget', 'wpslGeolocationErrors', $wpsl->frontend->geolocation_errors() );
        }
    }
}

/**
 * Include the available widget templates
 * in the list of available WPSL / add-on templates.
 *
 * @since 1.2.0
 * @param  array $templates The available templates
 * @return array $templates The updated template list
 */
function wpsl_update_widget_template_list( $templates ) {

    $widget_templates = array(
        array(
            'id'        => 'default',
            'name'      => __( 'Default', 'wpsl-widget' ),
            'path'      => WPSL_WIDGET_PLUGIN_DIR . 'templates/',
            'file_name' => 'default.php'
        ),
    );

    $templates['widget'] = apply_filters( 'wpsl_widget_templates', $widget_templates );

    return $templates;
}

add_filter( 'wpsl_template_list', 'wpsl_update_widget_template_list' );

/**
 * Enqueue the widget css file.
 *
 * @since 1.2.0
 * @return void
 */
function wpsl_widget_css() {

    $min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    wp_enqueue_style( 'wpsl-widget', WPSL_WIDGET_PLUGIN_URL . 'css/styles'. $min .'.css', '', WPSL_WIDGET_VERSION_NUM );
}

add_action( 'wp_enqueue_scripts', 'wpsl_widget_css' );

/**
 * Check if the search widget is active
 *
 * @since 1.2.0
 * @returns bool
 */
function wpsl_active_search_widget() {
    return is_active_widget( false, false, 'wpsl_search_widget', true );
}

/**
 * Make sure the shortcode attributes are booleans
 * when they are expected to be.
 *
 * @since 1.2.0
 * @param  array $atts Shortcode attributes
 * @return array $atts Shortcode attributes
 */
function wpsl_widget_bool_check( $atts ) {

    // The fields to check.
    $bool_fields = array( 'category_filter', 'autocomplete', 'auto_locate', 'manually_locate' );

    foreach ( $atts as $key => $val ) {
        if ( in_array( $key, $bool_fields ) ) {
            $atts[$key] = ( in_array( $val, array( 'true', '1', 'yes', 'on' ) ) ) ? 1 : 0;
        }
    }

    return $atts;
}

/**
 * Check if the server uses HTTPS.
 *
 * This is required for the geolocation API to work.
 * If it's not there, then show a message warning the user.
 *
 * @since 1.2.0
 * @return void
 */
function wpsl_widget_check_ssl() {
    if ( !is_ssl() ) {
        echo '<span style="display:block; margin-top:5px;">' . sprintf( __( '%sNote%s: this option requires %sHTTPS%s.', 'wpsl-widget' ), '<strong>', '</strong>','<a target="_blank" href="https://wpstorelocator.co/document/html-5-geolocation-not-working/">', '</a>' ) . '</span>';
    }
}