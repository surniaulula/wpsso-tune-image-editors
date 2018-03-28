<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoTieGplAdminTieGeneral' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoTieGplAdminTieGeneral {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$this->p->util->add_plugin_filters( $this, array( 
				'tie_wp_general_rows' => 2,
				'tie_ext_imagick_rows' => 2,
			) );
		}

		public function filter_tie_wp_general_rows( $table_rows, $form ) {

			$table_rows[] = '<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg', array( 'lca' => 'wpssotie' ) ).'</td>';

			$table_rows[] = ''.
			$form->get_th_html( _x( 'Adjustment Filter Priority', 'option label', 'wpsso-tune-image-extension' ), '', 'tie_wp_image_adj_filter_prio' ).
			'<td class="blank">'.$form->get_no_input( 'tie_wp_image_adj_filter_prio', 'medium' ).'</td>';

			return $table_rows;
		}

		public function filter_tie_ext_imagick_rows( $table_rows, $form ) {

			$table_rows[] = '<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg', array( 'lca' => 'wpssotie' ) ).'</td>';

			$table_rows[] = $form->get_tr_hide( 'basic', 'tie_imagick_contrast_leveling' ).
			$form->get_th_html( _x( 'Contrast Leveling', 'option label', 'wpsso-tune-image-extension' ), '', 'tie_imagick_contrast_leveling' ).
			'<td class="blank">'.$form->get_no_checkbox( 'tie_imagick_contrast_leveling' ).'</td>';

			$table_rows[] = ''.
			$form->get_th_html( _x( 'Compression Quality', 'option label', 'wpsso-tune-image-extension' ), '', 'tie_imagick_compress_quality' ).
			'<td class="blank">'.$form->get_no_input( 'tie_imagick_compress_quality', 'short' ).'</td>';

			$table_rows[] = ''.
			$form->get_th_html( _x( 'Sharpening Sigma', 'option label', 'wpsso-tune-image-extension' ), '', 'tie_imagick_sharpen_sigma' ).
			'<td class="blank">'.$form->get_no_input( 'tie_imagick_sharpen_sigma', 'short' ).' '.
				_x( 'recommended value is 0.5 to 1.0', 'option comment', 'wpsso-tune-image-extension' ).'</td>';

			$table_rows[] = $form->get_tr_hide( 'basic', 'tie_imagick_sharpen_radius' ).
			$form->get_th_html( _x( 'Sharpening Radius', 'option label', 'wpsso-tune-image-extension' ), '', 'tie_imagick_sharpen_radius' ).
			'<td class="blank">'.$form->get_no_input( 'tie_imagick_sharpen_radius', 'short' ).' '.
				_x( 'recommended value is 0 (auto)', 'option comment', 'wpsso-tune-image-extension' ).'</td>';

			$table_rows[] = ''.
			$form->get_th_html( _x( 'Sharpening Amount', 'option label', 'wpsso-tune-image-extension' ), '', 'tie_imagick_sharpen_amount' ).
			'<td class="blank">'.$form->get_no_input( 'tie_imagick_sharpen_amount', 'short' ).' '.
				_x( 'recommended value is 0.8 to 1.2', 'option comment', 'wpsso-tune-image-extension' ).'</td>';

			$table_rows[] = ''.
			$form->get_th_html( _x( 'Sharpening Threshold', 'option label', 'wpsso-tune-image-extension' ), '', 'tie_imagick_sharpen_threshold' ).
			'<td class="blank">'.$form->get_no_input( 'tie_imagick_sharpen_threshold', 'short' ).' '.
				_x( 'recommended value is 0 to 0.05', 'option comment', 'wpsso-tune-image-extension' ).'</td>';

			return $table_rows;
		}
	}
}
