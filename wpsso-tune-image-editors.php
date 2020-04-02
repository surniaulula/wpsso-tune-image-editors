<?php
/**
 * Plugin Name: WPSSO Tune Image Editors
 * Plugin Slug: wpsso-tune-image-editors
 * Text Domain: wpsso-tune-image-editors
 * Domain Path: /languages
 * Plugin URI: https://wpsso.com/extend/plugins/wpsso-tune-image-editors/
 * Assets URI: https://surniaulula.github.io/wpsso-tune-image-editors/assets/
 * Author: JS Morisset
 * Author URI: https://wpsso.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: Sharpen and improve WordPress thumbnails and resized images for social sharing and better SEO.
 * Requires PHP: 5.6
 * Requires At Least: 4.0
 * Tested Up To: 5.4
 * Version: 2.1.0-dev.1
 * 
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *      {major}         Major structural code changes / re-writes or incompatible API changes.
 *      {minor}         New functionality was added or improved in a backwards-compatible manner.
 *      {bugfix}        Backwards-compatible bug fixes or small improvements.
 *      {stage}.{level} Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 * 
 * Copyright 2016-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoTie' ) ) {

	class WpssoTie {

		/**
		 * Wpsso plugin class object variable.
		 */
		public $p;		// Wpsso

		/**
		 * Library class object variables.
		 */
		public $filters;	// WpssoTieFilters
		public $reg;		// WpssoTieRegister

		/**
		 * Reference Variables (config, options, modules, etc.).
		 */
		private $have_min_version = true;	// Have minimum wpsso version.

		private static $instance;

		public function __construct() {

			require_once dirname( __FILE__ ) . '/lib/config.php';

			WpssoTieConfig::set_constants( __FILE__ );

			WpssoTieConfig::require_libs( __FILE__ );	// Includes the register.php class library.

			$this->reg = new WpssoTieRegister();		// Activate, deactivate, uninstall hooks.

			/**
			 * Check for required plugins and show notices.
			 */
			add_action( 'all_admin_notices', array( __CLASS__, 'show_required_notices' ) );

			/**
			 * Add WPSSO filter hooks.
			 */
			add_filter( 'wpsso_get_config', array( $this, 'wpsso_get_config' ), 10, 2 );	// Checks core version and merges config array.
			add_filter( 'wpsso_get_avail', array( $this, 'wpsso_get_avail' ), 10, 1 );

			/**
			 * Add WPSSO action hooks.
			 */
			add_action( 'wpsso_init_textdomain', array( __CLASS__, 'wpsso_init_textdomain' ) );
			add_action( 'wpsso_init_options', array( $this, 'wpsso_init_options' ), 100 );	// Sets the $this->p reference variable.
			add_action( 'wpsso_init_objects', array( $this, 'wpsso_init_objects' ), 10 );
			add_action( 'wpsso_init_plugin', array( $this, 'wpsso_init_plugin' ), 10 );
		}

		public static function &get_instance() {

			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Check for required plugins and show notices.
		 */
		public static function show_required_notices() {

			$info = WpssoTieConfig::$cf[ 'plugin' ][ 'wpssotie' ];

			foreach ( $info[ 'req' ] as $ext => $req_info ) {

				if ( isset( $req_info[ 'class' ] ) ) {	// Just in case.
					if ( class_exists( $req_info[ 'class' ] ) ) {
						continue;	// Requirement satisfied.
					}
				} else continue;	// Nothing to check.

				$deactivate_url = html_entity_decode( wp_nonce_url( add_query_arg( array(
					'action'        => 'deactivate',
					'plugin'        => $info[ 'base' ],
					'plugin_status' => 'all',
					'paged'         => 1,
					's'             => '',
				), admin_url( 'plugins.php' ) ), 'deactivate-plugin_' . $info[ 'base' ] ) );

				self::wpsso_init_textdomain();	// If not already loaded, load the textdomain now.

				$notice_msg = __( 'The %1$s add-on requires the %2$s plugin &mdash; install and activate the plugin or <a href="%3$s">deactivate this add-on</a>.', 'wpsso-tune-image-editors' );

				echo '<div class="notice notice-error error"><p>';
				echo sprintf( $notice_msg, $info[ 'name' ], $req_info[ 'name' ], $deactivate_url );
				echo '</p></div>';
			}
		}

		public static function wpsso_init_textdomain() {

			static $do_once = null;

			if ( true === $do_once ) {
				return;
			}

			$do_once = true;

			load_plugin_textdomain( 'wpsso-tune-image-editors', false, 'wpsso-tune-image-editors/languages/' );
		}

		/**
		 * Checks the core plugin version and merges the extension / add-on config array.
		 */
		public function wpsso_get_config( $cf, $plugin_version = 0 ) {

			$info = WpssoTieConfig::$cf[ 'plugin' ][ 'wpssotie' ];

			$req_info = $info[ 'req' ][ 'wpsso' ];

			if ( version_compare( $plugin_version, $req_info[ 'min_version' ], '<' ) ) {

				$this->have_min_version = false;

				return $cf;
			}

			return SucomUtil::array_merge_recursive_distinct( $cf, WpssoTieConfig::$cf );
		}

		public function wpsso_get_avail( $avail ) {

			if ( ! $this->have_min_version ) {

				$avail[ 'p_ext' ][ 'tie' ] = false;	// Signal that this extension / add-on is not available.

				return $avail;
			}

			$avail[ 'p_ext' ][ 'tie' ] = true;		// Signal that this extension / add-on is available.

			return $avail;
		}

		/**
		 * Sets the $this->p reference variable for the core plugin instance.
		 */
		public function wpsso_init_options() {

			$this->p =& Wpsso::get_instance();

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}
		}

		public function wpsso_init_objects() {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ! $this->have_min_version ) {
				return;	// Stop here.
			}

			$this->filters = new WpssoTieFilters( $this->p );
		}

		/**
		 * All WPSSO objects are instantiated and configured.
		 */
		public function wpsso_init_plugin() {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( ! $this->have_min_version ) {

				$this->min_version_notice();	// Show minimum version notice.

				return;	// Stop here.
			}
		}

		private function min_version_notice() {

			if ( ! is_admin() ) {
				return;
			}

			$info = WpssoTieConfig::$cf[ 'plugin' ][ 'wpssotie' ];

			$req_info = $info[ 'req' ][ 'wpsso' ];

			$notice_msg = sprintf( __( 'The %1$s version %2$s add-on requires %3$s version %4$s or newer (version %5$s is currently installed).',
				'wpsso-tune-image-editors' ), $info[ 'name' ], $info[ 'version' ], $req_info[ 'name' ], $req_info[ 'min_version' ],
					$this->p->cf[ 'plugin' ][ 'wpsso' ][ 'version' ] );

			$this->p->notice->err( $notice_msg );

			if ( method_exists( $this->p->admin, 'get_check_for_updates_link' ) ) {

				$update_msg = $this->p->admin->get_check_for_updates_link();

				if ( ! empty( $update_msg ) ) {
					$this->p->notice->inf( $update_msg );
				}
			}
		}
	}

	WpssoTie::get_instance();
}
