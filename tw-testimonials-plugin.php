<?php
/*
 * Plugin Name: Third Wunder Testimonials Plugin
 * Version: 1.0
 * Plugin URI: http://www.thirdwunder.com/
 * Description: Third Wunder testimonials CPT plugin
 * Author: Mohamed Hamad
 * Author URI: http://www.thirdwunder.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: tw-testimonials-plugin
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Mohamed Hamad
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-tw-testimonials-plugin.php' );
require_once( 'includes/class-tw-testimonials-plugin-settings.php' );
require_once( 'includes/class-tw-testimonials-plugin-widgets.php' );

// Load plugin libraries
require_once( 'includes/lib/class-tw-testimonials-plugin-admin-api.php' );
require_once( 'includes/lib/class-tw-testimonials-plugin-post-type.php' );
require_once( 'includes/lib/class-tw-testimonials-plugin-taxonomy.php' );

if(!class_exists('AT_Meta_Box')){
  require_once("includes/My-Meta-Box/meta-box-class/my-meta-box-class.php");
}

/**
 * Returns the main instance of TW_Testimonials_Plugin to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object TW_Testimonials_Plugin
 */
function TW_Testimonials_Plugin () {
	$instance = TW_Testimonials_Plugin::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = TW_Testimonials_Plugin_Settings::instance( $instance );
	}

	return $instance;
}

TW_Testimonials_Plugin();

$testimonial_slug = get_option('wpt_tw_testimonial_slug') ? get_option('wpt_tw_testimonial_slug') : "faq";
$testimonial_search = get_option('wpt_tw_testimonial_search') ? true : false;
$testimonial_archive = get_option('wpt_tw_testimonial_archive') ? true : false;

$testimonial_category = get_option('wpt_tw_testimonial_category')=='on' ? true : false;
$testimoinal_tag      = get_option('wpt_tw_testimonial_tag')=='on' ? true : false;



TW_Testimonials_Plugin()->register_post_type(
                        'tw_testimonial',
                        __( 'Testimonials',     'tw-testimonials-plugin' ),
                        __( 'Testimonial',      'tw-testimonials-plugin' ),
                        __( 'Testimonials CPT', 'tw-testimonials-plugin'),
                        array(
                          'menu_icon'=>plugins_url( 'assets/img/cpt-icon-testimonial.png', __FILE__ ),
                          'rewrite' => array('slug' => $testimonial_slug),
                          'exclude_from_search' => $testimonial_search,
                          'has_archive'     => $testimonial_archive,
                        )
                    );

if($testimonial_category){
  TW_Testimonials_Plugin()->register_taxonomy( 'tw_testimonial_category', __( 'Testimonial Categories', 'tw-testimonials-plugin' ), __( 'Testimonial Category', 'tw' ), 'tw_testimonial', array('hierarchical'=>true) );
}

if($testimoinal_tag){
 TW_Testimonials_Plugin()->register_taxonomy( 'tw_testimonial_tag', __( 'Testimonial Tags', 'tw-testimonials-plugin' ), __( 'Testimonial Tag', 'tw-testimonials-plugin' ), 'tw_testimonial', array('hierarchical'=>false) );
}



if (is_admin()){
  $testimonial_config = array(
    'id'             => 'tw_testimonial_cpt_metabox',
    'title'          => 'Testimonial Details',
    'pages'          => array('tw_testimonial'),
    'context'        => 'normal',
    'priority'       => 'high',
    'fields'         => array(),
    'local_images'   => true,
    'use_with_theme' => false
  );

  $testimonial_meta =  new AT_Meta_Box($testimonial_config);

  $testimonial_meta->addText('tw_testimonial_source_title',  array('name'=> 'Job Title',        'desc'=>'Testimonial source\'s job title', 'group' => 'start'));
  $testimonial_meta->addText('tw_testimonial_source_company',array('name'=> 'Company Name', 'desc'=>'Testimonial source Company name'));
  $testimonial_meta->addText('tw_testimonial_source_url',    array('name'=> 'Website URL',          'desc'=>'Testimonial source website url. External links must include http://', 'group' => 'end'));

  $testimonial_meta->Finish();
}