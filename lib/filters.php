<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018-2023 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoTieFilters' ) ) {

	class WpssoTieFilters {

		private $p;	// Wpsso class object.
		private $a;	// WpssoTie class object.

		private $editor = null;

		/*
		 * Instantiated by WpssoTie->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			static $do_once = null;

			if ( true === $do_once ) {

				return;	// Stop here.
			}

			$do_once = true;

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'option_type' => 2,
			) );

			if ( is_admin() ) {

				$this->p->util->add_plugin_filters( $this, array(
					'messages_tooltip' => 3,
				) );
			}

			/*
			 * Run at lowest priority to re-define the default editors array.
			 */
			add_filter( 'wp_image_editors', array( $this, 'wp_image_editors' ), PHP_INT_MIN, 1 );

			/*
			 * Run at highest priority to make sure our quality setting is last.
			 */
			add_filter( 'wp_editor_set_quality', array( $this, 'wp_editor_set_quality' ), PHP_INT_MAX, 2 );

			/*
			 * Run at a variable priority to allow image adjustments before/after some plugins or themes.
			 */
			$prio = isset( $this->p->options[ 'tie_wp_image_adj_filter_prio' ] ) ? $this->p->options[ 'tie_wp_image_adj_filter_prio' ] : -1000;

			add_filter( 'image_make_intermediate_size', array( $this, 'image_make_intermediate_size' ), $prio, 1 );
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

				case 'tie_imagick_jpeg_compress_quality':

					return 'pos_num';

				case 'tie_wp_image_adj_filter_prio':
				case 'tie_imagick_jpeg_sharpen_radius':

					return 'integer';

				case 'tie_imagick_jpeg_sharpen_sigma':
				case 'tie_imagick_jpeg_sharpen_amount':

					return 'fnum1';

				case 'tie_imagick_jpeg_sharpen_threshold':

					return 'fnum2';

				case 'tie_imagick_jpeg_adjust':
				case 'tie_imagick_jpeg_contrast_level':

					return 'checkbox';
			}

			return $type;
		}

		/*
		 * Re-define the default image editors array. The array could be modified by other filters afterwards.
		 *
		 * Possible 'tie_wp_image_editors' option values:
		 *
		 * $cf = array(
		 * 	'wp' => array(
		 * 		'editors' => array(
		 * 			'gd'         => array( 'WP_Image_Editor_GD' ),
		 * 			'gd+imagick' => array( 'WP_Image_Editor_GD', 'WP_Image_Editor_Imagick' ),
		 * 			'imagick'    => array( 'WP_Image_Editor_Imagick' ),
		 * 			'imagick+gd' => array( 'WP_Image_Editor_Imagick', 'WP_Image_Editor_GD' ),
		 * 		),
		 * 	),
		 * );
		 */
		public function wp_image_editors( $implementations ) {

			if ( ! empty( $this->p->options[ 'tie_wp_image_editors' ] ) ) {

				$opt_val = $this->p->options[ 'tie_wp_image_editors' ];

				if ( ! empty( $this->p->cf[ 'wp' ][ 'editors' ][ $opt_val ] ) && is_array( $this->p->cf[ 'wp' ][ 'editors' ][ $opt_val ] ) ) {

					return $this->p->cf[ 'wp' ][ 'editors' ][ $opt_val ];
				}
			}

			return $implementations;
		}

		/*
		 * Set image quality to 100 for the image types we are adjusting so we don't re-compress the resized images.
		 */
		public function wp_editor_set_quality( $quality, $mime_type ) {

			if ( null === $this->editor ) {	// Get the current editor only once.

				$editor = _wp_image_editor_choose( array( 'mime_type' => $mime_type ) );
			}

			switch ( $editor ) {

				case 'WP_Image_Editor_Imagick':

					switch ( $mime_type ) {

						case 'image/jpg':
						case 'image/jpeg':

							if ( $this->p->options[ 'tie_imagick_jpeg_adjust' ] ) {

								return 100;
							}

							break;
					}

					break;
			}

			return $quality;
		}

		/*
		 * Apply adjustments to the resized image (leveling, sharpening, etc.).
		 */
		public function image_make_intermediate_size( $file_path ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( ! file_exists( $file_path ) ) {

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'skipped ' . $file_path . ': file does not exist' );
				}

				return $file_path;
			}

			/*
			 * Since WPSSO TIE v2.10.0.
			 *
			 * Use 'wpsso_error_handler' to handle any getimagesize or imagick exceptions.
			 */
			$previous_error_handler = set_error_handler( 'wpsso_error_handler' );

			/*
			 * Note that PHP v7.1 or better is required to get the image size of WebP images.
			 */
			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'calling getimagesize() for ' . $file_path );
			}

			$image_size = getimagesize( $file_path );

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log_arr( 'getimagesize', $image_size );
			}

			if ( ! empty( $image_size[ 'mime' ] ) ) {	// Just in case.

				if ( null === $this->editor ) {	// Get the current editor only once.

					$editor = _wp_image_editor_choose( array( 'mime_type' => $image_size[ 'mime' ] ) );
				}

				/*
				 * Adjust resized images based on the image editor and the image type.
				 */
				switch ( $editor ) {

					case 'WP_Image_Editor_Imagick':

						switch ( $image_size[ 'mime' ] ) {

							case 'image/jpg':
							case 'image/jpeg':

								if ( $this->p->options[ 'tie_imagick_jpeg_adjust' ] ) {

									$file_path = $this->adjust_imagick_jpeg( $file_path );
								}

								break;
						}

						break;
				}
			}

			/*
			 * Since WPSSO TIE v2.10.0.
			 */
			restore_error_handler();

			return $file_path;
		}

		/*
		 * Adjust a JPEG image using ImageMagick.
		 */
		public function adjust_imagick_jpeg( $file_path ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$image = new Imagick( $file_path );

			if ( $this->p->options[ 'tie_imagick_jpeg_contrast_level' ] ) {

				$image->normalizeImage();
			}

			$image->unsharpMaskImage(
				$this->p->options[ 'tie_imagick_jpeg_sharpen_radius' ],
				$this->p->options[ 'tie_imagick_jpeg_sharpen_sigma' ],
				$this->p->options[ 'tie_imagick_jpeg_sharpen_amount' ],
				$this->p->options[ 'tie_imagick_jpeg_sharpen_threshold' ]
			);

			$image->setImageFormat( 'jpg' );
			$image->setImageCompression( Imagick::COMPRESSION_JPEG );
			$image->setImageCompressionQuality( $this->p->options[ 'tie_imagick_jpeg_compress_quality' ] );
			$image->writeImage( $file_path );
			$image->destroy();

			return $file_path;
		}

		/*
		 * Option tooltips specific to this add-on.
		 */
		public function filter_messages_tooltip( $text, $msg_key, $info ) {

			if ( 0 !== strpos( $msg_key, 'tooltip-tie_' ) ) {

				return $text;
			}

			switch ( $msg_key ) {

				case 'tooltip-tie_wp_image_editors':

					$text = __( 'By default, WordPress uses the ImageMagick editor first (provided the PHP "imagick" extension is loaded), and uses the GD editor as a fallback.', 'wpsso-tune-image-editors' ) . ' ';

					$text .= __( 'This option allows you to select a different default editor list for WordPress.', 'wpsso-tune-image-editors' );

					break;

				case 'tooltip-tie_wp_image_adj_filter_prio':

					$text = sprintf( __( '%s hooks the WordPress \'image_make_intermediate_size\' filter to adjust and sharpen images.', 'wpsso-tune-image-editors' ), $this->p->cf[ 'plugin' ][ 'wpssotie' ][ 'short' ] ) . ' ';

					$text .= __( 'You can change the priority at which these adjustments are made, to process images before/after other image processing plugins or custom filter hooks.', 'wpsso-tune-image-editors' );

					break;

				case 'tooltip-tie_wp_imagick_avail':

					$text = sprintf( __( 'Status of the %1$s editor in the WordPress \'%2$s\' array.', 'wpsso-tune-image-editors' ), 'ImageMagick', 'wp_image_editors' ) . ' ';

					$text .= sprintf( __( 'By default, WordPress uses the ImageMagick editor first, as editor #1 in the \'%s\' array.', 'wpsso-tune-image-editors' ), 'wp_image_editors' );

					break;

				case 'tooltip-tie_php_imagick_avail':

					$text = sprintf( __( 'Status of the PHP "%s" extension module.', 'wpsso-tune-image-editors' ), 'imagick' ) . ' ';

					$text .= sprintf( __( 'If the WordPress %1$s editor is available, but the PHP "%2$s" extension is not loaded, contact your hosting provider and ask to have the PHP "%2$s" extension installed.', 'wpsso-tune-image-editors' ), 'ImageMagick', 'imagick' );

					break;

				case 'tooltip-tie_imagick_jpeg_adjust':

					$text = sprintf( __( 'Apply image adjustments for resized %1$s images using %2$s.', 'wpsso-tune-image-editors' ), 'JPEG', 'ImageMagick' );

					break;

				case 'tooltip-tie_imagick_jpeg_contrast_level':

					$text = __( 'Contrast leveling further enhances images by adjusting the pixel colors to span the entire range of available colors.', 'wpsso-tune-image-editors' );

					break;

				case 'tooltip-tie_imagick_jpeg_compress_quality':

					$text = __( 'The resized image compression quality as a positive integer value between 1 and 100. The recommended value is 90 to 95.', 'wpsso-tune-image-editors' );

					break;

				case 'tooltip-tie_imagick_jpeg_sharpen_sigma':

					$text = __( 'The sharpening sigma can be any floating-point value, from 0.1 for almost no sharpening, to 3 or more for severe sharpening.', 'wpsso-tune-image-editors' ) . ' ';

					$text .= __( 'A sharpening sigma value between 0.5 and 1.0 is recommended.', 'wpsso-tune-image-editors' );

					break;

				case 'tooltip-tie_imagick_jpeg_sharpen_radius':

					$text = __( 'The sharpening radius is an integer value, generally one to two times the sharpening sigma value.', 'wpsso-tune-image-editors' ) . ' ';

					$text .= __( 'The best sharpening radius depends on the resized image resolution, and for this reason, the recommended value is 0 (auto).', 'wpsso-tune-image-editors' );

					break;

				case 'tooltip-tie_imagick_jpeg_sharpen_amount':

					$text = __( 'The amount (ie. strength) of the sharpening effect. A larger value increases the contrast of sharpened pixels.', 'wpsso-tune-image-editors' ) . ' ';

					$text .= __( 'The default value is 1.0, and the recommended range is between 0.8 and 1.2.', 'wpsso-tune-image-editors' );

					break;

				case 'tooltip-tie_imagick_jpeg_sharpen_threshold':

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
