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

<?php
/**
 * The header for Astra Theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

?><!DOCTYPE html>
<?php astra_html_before(); ?>
<html <?php language_attributes(); ?>>
<head>
<?php astra_head_top(); ?>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">

<?php wp_head(); ?>
<?php astra_head_bottom(); ?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-156675993-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-156675993-1');
</script>
<img src='https://edge.surfside.io/id/ta?&aid=00080&cid=00287&lid=00345&tpcid=[tpcid]&suid=[suid]' style='display:none'></img>
	
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NM435BP');</script>
<!-- End Google Tag Manager -->
	
<!-- Facebook Pixel Code --><script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init', '870822126770121'); fbq('track', 'PageView');</script><noscript><img height="1" width="1" src="https://www.facebook.com/tr?id=870822126770121&ev=PageView&noscript=1"/></noscript><!-- End Facebook Pixel Code -->

    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Organization",
	  "URL": "https://sunderstorm.com"
      "name": "Sunderstorm",
      "sameAs": [
        "https://www.facebook.com/BySunderstorm",
        "https://www.instagram.com/bysunderstorm "
      ]
    }
    </script>
	
	
	<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/<?php echo get_template_directory_uri(); ?>/assets/imagesandroid-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon-16x16.png">
<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/images/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/assets/images/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

<script type="text/javascript">
	document.addEventListener("click", function(e) {
      var linkNode = e.srcElement.href ? e.srcElement : e.srcElement.parentNode;
	  if(linkNode.localName !== "a" || linkNode.href.endsWith("#")) return;
	  
	  e.preventDefault();
	  var currentQuery = location.search.substr(1);
	  var url = new URL(linkNode.href);
	  url.search += (url.search.indexOf('?') > -1 ? '&' : '?') + currentQuery;
	  var dst = e.target;
	  if (dst.target) {
		window.open(url.toString(), dst);
	  } else {
		location.assign(url.toString());
	  }
	});
</script>

</head>

<body <?php astra_schema_body(); ?> <?php body_class(); ?>>
	
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NM435BP"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php astra_body_top(); ?>
<?php wp_body_open(); ?>
<div 
	<?php
    echo astra_attr(
    'site',
    array(
            'id'    => 'page',
            'class' => 'hfeed site',
        )
);
    ?>
>
	<a class="skip-link screen-reader-text" href="#content"><?php echo esc_html(astra_default_strings('string-header-skip-link', false)); ?></a>

	<?php astra_content_before(); ?>

	<div id="content" class="site-content">

		<div class="ast-container">

		<?php astra_content_top(); ?>



<?php if (astra_page_layout() == 'left-sidebar') : ?>

	<?php get_sidebar(); ?>

<?php endif ?>

<img width="300" height="243" src="https://sunderstorm.com/app/uploads/2021/04/KanhaLogo_FullStacked-300x243.png" class="attachment-medium size-medium" alt="" loading="lazy" srcset="https://sunderstorm.com/app/uploads/2021/04/KanhaLogo_FullStacked-300x243.png 300w, https://sunderstorm.com/app/uploads/2021/04/KanhaLogo_FullStacked-1030x833.png 1030w, https://sunderstorm.com/app/uploads/2021/04/KanhaLogo_FullStacked-768x621.png 768w, https://sunderstorm.com/app/uploads/2021/04/KanhaLogo_FullStacked-1536x1242.png 1536w, https://sunderstorm.com/app/uploads/2021/04/KanhaLogo_FullStacked.png 1838w" sizes="(max-width: 300px) 100vw, 300px">

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

<?php get_footer(); ?>
