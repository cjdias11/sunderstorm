<?php
/**
 * WPSL Search Widget Shortcode
 *
 * This allows users to search for
 * store locations from anywhere on the site.
 *
 * @since 1.2.0
 * @param array $atts The shortcode options
 * @return string $output The search widget HTML
 */
function wpsl_widget_shortcodes( $atts ) {

    $output     = '';
    $wpsl_page  = '';
    $widget_ops = array();

    /**
     * To prevent the widget and shortcode from showing
     * duplicate output we check if the widget is already active.
     *
     * If this is the case we show a warning.
     */
    if ( wpsl_active_search_widget() ) {
        if ( current_user_can( 'edit_theme_options' ) ) {
            $output = '<p>' . sprintf( __( 'The WPSL search widget and the [wpsl_widget] shortcode can not both be active on the same page. You can disable the widget on the %sWidgets%s page.', 'wpsl-widget' ), '<a href="' . admin_url( 'widgets.php' ) . '">', '</a>' ) . '</p>';
        }
    } else {

        // Get details from the shortcode
        $atts = shortcode_atts( array(
            'title'              => '',
            'search_label'       => __( 'Enter your location', 'wpsl-widget' ),
            'search_placeholder' => '',
            'category_filter'    => false,
            'category_label'     => __( 'Category filter', 'wpsl-widget' ),
            'auto_locate'        => false,
            'manually_locate'    => false,
            'autocomplete'       => false,
            'page_id'            => '',
            'template'           => 'default'
        ), $atts, 'wpsl_widget' );

        // Grab the widget values and move them to a new array.
        $sidebars_widgets = get_option( 'widget_wpsl_search_widget' );

        foreach ( $sidebars_widgets as $k => $option ) {
            if ( is_int( $k ) ) {
                foreach ( $option as $k => $value ) {
                    $widget_ops[$k] = $value;
                }
            }
        }

        // Merge the widget values with the shortcode values.
        $args = wp_parse_args( $atts, $widget_ops );

        // Make sure the set page_id is a valid one.
        if ( isset( $args['page_id'] ) && is_numeric( $args['page_id'] ) ) {
            $wpsl_page = get_permalink( $args['page_id'] );
        }

        /**
         * Only show a warning to loggedin users if the permalink fails,
         * which happens when an invalid, or no page_id is passed.
         */
        if ( !$wpsl_page ) {
            if ( current_user_can( 'edit_theme_options' ) ) {
                $output = '<p>' . sprintf( __( 'Before you can use the [wpsl_widget] shortcode you need to provide the %spage_id%s the store locator is used on by including it in the shortcode [wpsl_widget page_id="xxx"].', 'wpsl-widget' ), '<a href="https://pagely.com/blog/find-post-id-wordpress/">', '</a>' ) . '</p>';
            }
        } else {
            ob_start();
            the_widget( 'WPSL_Search_Widget', $args );
            $output = ob_get_clean();
        }
    }

    return $output;
}

add_shortcode( 'wpsl_widget', 'wpsl_widget_shortcodes' );
?>