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
$prefix = 'tw_';

$testimonial_category = get_option('wpt_tw_testimonial_category') ? get_option('wpt_tw_testimonial_category') : "off";
$testimoinal_tag      = get_option('wpt_tw_testimonial_tag') ? get_option('wpt_tw_testimonial_tag') : "off";



TW_Testimonials_Plugin()->register_post_type(
                        'tw_testimonial',
                        __( 'Testimonials',     'tw-testimonials-plugin' ),
                        __( 'Testimonial',      'tw-testimonials-plugin' ),
                        __( 'Testimonials CPT', 'tw-testimonials-plugin'),
                        array(
                          'menu_icon'=>plugins_url( 'assets/img/cpt-icon-testimonial.png', __FILE__ ),
                        )
                    );

if($testimonial_category=='on'){
  TW_Testimonials_Plugin()->register_taxonomy( 'tw_testimonial_category', __( 'Testimonial Categories', 'tw-testimonials-plugin' ), __( 'Testimonial Category', 'tw' ), 'tw_testimonial', array('hierarchical'=>true) );
}

if($testimoinal_tag=='on'){
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

  $testimonial_meta->addText($prefix.'testimonial_source_title',  array('name'=> 'Source Title',        'desc'=>'Testimonial source\'s job title', 'group' => 'start'));
  $testimonial_meta->addText($prefix.'testimonial_source_company',array('name'=> 'Source Company Name', 'desc'=>'Testimonial source Company name'));
  $testimonial_meta->addText($prefix.'testimonial_source_url',    array('name'=> 'Source URL',          'desc'=>'Testimonial source website url. External links must include http://', 'group' => 'end'));

  $testimonial_meta->Finish();
}