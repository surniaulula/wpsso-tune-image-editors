<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoTieSubmenuTieGeneral' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoTieSubmenuTieGeneral extends WpssoAdmin {

		private $implementations = array();

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->menu_id   = $id;
			$this->menu_name = $name;
			$this->menu_lib  = $lib;
			$this->menu_ext  = $ext;
		}

		/**
		 * Called by the extended WpssoAdmin class.
		 */
		protected function add_meta_boxes() {

			$ext_name = $this->p->cf[ 'plugin' ][ 'wpssotie' ][ 'name' ];

			$regen_url = 'https://wordpress.org/plugins/search/regenerate+thumbnails/';

			$notice_key = 'wpsso-tie-notice-regenrate-all-image-thumbnails';

			$notice_msg = '<p>' . __( 'After activating the %1$s add-on or changing its settings, don\'t forget to <a href="%2$s">regenerate all image thumbnail and image sizes</a> to see the results.', 'wpsso-tune-image-editors' ) . '</p>';

			$this->p->notice->nag( sprintf( $notice_msg, $ext_name, $regen_url ), null, $notice_key );

			$metabox_id      = 'wp';
			$metabox_title   = _x( 'WordPress Settings', 'metabox title', 'wpsso-tune-image-editors' );
			$metabox_screen  = $this->pagehook;
			$metabox_context = 'normal';
			$metabox_prio    = 'default';
			$callback_args   = array(	// Second argument passed to the callback function / method.
			);

			add_meta_box( $this->pagehook . '_' . $metabox_id, $metabox_title,
				array( $this, 'show_metabox_wp' ), $metabox_screen,
					$metabox_context, $metabox_prio, $callback_args );

			$metabox_id      = 'ext';
			$metabox_title   = _x( 'PHP Extension Settings', 'metabox title', 'wpsso-tune-image-editors' );
			$metabox_screen  = $this->pagehook;
			$metabox_context = 'normal';
			$metabox_prio    = 'default';
			$callback_args   = array(	// Second argument passed to the callback function / method.
			);

			add_meta_box( $this->pagehook . '_' . $metabox_id, $metabox_title,
				array( $this, 'show_metabox_ext' ), $metabox_screen,
					$metabox_context, $metabox_prio, $callback_args );
		}

		public function show_metabox_wp() {

			/**
			 * Load the WP class libraries to avoid triggering a known bug in EWWW
			 * when applying the 'wp_image_editors' filter.
			 */
			require_once ABSPATH . WPINC . '/class-wp-image-editor.php';
			require_once ABSPATH . WPINC . '/class-wp-image-editor-gd.php';
			require_once ABSPATH . WPINC . '/class-wp-image-editor-imagick.php';

			$this->implementations = apply_filters( 'wp_image_editors', array( 'WP_Image_Editor_Imagick', 'WP_Image_Editor_GD' ) );

			$metabox_id = 'tie-wp';

			$tab_key = 'general';

			$filter_name = SucomUtil::sanitize_hookname( 'wpsso_' . $metabox_id . '_' . $tab_key . '_rows' );

			$table_rows = $this->get_table_rows( $metabox_id, $tab_key );

			$table_rows = apply_filters( $filter_name, $table_rows, $this->form, $network = false );

			$this->p->util->metabox->do_table( $table_rows, 'metabox-' . $metabox_id . '-' . $tab_key );
		}

		public function show_metabox_ext() {

			$metabox_id = 'tie-ext';

			$tabs = array( 'imagick' => _x( 'ImageMagick', 'metabox tab', 'wpsso-tune-image-editors' ) );

			$table_rows = array();

			foreach ( $tabs as $tab_key => $title ) {

				$filter_name = SucomUtil::sanitize_hookname( 'wpsso_' . $metabox_id . '_' . $tab_key . '_rows' );

				$table_rows[ $tab_key ] = $this->get_table_rows( $metabox_id, $tab_key );

				$table_rows[ $tab_key ] = apply_filters( $filter_name, $table_rows[ $tab_key ], $this->form, $network = false );
			}

			$this->p->util->metabox->do_tabbed( $metabox_id, $tabs, $table_rows );
		}

		protected function get_table_rows( $metabox_id, $tab_key ) {

			$table_rows = array();

			switch ( $metabox_id . '-' . $tab_key ) {

				case 'tie-wp-general':

					$table_rows[ 'tie_wp_image_editors' ] = '' . 
						$this->form->get_th_html( _x( 'Default WordPress Image Editor(s)', 'option label', 'wpsso-tune-image-editors' ),
							$css_class = '', $css_id = 'tie_wp_image_editors' ) . 
						'<td>' . $this->form->get_select( 'tie_wp_image_editors', $this->p->cf[ 'form' ][ 'editors' ],
							$css_class = '', $css_id = '', $is_assoc = true ) . '</td>';

					$table_rows[ 'tie_wp_image_adj_filter_prio' ] = $this->form->get_tr_hide( 'basic', 'tie_wp_image_adj_filter_prio' ) . 
						$this->form->get_th_html( _x( 'Adjustment Filter Priority', 'option label', 'wpsso-tune-image-editors' ),
							$css_class = '', 'tie_wp_image_adj_filter_prio' ) . 
						'<td>' . $this->form->get_input( 'tie_wp_image_adj_filter_prio', $css_class = 'medium' ) . '</td>';

					break;

				case 'tie-ext-imagick':

					$wp_imagick_position = array_search( 'WP_Image_Editor_Imagick', $this->implementations );

					if ( false !== $wp_imagick_position ) {

						if ( $wp_imagick_position > 0 ) {

							$css_color = 'orange';

						} else {

							$css_color = 'green';
						}

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

					$table_rows[ 'subsection_imagick_jpeg' ] = '' .
						'<td colspan="2" class="subsection"><h4>' . 
						sprintf( _x( '%s Resized Images', 'metabox title', 'wpsso-tune-image-editors' ), 'JPEG' ) . '</h4></td>';

					$table_rows[ 'tie_imagick_jpeg_adjust' ] = '' . 
						$this->form->get_th_html( sprintf( _x( 'Adjust %s Images', 'option label', 'wpsso-tune-image-editors' ), 'JPEG' ),
							$css_class = '', $css_id = 'tie_imagick_jpeg_adjust' ) . 
						'<td>' . $this->form->get_checkbox( 'tie_imagick_jpeg_adjust' ) . '</td>';

					$table_rows[ 'tie_imagick_jpeg_contrast_level' ] = $this->form->get_tr_hide( 'basic', 'tie_imagick_jpeg_contrast_level' ) . 
						$this->form->get_th_html( _x( 'Contrast Leveling', 'option label', 'wpsso-tune-image-editors' ),
							$css_class = '', $css_id = 'tie_imagick_jpeg_contrast_level' ) . 
						'<td>' . $this->form->get_checkbox( 'tie_imagick_jpeg_contrast_level' ) . '</td>';

					$table_rows[ 'tie_imagick_jpeg_compress_quality' ] = '' . 
						$this->form->get_th_html( _x( 'Compression Quality', 'option label', 'wpsso-tune-image-editors' ),
							$css_class = '', $css_id = 'tie_imagick_jpeg_compress_quality' ) . 
						'<td>' . $this->form->get_input( 'tie_imagick_jpeg_compress_quality', $css_class = 'short' ) . ' jpeg</td>';

					$table_rows[ 'tie_imagick_jpeg_sharpen_sigma' ] = '' . 
						$this->form->get_th_html( _x( 'Sharpening Sigma', 'option label', 'wpsso-tune-image-editors' ),
							$css_class = '', $css_id = 'tie_imagick_jpeg_sharpen_sigma' ) . 
						'<td>' . $this->form->get_input( 'tie_imagick_jpeg_sharpen_sigma', $css_class = 'short' ) . ' ' . 
						sprintf( _x( 'recommended value is %1$s to %2$s', 'option comment', 'wpsso-tune-image-editors' ), '0.5', '1.0' ) . '</td>';

					$table_rows[ 'tie_imagick_jpeg_sharpen_radius' ] = $this->form->get_tr_hide( 'basic', 'tie_imagick_jpeg_sharpen_radius' ) . 
						$this->form->get_th_html( _x( 'Sharpening Radius', 'option label', 'wpsso-tune-image-editors' ),
							$css_class = '', $css_id = 'tie_imagick_jpeg_sharpen_radius' ) . 
						'<td>' . $this->form->get_input( 'tie_imagick_jpeg_sharpen_radius', $css_class = 'short' ) . ' ' . 
						_x( 'recommended value is 0 (auto)', 'option comment', 'wpsso-tune-image-editors' ) . '</td>';

					$table_rows[ 'tie_imagick_jpeg_sharpen_amount' ] = '' . 
						$this->form->get_th_html( _x( 'Sharpening Amount', 'option label', 'wpsso-tune-image-editors' ),
							$css_class = '', $css_id = 'tie_imagick_jpeg_sharpen_amount' ) . 
						'<td>' . $this->form->get_input( 'tie_imagick_jpeg_sharpen_amount', $css_class = 'short' ) . ' ' . 
						sprintf( _x( 'recommended value is %1$s to %2$s', 'option comment', 'wpsso-tune-image-editors' ), '0.8', '1.2' ) . '</td>';

					$table_rows[ 'tie_imagick_jpeg_sharpen_threshold' ] = '' . 
						$this->form->get_th_html( _x( 'Sharpening Threshold', 'option label', 'wpsso-tune-image-editors' ),
							$css_class = '', $css_id = 'tie_imagick_jpeg_sharpen_threshold' ) . 
						'<td>' . $this->form->get_input( 'tie_imagick_jpeg_sharpen_threshold', $css_class = 'short' ) . ' ' . 
						sprintf( _x( 'recommended value is %1$s to %2$s', 'option comment', 'wpsso-tune-image-editors' ), '0', '0.05' ) . '</td>';

					break;
			}

			return $table_rows;
		}
	}
}
