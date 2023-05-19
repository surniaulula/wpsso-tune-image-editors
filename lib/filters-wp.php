<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018-2023 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoTieFiltersWp' ) ) {

	class WpssoTieFiltersWp {

		private $p;	// Wpsso class object.
		private $a;	// WpssoTie class object.

		private $cache_editors = array();	// Editor by mime type.

		/*
		 * Instantiated by WpssoTieFilters->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

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

			if ( ! empty( $mime_type ) ) {	// Just in case.

				if ( empty( $this->cache_editors[ $mime_type ] ) ) {
	
					$this->cache_editors[ $mime_type ] = _wp_image_editor_choose( array( 'mime_type' => $mime_type ) );
				}
	
				switch ( $mime_type ) {
	
					case 'image/jpg':
					case 'image/jpeg':
	
						if ( $this->p->options[ 'tie_imagick_jpeg_adjust' ] ) {
	
							if ( 'WP_Image_Editor_Imagick' === $this->cache_editors[ $mime_type ] ) {
							
								return 100;
							}
						}
	
						break;
	
					case 'image/webp':
	
						if ( $this->p->options[ 'tie_imagick_webp_adjust' ] ) {
	
							if ( 'WP_Image_Editor_Imagick' === $this->cache_editors[ $mime_type ] ) {
							
								return 100;
							}
						}
	
						break;
				}
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

				$mime_type = $image_size[ 'mime' ];

				if ( empty( $this->cache_editors[ $mime_type ] ) ) {

					$this->cache_editors[ $mime_type ] = _wp_image_editor_choose( array( 'mime_type' => $mime_type ) );
				}

				switch ( $mime_type ) {

					case 'image/jpg':
					case 'image/jpeg':

						if ( $this->p->options[ 'tie_imagick_jpeg_adjust' ] ) {

							if ( 'WP_Image_Editor_Imagick' === $this->cache_editors[ $mime_type ] ) {

								$file_path = $this->a->imagick->adjust_image( $mime_type, $file_path );
							}
						}

						break;

					case 'image/webp':

						if ( $this->p->options[ 'tie_imagick_webp_adjust' ] ) {

							if ( 'WP_Image_Editor_Imagick' === $this->cache_editors[ $mime_type ] ) {

								$file_path = $this->a->imagick->adjust_image( $mime_type, $file_path );
							}
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
	}
}
