<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoTieSubmenuImageEditors' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoTieSubmenuImageEditors extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->menu_id   = $id;
			$this->menu_name = $name;
			$this->menu_lib  = $lib;
			$this->menu_ext  = $ext;

			$this->menu_metaboxes = array(
				'wp'     => _x( 'WordPress Settings', 'metabox title', 'wpsso-tune-image-editors' ),
				'phpext' => _x( 'PHP Extension Settings', 'metabox title', 'wpsso-tune-image-editors' ),
			);
		}

		public function show_metabox_phpext( $obj, $mb ) {

			$tabs = array(
				'imagick' => _x( 'ImageMagick', 'metabox tab', 'wpsso-tune-image-editors' ),
			);

			$this->show_metabox_tabbed( $obj, $mb, $tabs );
		}

		protected function get_table_rows( $page_id, $metabox_id, $tab_key = '', $args = array() ) {

			$table_rows = array();
			$match_rows = trim( $page_id . '-' . $metabox_id . '-' . $tab_key, '-' );

			switch ( $match_rows ) {

				case 'image-editors-wp':

					$table_rows[ 'tie_wp_image_editors' ] = '' .
						$this->form->get_th_html( _x( 'Default WordPress Image Editor(s)', 'option label', 'wpsso-tune-image-editors' ),
							$css_class = '', $css_id = 'tie_wp_image_editors' ) .
						'<td>' . $this->form->get_select( 'tie_wp_image_editors', $this->p->cf[ 'form' ][ 'editors' ],
							$css_class = '', $css_id = '', $is_assoc = true ) . '</td>';

					$table_rows[ 'tie_wp_image_adj_filter_prio' ] = $this->form->get_tr_hide( $in_view = 'basic', 'tie_wp_image_adj_filter_prio' ) .
						$this->form->get_th_html( _x( 'Image Adjust Filter Priority', 'option label', 'wpsso-tune-image-editors' ),
							$css_class = '', 'tie_wp_image_adj_filter_prio' ) .
						'<td>' . $this->form->get_input( 'tie_wp_image_adj_filter_prio', $css_class = 'medium' ) . '</td>';

					break;

				case 'image-editors-phpext-imagick':

					/*
					 * Load the WP class libraries to avoid triggering a known bug in EWWW when applying the 'wp_image_editors' filter.
					 */
					require_once ABSPATH . WPINC . '/class-wp-image-editor.php';
					require_once ABSPATH . WPINC . '/class-wp-image-editor-gd.php';
					require_once ABSPATH . WPINC . '/class-wp-image-editor-imagick.php';

					$implementations = apply_filters( 'wp_image_editors', array( 'WP_Image_Editor_Imagick', 'WP_Image_Editor_GD' ) );

					$wp_imagick_position = array_search( 'WP_Image_Editor_Imagick', $implementations );

					if ( false !== $wp_imagick_position ) {

						if ( $wp_imagick_position > 0 ) {

							$css_color = 'orange';

						} else $css_color = 'green';

						$wp_imagick_status = '<font color="' . $css_color . '">' . sprintf( __( 'Used as editor #%d',
							'wpsso-tune-image-editors' ), $wp_imagick_position + 1 ) . '</font>';

					} else {

						$wp_imagick_status = '<font color="red">' . __( 'Not used', 'wpsso-tune-image-editors' ) . '</font>';
					}

					if ( extension_loaded( 'imagick' ) ) {

						$php_imagick_status = '<font color="green">' . __( 'Loaded', 'wpsso-tune-image-editors' ) . '</font>';

					} else {

						$php_imagick_status = '<font color="red">' . __( 'Not loaded', 'wpsso-tune-image-editors' ) . '</font>';
					}

					$table_rows[ 'tie_wp_imagick_avail' ] = '' .
						$this->form->get_th_html( sprintf( _x( 'WordPress %s Editor', 'option label', 'wpsso-tune-image-editors' ), 'ImageMagick' ),
							$css_class = '', $css_id = 'tie_wp_imagick_avail' ) .
						'<td><strong>' . $wp_imagick_status . '</strong></td>';

					$table_rows[ 'tie_php_imagick_avail' ] = '' .
						$this->form->get_th_html( sprintf( _x( 'PHP %s Extension', 'option label', 'wpsso-tune-image-editors' ), 'ImageMagick' ),
							$css_class = '', $css_id = 'tie_php_imagick_avail' ) .
						'<td><strong>' . $php_imagick_status . '</strong></td>';

					$table_rows[ 'tie_imagick_jpeg_adjust' ] = '' .
						$this->form->get_th_html( sprintf( _x( 'Adjust %s Images', 'option label', 'wpsso-tune-image-editors' ), 'JPEG' ),
							$css_class = '', $css_id = 'tie_imagick_jpeg_adjust' ) .
						'<td>' . $this->form->get_checkbox( 'tie_imagick_jpeg_adjust' ) . '</td>';

					$table_rows[ 'tie_imagick_webp_adjust' ] = '' .
						$this->form->get_th_html( sprintf( _x( 'Adjust %s Images', 'option label', 'wpsso-tune-image-editors' ), 'WEBP' ),
							$css_class = '', $css_id = 'tie_imagick_webp_adjust' ) .
						'<td>' . $this->form->get_checkbox( 'tie_imagick_webp_adjust' ) . '</td>';

					$table_rows[ 'tie_imagick_compress_quality' ] = '' .
						$this->form->get_th_html( _x( 'Compression Quality', 'option label', 'wpsso-tune-image-editors' ),
							$css_class = '', $css_id = 'tie_imagick_compress_quality' ) .
						'<td>' . $this->form->get_input( 'tie_imagick_compress_quality', $css_class = 'short' ) . ' ' .
						sprintf( _x( '(recommended %1$s to %2$s)', 'option comment', 'wpsso-tune-image-editors' ), '90', '95' ) . '</td>';

					$table_rows[ 'tie_imagick_sharpen_radius' ] = $this->form->get_tr_hide( $in_view = 'basic', 'tie_imagick_sharpen_radius' ) .
						$this->form->get_th_html( _x( 'Sharpening Radius', 'option label', 'wpsso-tune-image-editors' ),
							$css_class = '', $css_id = 'tie_imagick_sharpen_radius' ) .
						'<td>' . $this->form->get_input( 'tie_imagick_sharpen_radius', $css_class = 'short' ) . ' ' .
						_x( '(recommended 0)', 'option comment', 'wpsso-tune-image-editors' ) . '</td>';

					$table_rows[ 'tie_imagick_sharpen_sigma' ] = $this->form->get_tr_hide( $in_view = 'basic', 'tie_imagick_sharpen_sigma' ) .
						$this->form->get_th_html( _x( 'Sharpening Sigma', 'option label', 'wpsso-tune-image-editors' ),
							$css_class = '', $css_id = 'tie_imagick_sharpen_sigma' ) .
						'<td>' . $this->form->get_input( 'tie_imagick_sharpen_sigma', $css_class = 'short' ) . ' ' .
						sprintf( _x( '(recommended %1$s to %2$s)', 'option comment', 'wpsso-tune-image-editors' ), '0.5', '1.0' ) . '</td>';

					$table_rows[ 'tie_imagick_sharpen_amount' ] = $this->form->get_tr_hide( $in_view = 'basic', 'tie_imagick_sharpen_amount' ) .
						$this->form->get_th_html( _x( 'Sharpening Amount', 'option label', 'wpsso-tune-image-editors' ),
							$css_class = '', $css_id = 'tie_imagick_sharpen_amount' ) .
						'<td>' . $this->form->get_input( 'tie_imagick_sharpen_amount', $css_class = 'short' ) . ' ' .
						sprintf( _x( '(recommended %1$s to %2$s)', 'option comment', 'wpsso-tune-image-editors' ), '0.8', '1.2' ) . '</td>';

					$table_rows[ 'tie_imagick_sharpen_threshold' ] = $this->form->get_tr_hide( $in_view = 'basic', 'tie_imagick_sharpen_threshold' ).
						$this->form->get_th_html( _x( 'Sharpening Threshold', 'option label', 'wpsso-tune-image-editors' ),
							$css_class = '', $css_id = 'tie_imagick_sharpen_threshold' ) .
						'<td>' . $this->form->get_input( 'tie_imagick_sharpen_threshold', $css_class = 'short' ) . ' ' .
						sprintf( _x( '(recommended %1$s to %2$s)', 'option comment', 'wpsso-tune-image-editors' ), '0', '0.05' ) . '</td>';

					break;
			}

			return $table_rows;
		}
	}
}
