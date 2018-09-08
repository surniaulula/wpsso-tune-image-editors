<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2016-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoTieConfig' ) ) {

	class WpssoTieConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssotie' => array(			// Plugin acronym.
					'version'     => '1.2.0',	// Plugin version.
					'opt_version' => '4',		// Increment when changing default option values.
					'short'       => 'WPSSO TIE',	// Short plugin name.
					'name'        => 'WPSSO Tune WP Image Editors',
					'desc'        => 'WPSSO Core add-on to provide tuning options for the WordPress image editors and PHP image extensions.',
					'slug'        => 'wpsso-tune-image-editors',
					'base'        => 'wpsso-tune-image-editors/wpsso-tune-image-editors.php',
					'update_auth' => 'tid',
					'text_domain' => 'wpsso-tune-image-editors',
					'domain_path' => '/languages',
					'req' => array(
						'short'       => 'WPSSO Core',
						'name'        => 'WPSSO Core',
						'min_version' => '4.10.0',
					),
					'img' => array(
						'icons' => array(
							'low'  => 'images/icon-128x128.png',
							'high' => 'images/icon-256x256.png',
						),
					),
					'lib' => array(
						'submenu' => array(	// Note that submenu elements must have unique keys.
							'tie-general' => 'WP Image Editors',
						),
						'gpl' => array(
							'admin' => array(
								'tie-general' => 'WP Image Editors',
							),
						),
						'pro' => array(
							'admin' => array(
								'tie-general' => 'WP Image Editors',
							),
						),
					),
				),
			),
			'opt' => array(						// options
				'defaults' => array(
					'tie_wp_image_editors'               => 'imagick+gd',
					'tie_wp_image_adj_filter_prio'       => -1000,	// integer
					'tie_imagick_jpeg_adjust'            => 1,	// checkbox
					'tie_imagick_jpeg_auto_level'        => 0,	// checkbox
					'tie_imagick_jpeg_contrast_level'    => 0,	// checkbox
					'tie_imagick_jpeg_compress_quality'  => 92,	// positive number
					'tie_imagick_jpeg_sharpen_sigma'     => 0.5,	// floating-point number
					'tie_imagick_jpeg_sharpen_radius'    => 0,	// integer
					'tie_imagick_jpeg_sharpen_amount'    => 1.0,	// floating-point number
					'tie_imagick_jpeg_sharpen_threshold' => 0.05,	// floating-point number
				),
			),
			'wp' => array(
				'editors' => array(
					'gd'         => array( 'WP_Image_Editor_GD' ),
					'gd+imagick' => array( 'WP_Image_Editor_GD', 'WP_Image_Editor_Imagick' ),
					'imagick'    => array( 'WP_Image_Editor_Imagick' ),
					'imagick+gd' => array( 'WP_Image_Editor_Imagick', 'WP_Image_Editor_GD' ),
				),
			),
			'form' => array(
				'editors' => array(
					'gd'         => 'GD Only',
					'gd+imagick' => 'GD and ImageMagick',
					'imagick'    => 'ImageMagick Only',
					'imagick+gd' => 'ImageMagick and GD',
				),
			),
		);

		public static function get_version( $add_slug = false ) {
			$ext = 'wpssotie';
			$info =& self::$cf['plugin'][$ext];
			return $add_slug ? $info['slug'].'-'.$info['version'] : $info['version'];
		}

		public static function set_constants( $plugin_filepath ) { 

			if ( defined( 'WPSSOTIE_VERSION' ) ) {	// Define constants only once.
				return;
			}

			define( 'WPSSOTIE_FILEPATH', $plugin_filepath );						
			define( 'WPSSOTIE_PLUGINBASE', self::$cf['plugin']['wpssotie']['base'] );		// wpsso-tune-image-editors/wpsso-tune-image-editors.php
			define( 'WPSSOTIE_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_filepath ) ) ) );
			define( 'WPSSOTIE_PLUGINSLUG', self::$cf['plugin']['wpssotie']['slug'] );		// wpsso-tune-image-editors
			define( 'WPSSOTIE_URLPATH', trailingslashit( plugins_url( '', $plugin_filepath ) ) );
			define( 'WPSSOTIE_VERSION', self::$cf['plugin']['wpssotie']['version'] );						
		}

		public static function require_libs( $plugin_filepath ) {

			require_once WPSSOTIE_PLUGINDIR.'lib/register.php';
			require_once WPSSOTIE_PLUGINDIR.'lib/filters.php';

			add_filter( 'wpssotie_load_lib', array( 'WpssoTieConfig', 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $ret = false, $filespec = '', $classname = '' ) {
			if ( false === $ret && ! empty( $filespec ) ) {
				$filepath = WPSSOTIE_PLUGINDIR.'lib/'.$filespec.'.php';
				if ( file_exists( $filepath ) ) {
					require_once $filepath;
					if ( empty( $classname ) ) {
						return SucomUtil::sanitize_classname( 'wpssotie'.$filespec, false );	// $underscore = false
					} else {
						return $classname;
					}
				}
			}
			return $ret;
		}
	}
}
