<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2016-2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoTieFilters' ) ) {

	class WpssoTieFilters {

		protected $p;
		protected $editor = null;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( is_admin() ) {
				$this->p->util->add_plugin_filters( $this, array( 
					'option_type' => 2,
					'messages_tooltip' => 3,		// tooltip messages filter
				) );
			}

			/**
			 * Run at lowest priority to re-define the default editors array.
			 */
			add_filter( 'wp_image_editors', array( &$this, 'wp_image_editors' ), SucomUtil::get_min_int(), 1 );

			add_filter( 'wp_editor_set_quality', array( &$this, 'wp_editor_set_quality' ), SucomUtil::get_max_int(), 2 );

			add_filter( 'image_make_intermediate_size', array( &$this, 'image_make_intermediate_size' ), $this->p->options['tie_wp_image_adj_filter_prio'], 1 );
		}

		public function wp_image_editors( $implementations ) {

			if ( ! empty( $this->p->options['tie_wp_image_editors'] ) ) {

				$opt_val = $this->p->options['tie_wp_image_editors'];

				if ( ! empty( $this->p->cf['wp']['editors'][$opt_val] ) && 
					is_array( $this->p->cf['wp']['editors'][$opt_val] ) ) {

					return $this->p->cf['wp']['editors'][$opt_val];
				}
			}

			return $implementations;
		}

		/**
		 * Set image quality to 100 for the image types we are adjusting so we don't re-compress the resized images.
		 */
		public function wp_editor_set_quality( $quality, $mime_type ) {

			if ( $this->editor === null ) {
				$editor = _wp_image_editor_choose( array( 'mime_type' => $mime_type ) );
			}

			switch ( $editor ) {

				case 'WP_Image_Editor_Imagick':

					switch ( $mime_type ) {

						case 'image/jpeg':

							if ( $this->p->options['tie_imagick_adjust_jpeg'] ) {
								return 100;
							}

							break;
					}

					break;
			}

			return $quality;
		}

		public function image_make_intermediate_size( $filepath ) {

			if ( ! file_exists( $filepath ) ) {
				return $filepath;
			}
				
			$image_size = @getimagesize( $filepath );

			if ( empty( $image_size['mime'] ) ) {
				return $filepath;
			}

			if ( $this->editor === null ) {
				$editor = _wp_image_editor_choose( array( 'mime_type' => $image_size['mime'] ) );
			}

			switch ( $editor ) {

				case 'WP_Image_Editor_Imagick':

					switch ( $image_size['mime'] ) {

						case 'image/jpeg':

							if ( $this->p->options['tie_imagick_adjust_jpeg'] ) {
								return $this->adjust_imagick_jpeg( $filepath );
							}

							break;
					}

					break;
			}

			return $filepath;
		}

		protected function adjust_imagick_jpeg( $filepath ) {

			$image = new Imagick( $filepath );

			if ( $this->p->options['tie_imagick_contrast_leveling'] ) {
				$image->normalizeImage();
			}

			$image->unsharpMaskImage(
				$this->p->options['tie_imagick_sharpen_radius'],
				$this->p->options['tie_imagick_sharpen_sigma'],
				$this->p->options['tie_imagick_sharpen_amount'],
				$this->p->options['tie_imagick_sharpen_threshold']
			);

			$image->setImageFormat( 'jpg' );
			$image->setImageCompression( Imagick::COMPRESSION_JPEG );
			$image->setImageCompressionQuality( $this->p->options['tie_imagick_compress_quality'] );
			$image->writeImage( $filepath );
			$image->destroy();

			return $filepath;
		}

		public function filter_messages_tooltip( $text, $idx, $info ) {

			if ( strpos( $idx, 'tooltip-tie_' ) !== 0 ) {
				return $text;
			}

			switch ( $idx ) {

				case 'tooltip-tie_wp_image_editors':

					$text = __( 'By default, WordPress uses the ImageMagick editor first (provided the PHP "imagick" extension is loaded), and uses the GD editor as a fallback.', 'wpsso-tune-image-extension' ).' ';
					
					$text .= __( 'This option allows you to select a different default editor list for WordPress.', 'wpsso-tune-image-extension' );

					break;

				case 'tooltip-tie_wp_image_adj_filter_prio':

					$short = $this->p->cf['plugin']['wpssotie']['short'];

					$text = sprintf( __( '%s hooks the WordPress \'image_make_intermediate_size\' filter to adjust and sharpen images.', 'wpsso-tune-image-extension' ), $short ).' ';
					
					$text .= __( 'You may change the priority at which these adjustments are made to process images before / after other image processing plugins or custom filter hooks.', 'wpsso-tune-image-extension' );

					break;

				case 'tooltip-tie_wp_imagick_avail':

					$text = sprintf( __( 'Status of the ImageMagick editor in the WordPress \'%s\' array.', 'wpsso-tune-image-extension' ), 'wp_image_editors' ).' ';

					$text .= sprintf( __( 'By default, WordPress uses the ImageMagick editor first, as editor #1 in the \'%s\' array.', 'wpsso-tune-image-extension' ), 'wp_image_editors' );

					break;

				case 'tooltip-tie_php_imagick_avail':

					$text = __( 'Status of the PHP "imagick" extension.', 'wpsso-tune-image-extension' ).' ';
					
					$text .= __( 'If the WordPress ImageMagick editor is available, but the PHP "imagick" extension is not loaded, contact your hosting provider and ask to have the PHP "imagick" extension installed.', 'wpsso-tune-image-extension' );

					break;

				case 'tooltip-tie_imagick_adjust_enable':

					$text = __( 'Enabled image adjustments only for these checked image types.', 'wpsso-tune-image-extension' );

					break;

				case 'tooltip-tie_imagick_compress_quality':

					$text = __( 'The resized image compression quality as a positive integer value between 1 and 100. The recommended value is 90 to 95.', 'wpsso-tune-image-extension' );

					break;

				case 'tooltip-tie_imagick_contrast_leveling':

					$text = __( 'Contrast leveling further enhances the resized image by adjusting the pixel colors to span the entire range of available colors.', 'wpsso-tune-image-extension' );

					break;

				case 'tooltip-tie_imagick_sharpen_sigma':

					$text = __( 'The sharpening sigma can be any floating point value, from 0.1 for almost no sharpening, to 3 or more for severe sharpening. A sigma value between 0.5 and 1.0 is recommended.', 'wpsso-tune-image-extension' );

					break;

				case 'tooltip-tie_imagick_sharpen_radius':

					$text = __( 'The sharpening radius is an integer value, one to two times the sharpening sigma value. The best sharpening radius also depends on the resized image resolution, and for this reason, the default and commended value is 0 (auto).', 'wpsso-tune-image-extension' );

					break;

				case 'tooltip-tie_imagick_sharpen_amount':

					$text = __( 'The amount (ie. strength) of the sharpening effect. A larger value increases the contrast of sharpened pixels. The default value is 1.0 and the recommended range is between 0.8 and 1.2.', 'wpsso-tune-image-extension' );

					break;

				case 'tooltip-tie_imagick_sharpen_threshold':

					$text = __( 'Minimum contrast required for a pixel to be considered an edge pixel for sharpening. Higher values (closer to 1) allow sharpening only in high-contrast regions, such as strong edges, while leaving low-contrast regions unaffected. Lower values (closer to 0) allow sharpening in relatively smoother regions of the image. A value of 0 may be desirable to retain fine skin details in portrait photographs.', 'wpsso-tune-image-extension' );

					break;
			}

			return $text;
		}

		public function filter_option_type( $type, $base_key ) {

			if ( ! empty( $type ) ) {
				return $type;
			} elseif ( strpos( $base_key, 'tie_' ) !== 0 ) {
				return $type;
			}

			switch ( $base_key ) {
				case 'tie_wp_image_editors':
					return 'not_blank';
					break;
				case 'tie_imagick_compress_quality':
					return 'pos_num';
					break;
				case 'tie_wp_image_adj_filter_prio':
				case 'tie_imagick_sharpen_radius':
					return 'integer';
					break;
				case 'tie_imagick_sharpen_sigma':
				case 'tie_imagick_sharpen_amount':
					return 'float1';
					break;
				case 'tie_imagick_sharpen_threshold':
					return 'float2';
					break;
				case 'tie_imagick_adjust_jpeg':
				case 'tie_imagick_contrast_leveling':
					return 'checkbox';
					break;
			}

			return $type;
		}
	}
}
