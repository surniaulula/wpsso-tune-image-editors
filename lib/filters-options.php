<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoTieFiltersOptions' ) ) {

	class WpssoTieFiltersOptions {

		private $p;	// Wpsso class object.
		private $a;	// WpssoTie class object.

		/*
		 * Instantiated by WpssoTieFilters->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'option_type' => 2,
			) );
		}

		/*
		 * Return the sanitation type for a given option key.
		 */
		public function filter_option_type( $type, $base_key ) {

			if ( ! empty( $type ) ) {	// Return early if we already have a type.

				return $type;

			} elseif ( 0 !== strpos( $base_key, 'tie_' ) ) {	// Nothing to do.

				return $type;
			}

			switch ( $base_key ) {

				case 'tie_wp_image_editors':

					return 'not_blank';

				case 'tie_imagick_compress_quality':

					return 'pos_int';

				case 'tie_wp_image_adj_filter_prio':
				case 'tie_imagick_sharpen_radius':

					return 'integer';

				case 'tie_imagick_sharpen_sigma':
				case 'tie_imagick_sharpen_amount':

					return 'fnum1';

				case 'tie_imagick_sharpen_threshold':

					return 'fnum2';

				case 'tie_imagick_jpeg_adjust':
				case 'tie_imagick_webp_adjust':

					return 'checkbox';
			}

			return $type;
		}
	}
}
