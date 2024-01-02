<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoTieFiltersUpgrade' ) ) {

	class WpssoTieFiltersUpgrade {

		private $p;	// Wpsso class object.
		private $a;	// WpssoTie class object.

		/*
		 * Instantiated by WpssoTieFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'rename_options_keys' => 1,
			) );
		}

		public function filter_rename_options_keys( $rename_options ) {

			$rename_options[ 'wpssotie' ] = array(
				8 => array(
					'tie_imagick_jpeg_contrast_level'    => '',
					'tie_imagick_jpeg_normalize'         => '',
					'tie_imagick_jpeg_compress_quality'  => 'tie_imagick_compress_quality',
					'tie_imagick_jpeg_sharpen_radius'    => 'tie_imagick_sharpen_radius',
					'tie_imagick_jpeg_sharpen_sigma'     => 'tie_imagick_sharpen_sigma',
					'tie_imagick_jpeg_sharpen_amount'    => 'tie_imagick_sharpen_amount',
					'tie_imagick_jpeg_sharpen_threshold' => 'tie_imagick_sharpen_threshold',
				),
			);

			return $rename_options;
		}
	}
}
