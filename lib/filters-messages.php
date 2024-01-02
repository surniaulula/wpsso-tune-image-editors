<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoTieFiltersMessages' ) ) {

	class WpssoTieFiltersMessages {

		private $p;	// Wpsso class object.
		private $a;	// WpssoTie class object.

		/*
		 * Instantiated by WpssoTieFilters->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'messages_tooltip' => 3,
			) );
		}

		public function filter_messages_tooltip( $text, $msg_key, $info ) {

			if ( 0 !== strpos( $msg_key, 'tooltip-tie_' ) ) {

				return $text;
			}

			switch ( $msg_key ) {

				case 'tooltip-tie_wp_image_editors':	// Default WordPress Image Editor(s).

					$text = __( 'By default, WordPress uses the ImageMagick editor first (provided the PHP "imagick" extension is loaded), and uses the GD editor as a fallback.', 'wpsso-tune-image-editors' ) . ' ';

					$text .= __( 'This option allows you to select a different default editor list for WordPress.', 'wpsso-tune-image-editors' );

					break;

				case 'tooltip-tie_wp_image_adj_filter_prio':	// Image Adjust Filter Priority.

					$text = sprintf( __( '%s hooks the WordPress \'image_make_intermediate_size\' filter to adjust and sharpen images.', 'wpsso-tune-image-editors' ), $this->p->cf[ 'plugin' ][ 'wpssotie' ][ 'short' ] ) . ' ';

					$text .= __( 'You can change the filter priority to process images before/after other image processing plugins or custom filter hooks.', 'wpsso-tune-image-editors' );

					break;

				case 'tooltip-tie_wp_imagick_avail':

					$text = sprintf( __( 'Status of the %1$s editor in the WordPress \'%2$s\' array.', 'wpsso-tune-image-editors' ), 'ImageMagick', 'wp_image_editors' ) . ' ';

					$text .= sprintf( __( 'By default, WordPress uses the ImageMagick editor first, as editor #1 in the \'%s\' array.', 'wpsso-tune-image-editors' ), 'wp_image_editors' );

					break;

				case 'tooltip-tie_php_imagick_avail':

					$text = sprintf( __( 'Status of the PHP "%s" extension module.', 'wpsso-tune-image-editors' ), 'imagick' ) . ' ';

					$text .= sprintf( __( 'If the WordPress %1$s editor is available, but the PHP "%2$s" extension is not loaded, contact your hosting provider and ask to have the PHP "%2$s" extension installed.', 'wpsso-tune-image-editors' ), 'ImageMagick', 'imagick' );

					break;

				case 'tooltip-tie_imagick_jpeg_adjust':	// Adjust JPEG Images.

					$text = sprintf( __( 'Apply image adjustments for resized %1$s images using %2$s.', 'wpsso-tune-image-editors' ), 'JPEG', 'ImageMagick' );

					break;

				case 'tooltip-tie_imagick_webp_adjust':	// Adjust WEBP Images.

					$text = sprintf( __( 'Apply image adjustments for resized %1$s images using %2$s.', 'wpsso-tune-image-editors' ), 'WEBP', 'ImageMagick' );

					break;

				case 'tooltip-tie_imagick_compress_quality':	// Compression Quality.

					$text = __( 'The resized image compression quality as a positive integer value between 1 and 100. The recommended value is 90 to 95.', 'wpsso-tune-image-editors' );

					break;

				case 'tooltip-tie_imagick_sharpen_radius':	// Sharpening Radius.

					$text = __( 'The sharpening radius is an integer value, generally one to two times the sharpening sigma value.', 'wpsso-tune-image-editors' ) . ' ';

					$text .= __( 'The best sharpening radius depends on the resized image resolution, and for this reason, the recommended value is 0 (auto).', 'wpsso-tune-image-editors' );

					break;

				case 'tooltip-tie_imagick_sharpen_sigma':	// Sharpening Sigma.

					$text = __( 'The sharpening sigma can be any floating-point value, from 0.1 for almost no sharpening, to 3 or more for severe sharpening.', 'wpsso-tune-image-editors' ) . ' ';

					$text .= __( 'A sharpening sigma value between 0.5 and 1.0 is recommended.', 'wpsso-tune-image-editors' );

					break;

				case 'tooltip-tie_imagick_sharpen_amount':	// Sharpening Amount.

					$text = __( 'The amount (ie. strength) of the sharpening effect. A larger value increases the contrast of sharpened pixels.', 'wpsso-tune-image-editors' ) . ' ';

					$text .= __( 'The default value is 1.0, and the recommended range is between 0.8 and 1.2.', 'wpsso-tune-image-editors' );

					break;

				case 'tooltip-tie_imagick_sharpen_threshold':	// Sharpening Threshold.

					$text = __( 'Minimum contrast required for a pixel to be considered an edge pixel for sharpening.', 'wpsso-tune-image-editors' ) . ' ';

					$text .= __( 'Higher values (closer to 1) allow sharpening only in high-contrast regions, like strong edges, while leaving low-contrast areas unaffected.', 'wpsso-tune-image-editors' ) . ' ';

					$text .= __( 'Lower values (closer to 0) allow sharpening in relatively smoother regions of the image.', 'wpsso-tune-image-editors' ) . ' ';

					$text .= __( 'A value of 0 may be desirable to retain fine skin details in portrait photographs.', 'wpsso-tune-image-editors' );

					break;
			}

			return $text;
		}
	}
}
