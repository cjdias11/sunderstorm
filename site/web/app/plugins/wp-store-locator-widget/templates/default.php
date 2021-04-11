<?php
$search_widget = new WPSL_Search_Widget();

if ( $template_data['category_filter'] ) {
    $category = $search_widget->create_category_filter( $template_data );
}
?>
<form action="<?php echo get_permalink( $template_data['page_id'] ); ?>" method="post" id="wpsl-widget-form">
    <?php do_action( 'wpsl_before_widget_input' ); ?>
    <p>
        <label for="wpsl-widget-search"><?php echo esc_html( $template_data['search_label'] ); ?></label>
        <input type="text" name="wpsl-widget-search" placeholder="<?php echo esc_attr( $template_data['search_placeholder'] ); ?>" id="wpsl-widget-search" value="" >

    </p>
    <?php
    do_action( 'wpsl_after_widget_input' );

    if ( isset( $category ) ) {
        echo $category;

        do_action( 'wpsl_after_widget_category' );
    }
    ?>
    <p>
        <input id="wpsl-widget-submit" type="submit" value="<?php _e( 'Search', 'wpsl-widget' ); ?>">
        <?php if ( $template_data['manually_locate'] ) { ?>
            <span class="wpsl-icon-direction" title="<?php _e( 'Use your current location', 'wpsl-widget' ); ?>">&#xe800;</span>
        <?php } ?>
    </p>
</form>