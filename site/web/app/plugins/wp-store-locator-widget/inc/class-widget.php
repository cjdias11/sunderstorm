<?php
/**
 * WPSL Search Widget
 *
 * This allows users to search for 
 * store locations from the sidebar.
 *
 * @since 1.0.0
 * @return void
*/
class WPSL_Search_Widget extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'wpsl-search-widget',
			'description' => __( 'Show a search form that enables users to search for nearby stores. The search results are shown on the store locator page.', 'wpsl-widget' )
		);
        
		parent::__construct( 'wpsl_search_widget', __( 'WP Store Locator Search', 'wpsl-widget' ), $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

	    global $wpsl;

        $settings = $this->parse_settings( $instance );

        // Check if we need to include the JS scripts
        wpsl_maybe_include_widget_js( $settings );

        // If no page id is set show an empty page, or if the user is logged in show an error msg.
        if ( empty( $settings['page_id'] ) ) {
            if ( current_user_can( 'edit_theme_options' ) ) {
                echo '<aside class="widget"><p>' . sprintf( __( 'Before you can use the WP Store Locator Search widget, you need to select the page where you added the [wpsl] shortcode in the widget %ssettings%s.', 'wpsl-widget' ), '<a href="' . admin_url( 'widgets.php' ) . '">', '</a>' ) . '</p></aside>';
            }
            
			return;
        }

        echo $args['before_widget'];
        
        if ( $settings['title'] ) {
            echo $args['before_title'] . $settings['title'] . $args['after_title'];
		}

		// Grab the HTML template and output it.
        $template_details = $wpsl->templates->get_template_details( $settings['template'], 'widget' );
        echo $wpsl->templates->get_template( $template_details, $settings );

        do_action( 'wpsl_after_widget_form' );
  
		echo $args['after_widget'];
	}

    /**
     * Check the widget settings that are used in
     * the template code and move them to a new array.
     *
     * @since 1.2.0
     * @param array $instance
     * @return array $settings The widget settings
     */
    public function parse_settings( $instance ) {

        /**
         * Make sure the shortcode atts fields that are set to
         * 'true', 'yes' and 'on' are set to 1 or 0.
         */
        $settings = wpsl_widget_bool_check( $instance );

        foreach ( $instance as $k => $value ) {
            switch ( $k ) {
                case 'title':
                    $settings['title'] = apply_filters( 'widget_title', $instance[ 'title' ], $instance, $this->id_base );
                    break;
                case 'search_label':
                    $settings['search_label'] = !empty( $instance['search_label'] ) ? $instance['search_label'] : __( 'Enter your location', 'wpsl-widget' );
                    break;
                case 'search_placeholder':
                    $settings['search_placeholder'] = !empty( $instance['search_placeholder'] ) ? $instance['search_placeholder'] : '';
                    break;
                case 'category_label':
                    $settings['category_label'] = !empty( $instance['category_label'] ) ? $instance['category_label'] : __( 'Category filter', 'wpsl-widget' );
                    break;
                case 'page_id':
                    $settings['page_id'] = !empty( $instance['page_id'] ) ? $instance['page_id'] : '';
                    break;
                case 'template':
                    $settings['template'] = $instance['template'];
                    break;
            }
        }

        // If no template is set, then use the default one ( can only be set through the shortcode ).
        if ( !isset( $settings['template'] ) ) {
            $settings['template'] = 'default';
        }

        return $settings;
    }

    /**
     * Create the HTML for the category filter
     *
     * @since 1.2.0
     * @param
     * @return string $category The category filter HTML
     */
    public function create_category_filter( $settings ) {

        global $wpsl;

        $category = '';
        $terms    = get_terms( 'wpsl_store_category' );

        if ( count( $terms ) > 0 ) {
            $category = '<p>' . "\r\n";
            $category .= '<label for="wpsl-widget-categories">'. esc_html( $settings['category_label'] ) .'</label>' . "\r\n";
            $category .= '<select autocomplete="off" name="wpsl-widget-categories" id="wpsl-widget-categories">';
            $category .= '<option value="0">' . esc_html( $wpsl->i18n->get_translation( 'category_default_label', __( 'Any' , 'wpsl-widget' ) ) ) . '</option>';

            foreach ( $terms as $term ) {
                $category .=  '<option value="' . esc_attr( $term->term_id ) . '">' . esc_html( $term->name ). '</option>';
            }

            $category .= '</select>' . "\r\n";
            $category .= '</p>';
        }

        return $category;
    }

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

        global $wpsl;

        $template_list      = $wpsl->templates->get_template_list( 'widget' );
        $title              = !empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $search_label       = !empty( $instance['search_label'] ) ? esc_attr( $instance['search_label'] ) : '';    
        $search_placeholder = !empty( $instance['search_placeholder'] ) ? esc_attr( $instance['search_placeholder'] ) : '';
        $page_id            = !empty( $instance['page_id'] ) ? $instance['page_id'] : '';
        $autocomplete       = isset( $instance['autocomplete'] ) && $instance['autocomplete'] == 'on' ? 'on' : 'off';
        $auto_locate        = isset( $instance['auto_locate'] ) && $instance['auto_locate'] == 'on' ? 'on' : 'off';
        $manually_locate    = isset( $instance['manually_locate'] ) && $instance['manually_locate'] == 'on' ? 'on' : 'off';
        $category_filter    = isset( $instance['category_filter'] ) && $instance['category_filter'] == 'on' ? 'on' : 'off'; 
        $category_label     = !empty( $instance['category_label'] ) ? esc_attr( $instance['category_label'] ) : '';

        // Only show the template option if a custom template is available.
        if ( count( $template_list ) > 1 ) {
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>"><?php _e( 'Used widget template', 'wpsl-widget' ); ?>:</label><br>
            <?php
            echo '<select name="' . esc_attr( $this->get_field_name( 'template' ) ) . '" id="' . esc_attr( $this->get_field_id( 'template' ) ) . '">';

            foreach ( $template_list as $k => $template ) {
                echo '<option value="' . esc_attr( $template['id'] ) . '"' . selected( $instance['template'], $template['id'], false ) . '>' . esc_html( $template['name'] ) . '</option>';
            }

            echo '</select>';
            ?>
        </p>
        <?php } ?>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'wpsl-widget' ); ?>:</label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $title; ?>">
		</p>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'search_label' ) ); ?>"><?php _e( 'Search label', 'wpsl-widget' ); ?>:</label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'search_label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'search_label' ) ); ?>" type="text" value="<?php echo $search_label; ?>">
		</p>        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'search_placeholder' ) ); ?>"><?php _e( 'Search field placeholder', 'wpsl-widget' ); ?>:</label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'search_placeholder' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'search_placeholder' ) ); ?>" type="text" value="<?php echo $search_placeholder; ?>">
		</p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'autocomplete' ) ); ?>">
                <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'autocomplete' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'autocomplete' ) ); ?>"<?php checked( $autocomplete, 'on' ); ?> />
                <?php _e( 'Enable autocomplete?', 'wpsl-widget' ); ?>
            </label>
		</p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'auto_locate' ) ); ?>">
                <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'auto_locate' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'auto_locate' ) ); ?>"<?php checked( $auto_locate, 'on' ); ?> />
                <?php _e( 'Attempt to auto-locate the user?', 'wpsl-widget' ); ?>
                <?php wpsl_widget_check_ssl(); ?>
            </label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'manually_locate' ) ); ?>">
                <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'manually_locate' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'manually_locate' ) ); ?>"<?php checked( $manually_locate, 'on' ); ?> />
                <?php _e( 'Users can manually trigger geolocation requests?', 'wpsl-widget' ); ?>
                <?php wpsl_widget_check_ssl(); ?>
            </label>
        </p>
        <p id="wpsl-category-filter-option">
            <label for="<?php echo esc_attr( $this->get_field_id( 'category_filter' ) ); ?>">
                <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'category_filter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category_filter' ) ); ?>"<?php checked( $category_filter, 'on' ); ?> />
                <?php _e( 'Show store category filter *', 'wpsl-widget' ); ?>
            </label>
		</p>
        <p><em>* <?php echo sprintf( __( 'If you enable this option, then make sure the "Show the category dropdown?" option is also enabled on the WPSL %ssettings%s page.', 'wpsl-widget' ), '<a href="' . admin_url( 'edit.php?post_type=wpsl_stores&page=wpsl_settings#wpsl-search-settings' ) . '">', '</a>' ); ?></em></p>
        <p id="wpsl-category-widget-label">
            <label for="<?php echo esc_attr( $this->get_field_id( 'category_label' ) ); ?>"><?php _e( 'Category label', 'wpsl-widget' ); ?>:</label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category_label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category_label' ) ); ?>" type="text" value="<?php echo $category_label; ?>">
		</p>
        <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'page_id' ) ); ?>"><?php _e( 'Select the page where you added the [wpsl] shortcode', 'wpsl-widget' ); ?>:</label><br>
            <?php
            $args = array(
                'name'             => $this->get_field_name( 'page_id' ),
                'id'               => $this->get_field_id( 'page_id' ),
                'class'            => 'widefat',
                'selected'         => $page_id,
                'show_option_none' => __( 'Select page', 'wpsl-widget' )
            );

            wp_dropdown_pages( $args );
            ?>
        </p>
        <?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
     * @return array $instance
	 */
	public function update( $new_instance, $old_instance ) {
        
		$instance = $old_instance;

        $instance['template']           = isset( $new_instance['template'] ) ? $new_instance['template'] : 'default';
        $instance['title']              = strip_tags( $new_instance['title'] );
		$instance['search_label']       = strip_tags( $new_instance['search_label'] );
        $instance['search_placeholder'] = strip_tags( $new_instance['search_placeholder'] );
        $instance['autocomplete']       = isset( $new_instance['autocomplete'] ) ? $new_instance['autocomplete'] : '';
        $instance['auto_locate']        = isset( $new_instance['auto_locate'] ) ? $new_instance['auto_locate'] : '';
        $instance['manually_locate']    = isset( $new_instance['manually_locate'] ) ? $new_instance['manually_locate'] : '';
        $instance['category_filter']    = isset( $new_instance['category_filter'] ) ? $new_instance['category_filter'] : '';
        $instance['category_label']     = strip_tags( $new_instance['category_label'] );
        $instance['page_id']            = absint( $new_instance['page_id'] );
        
		return $instance;
	}
}