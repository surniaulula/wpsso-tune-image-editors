<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018-2023 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoTieImagick' ) ) {

	class WpssoTieImagick {
		
		private $p;	// Wpsso class object.
		private $a;	// WpssoTie class object.

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
		}

		/*
		 * Adjust a JPEG image.
		 */
		public function adjust_jpeg( $file_path ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$image = new Imagick( $file_path );

			/*
			 * Imagick::unsharpMaskImage
			 *
			 * Sharpens an image. We convolve the image with a Gaussian operator of the given radius and standard
			 * deviation (sigma). For reasonable results, radius should be larger than sigma. Use a radius of 0 and
			 * Imagick::UnsharpMaskImage() selects a suitable radius for you. 
			 *
			 * See https://www.php.net/manual/en/imagick.unsharpmaskimage.php.
			 */
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
		 * Adjust a WEBP image.
		 */
		public function adjust_webp( $file_path ) {
		}
	}
}
