<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoTieConfig' ) ) {

	class WpssoTieConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssotie' => array(			// Plugin acronym.
					'version'     => '4.1.0',	// Plugin version.
					'opt_version' => '9',		// Increment when changing default option values.
					'short'       => 'WPSSO TIE',	// Short plugin name.
					'name'        => 'WPSSO Tune WP Image Editors',
					'desc'        => 'Improves the appearance of WordPress images for better click through rates from social and search sites.',
					'slug'        => 'wpsso-tune-image-editors',
					'base'        => 'wpsso-tune-image-editors/wpsso-tune-image-editors.php',
					'update_auth' => '',		// No premium version.
					'text_domain' => 'wpsso-tune-image-editors',
					'domain_path' => '/languages',

					/*
					 * Required plugin and its version.
					 */
					'req' => array(
						'wpsso' => array(
							'name'          => 'WPSSO Core',
							'home'          => 'https://wordpress.org/plugins/wpsso/',
							'plugin_class'  => 'Wpsso',
							'version_const' => 'WPSSO_VERSION',
							'min_version'   => '18.10.0',
						),
					),

					/*
					 * URLs or relative paths to plugin banners and icons.
					 */
					'assets' => array(

						/*
						 * Icon image array keys are '1x' and '2x'.
						 */
						'icons' => array(
							'1x' => 'images/icon-128x128.png',
							'2x' => 'images/icon-256x256.png',
						),
					),

					/*
					 * Library files loaded and instantiated by WPSSO.
					 */
					'lib' => array(
						'submenu' => array(
							'image-editors' => 'Image Editors',
						),
					),
				),
			),

			/*
			 * Additional add-on setting options.
			 */
			'opt' => array(
				'defaults' => array(
					'tie_wp_image_editors'          => 'imagick+gd',
					'tie_wp_image_adj_filter_prio'  => -1000,		// Integer.
					'tie_imagick_jpeg_adjust'       => 1,			// Checkbox.
					'tie_imagick_webp_adjust'       => 1,			// Checkbox.
					'tie_imagick_auto_level'        => 0,			// Checkbox.
					'tie_imagick_compress_quality'  => 92,			// Positive integer.
					'tie_imagick_sharpen_radius'    => 0,			// Integer.
					'tie_imagick_sharpen_sigma'     => '0.5',		// Floating-point number (string).
					'tie_imagick_sharpen_amount'    => '1.0',		// Floating-point number (string).
					'tie_imagick_sharpen_threshold' => '0.05',		// Floating-point number (string).
				),	// End of 'defaults' array.
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

		public static function set_constants( $plugin_file ) {

			if ( defined( 'WPSSOTIE_VERSION' ) ) {	// Define constants only once.

				return;
			}

			$info =& self::$cf[ 'plugin' ][ 'wpssotie' ];

			/*
			 * Define fixed constants.
			 */
			define( 'WPSSOTIE_FILEPATH', $plugin_file );
			define( 'WPSSOTIE_PLUGINBASE', $info[ 'base' ] );	// Example: wpsso-tune-image-editors/wpsso-tune-image-editors.php.
			define( 'WPSSOTIE_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_file ) ) ) );
			define( 'WPSSOTIE_PLUGINSLUG', $info[ 'slug' ] );	// Example: wpsso-tune-image-editors.
			define( 'WPSSOTIE_URLPATH', trailingslashit( plugins_url( '', $plugin_file ) ) );
			define( 'WPSSOTIE_VERSION', $info[ 'version' ] );
		}

		public static function require_libs( $plugin_file ) {

			require_once WPSSOTIE_PLUGINDIR . 'lib/filters.php';
			require_once WPSSOTIE_PLUGINDIR . 'lib/imagick.php';
			require_once WPSSOTIE_PLUGINDIR . 'lib/register.php';

			add_filter( 'wpssotie_load_lib', array( __CLASS__, 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $success = false, $filespec = '', $classname = '' ) {

			if ( false !== $success ) {

				return $success;
			}

			if ( ! empty( $classname ) ) {

				if ( class_exists( $classname ) ) {

					return $classname;
				}
			}

			if ( ! empty( $filespec ) ) {

				$file_path = WPSSOTIE_PLUGINDIR . 'lib/' . $filespec . '.php';

				if ( file_exists( $file_path ) ) {

					require_once $file_path;

					if ( empty( $classname ) ) {

						return SucomUtil::sanitize_classname( 'wpssotie' . $filespec, $allow_underscore = false );
					}

					return $classname;
				}
			}

			return $success;
		}
	}
}
