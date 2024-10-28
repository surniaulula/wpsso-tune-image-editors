<?php
/*
 * Plugin Name: WPSSO Tune WP Image Editors
 * Plugin Slug: wpsso-tune-image-editors
 * Text Domain: wpsso-tune-image-editors
 * Domain Path: /languages
 * Plugin URI: https://wpsso.com/extend/plugins/wpsso-tune-image-editors/
 * Assets URI: https://surniaulula.github.io/wpsso-tune-image-editors/assets/
 * Author: JS Morisset
 * Author URI: https://wpsso.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: Improves the appearance of WordPress images for better click through rates from social and search sites.
 * Requires Plugins: wpsso
 * Requires PHP: 7.4.33
 * Requires At Least: 5.9
 * Tested Up To: 6.7.0
 * Version: 4.1.0
 *
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *      {major}         Major structural code changes and/or incompatible API changes (ie. breaking changes).
 *      {minor}         New functionality was added or improved in a backwards-compatible manner.
 *      {bugfix}        Backwards-compatible bug fixes or small improvements.
 *      {stage}.{level} Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 *
 * Copyright 2018-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoAbstractAddOn' ) ) {

	require_once dirname( __FILE__ ) . '/lib/abstract/add-on.php';
}

if ( ! class_exists( 'WpssoTie' ) ) {

	class WpssoTie extends WpssoAbstractAddOn {

		public $imagick;	// WpssoTieImagick class object.

		protected $p;	// Wpsso class object.

		private static $instance = null;	// WpssoTie class object.

		public function __construct() {

			parent::__construct( __FILE__, __CLASS__ );
		}

		public static function &get_instance() {

			if ( null === self::$instance ) {

				self::$instance = new self;
			}

			return self::$instance;
		}

		public function init_textdomain() {

			load_plugin_textdomain( 'wpsso-tune-image-editors', false, 'wpsso-tune-image-editors/languages/' );
		}

		/*
		 * Called by Wpsso->set_objects() which runs at init priority 10.
		 */
		public function init_objects_preloader() {

			$this->p =& Wpsso::get_instance();

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( $this->get_missing_requirements() ) {	// Returns false or an array of missing requirements.

				return;	// Stop here.
			}

			new WpssoTieFilters( $this->p, $this );

			/*
			 * See WpssoTieFiltersWp->image_make_intermediate_size().
			 */
			$this->imagick = new WpssoTieImagick( $this->p, $this );
		}
	}

	WpssoTie::get_instance();
}
