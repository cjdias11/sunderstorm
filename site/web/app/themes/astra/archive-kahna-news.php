<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 1.0.0
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
 ?>

<?php if (astra_page_layout() == 'left-sidebar') : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

	<div id="primary" <?php astra_primary_class(); ?>>

		<?php astra_primary_content_top(); ?>

		<h1> Cannabis News and Blog</h1>

		<?php astra_content_loop(); ?>

		<?php astra_pagination(); ?>

		<?php astra_primary_content_bottom(); ?>

	</div><!-- #primary -->

<?php if (astra_page_layout() == 'right-sidebar') : ?>

	<?php get_sidebar(); ?>

<?php endif ?>


