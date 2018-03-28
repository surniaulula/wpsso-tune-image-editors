<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoTieSubmenuTieGeneral' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoTieSubmenuTieGeneral extends WpssoAdmin {

		private $implementations = array();

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {
			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$this->menu_id = $id;
			$this->menu_name = $name;
			$this->menu_lib = $lib;
			$this->menu_ext = $ext;
		}

		// called by the extended WpssoAdmin class
		protected function add_meta_boxes() {

			/**
			 * Load the WP class libraries to avoid triggering a known bug in EWWW
			 * when applying the 'wp_image_editors' filter.
			 */
			require_once ABSPATH . WPINC . '/class-wp-image-editor.php';
			require_once ABSPATH . WPINC . '/class-wp-image-editor-gd.php';
			require_once ABSPATH . WPINC . '/class-wp-image-editor-imagick.php';

			$this->implementations = apply_filters( 'wp_image_editors', array( 'WP_Image_Editor_Imagick', 'WP_Image_Editor_GD' ) );

			add_meta_box( $this->pagehook.'_wp',
				_x( 'WordPress Settings', 'metabox title', 'wpsso-tune-image-extension' ), 
					array( &$this, 'show_metabox_wp' ), $this->pagehook, 'normal' );

			add_meta_box( $this->pagehook.'_ext',
				_x( 'PHP Extension Settings', 'metabox title', 'wpsso-tune-image-extension' ), 
					array( &$this, 'show_metabox_ext' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_wp() {

			$metabox_id = 'tie-wp';
			$tab_key = 'general';

			$this->p->util->do_table_rows( apply_filters( SucomUtil::sanitize_hookname( $this->p->lca.'_'.$metabox_id.'_'.$tab_key.'_rows' ),
				$this->get_table_rows( $metabox_id, $tab_key ), $this->form ), 'metabox-'.$metabox_id.'-'.$tab_key );
		}

		public function show_metabox_ext() {

			$metabox_id = 'tie-ext';
			$tabs = array( 'imagick' => _x( 'ImageMagick', 'metabox tab', 'wpsso-tune-image-extension' ) );
			$table_rows = array();

			foreach ( $tabs as $tab_key => $title ) {
				$table_rows[$tab_key] = array_merge( $this->get_table_rows( $metabox_id, $tab_key ), 
					apply_filters( SucomUtil::sanitize_hookname( $this->p->lca.'_'.$metabox_id.'_'.$tab_key.'_rows' ), array(), $this->form ) );
			}

			$this->p->util->do_metabox_tabs( $metabox_id, $tabs, $table_rows );
		}

		protected function get_table_rows( $metabox_id, $tab_key ) {

			$table_rows = array();

			switch ( $metabox_id.'-'.$tab_key ) {

				case 'tie-wp-general':

					$table_rows[] = ''.
					$this->form->get_th_html( _x( 'Default WordPress Image Editor(s)', 'option label', 'wpsso-tune-image-extension' ), '', 'tie_wp_image_editors' ).
					'<td>'.$this->form->get_select( 'tie_wp_image_editors', $this->p->cf['form']['editors'], '', '', true ).'</td>';

					break;

				case 'tie-ext-imagick':

					$wp_imagick_position = array_search( 'WP_Image_Editor_Imagick', $this->implementations );

					if ( $wp_imagick_position !== false ) {
						$wp_imagick_status = '<font color="green">'.sprintf( __( 'Used as editor #%s', 'wpsso-tune-image-extension' ), $wp_imagick_position + 1 ).'</font>';
					} else {
						$wp_imagick_status = '<font color="red">'.__( 'Not used', 'wpsso-tune-image-extension' ).'</font>';
					}

					if ( extension_loaded( 'imagick' ) ) {
						$php_imagick_status = '<font color="green">'.__( 'Loaded', 'wpsso-tune-image-extension' ).'</font>';
					} else {
						$php_imagick_status = '<font color="red">'.__( 'Not loaded', 'wpsso-tune-image-extension' ).'</font>';
					}

					$table_rows[] = ''.
					$this->form->get_th_html( _x( 'WordPress ImageMagick Editor', 'option label', 'wpsso-tune-image-extension' ), '', 'tie_wp_imagick_avail' ).
					'<td><strong>'.$wp_imagick_status.'</strong></td>';

					$table_rows[] = ''.
					$this->form->get_th_html( _x( 'PHP ImageMagick Extension', 'option label', 'wpsso-tune-image-extension' ), '', 'tie_php_imagick_avail' ).
					'<td><strong>'.$php_imagick_status.'</strong></td>';

					break;
			}

			return $table_rows;
		}
	}
}