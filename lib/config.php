<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2016-2019 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoTieConfig' ) ) {

	class WpssoTieConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssotie' => array(			// Plugin acronym.
					'version'     => '2.0.3',	// Plugin version.
					'opt_version' => '4',		// Increment when changing default option values.
					'short'       => 'WPSSO TIE',	// Short plugin name.
					'name'        => 'WPSSO Tune WP Image Editors',
					'desc'        => 'Sharpen and improve WordPress thumbnails and resized images for social sharing and better SEO.',
					'slug'        => 'wpsso-tune-image-editors',
					'base'        => 'wpsso-tune-image-editors/wpsso-tune-image-editors.php',
					'update_auth' => '',	// No premium version.
					'text_domain' => 'wpsso-tune-image-editors',
					'domain_path' => '/languages',
					'req'         => array(
						'short'       => 'WPSSO Core',
						'name'        => 'WPSSO Core',
						'min_version' => '6.13.2',
					),
					'assets' => array(
						'icons' => array(
							'low'  => 'images/icon-128x128.png',
							'high' => 'images/icon-256x256.png',
						),
					),
					'lib' => array(
						'pro' => array(
						),
						'std' => array(
						),
						'submenu' => array(
							'tie-general' => 'WP Image Editors',
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

			$info =& self::$cf[ 'plugin' ][ 'wpssotie' ];

			return $add_slug ? $info[ 'slug' ] . '-' . $info[ 'version' ] : $info[ 'version' ];
		}

		public static function set_constants( $plugin_filepath ) { 

			if ( defined( 'WPSSOTIE_VERSION' ) ) {	// Define constants only once.
				return;
			}

			$info =& self::$cf[ 'plugin' ][ 'wpssotie' ];

			/**
			 * Define fixed constants.
			 */
			define( 'WPSSOTIE_FILEPATH', $plugin_filepath );						
			define( 'WPSSOTIE_PLUGINBASE', $info[ 'base' ] );	// Example: wpsso-tune-image-editors/wpsso-tune-image-editors.php.
			define( 'WPSSOTIE_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_filepath ) ) ) );
			define( 'WPSSOTIE_PLUGINSLUG', $info[ 'slug' ] );	// Example: wpsso-tune-image-editors.
			define( 'WPSSOTIE_URLPATH', trailingslashit( plugins_url( '', $plugin_filepath ) ) );
			define( 'WPSSOTIE_VERSION', $info[ 'version' ] );						
		}

		public static function require_libs( $plugin_filepath ) {

			require_once WPSSOTIE_PLUGINDIR . 'lib/filters.php';
			require_once WPSSOTIE_PLUGINDIR . 'lib/register.php';

			add_filter( 'wpssotie_load_lib', array( 'WpssoTieConfig', 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $ret = false, $filespec = '', $classname = '' ) {

			if ( false === $ret && ! empty( $filespec ) ) {

				$filepath = WPSSOTIE_PLUGINDIR . 'lib/' . $filespec . '.php';

				if ( file_exists( $filepath ) ) {

					require_once $filepath;

					if ( empty( $classname ) ) {
						return SucomUtil::sanitize_classname( 'wpssotie' . $filespec, $allow_underscore = false );
					} else {
						return $classname;
					}
				}
			}

			return $ret;
		}
	}
}
